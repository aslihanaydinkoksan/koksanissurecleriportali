<?php

namespace App\Http\Controllers;

use App\Models\VehicleAssignment;
use App\Models\Vehicle;
use App\Models\LogisticsVehicle;
use App\Models\ServiceSchedule;
use App\Models\User;
use App\Models\Team;
use App\Models\Customer;
use App\Notifications\VehicleAssignmentCreated;
use App\Notifications\NewRequestForManager;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\TaskStatusUpdatedNotification;
use App\Services\CsvExporter;
use App\Notifications\VehicleAssignedNotification;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerProduct;
use App\Models\CustomerSample;

class VehicleAssignmentController extends Controller
{
    /**
     * Araç atamalarını listeler ve filtreler.
     */
    public function index(Request $request): View
    {
        $query = VehicleAssignment::with(['vehicle', 'createdBy', 'responsible', 'files'])
            ->where('assignment_type', 'vehicle');

        $query->where(function ($q) {
            $q->whereNotNull('vehicle_id') // Aracı atanmışlar
                ->orWhereIn('status', ['pending', 'waiting_assignment', 'in_progress', 'approved']); // Veya işlemdekiler
        });

        $query->whereIn('responsible_type', [
            User::class,
            Team::class
        ]);

        // --- FİLTRELEME ---
        if ($request->filled('vehicle_id')) {
            $parts = explode('|', $request->input('vehicle_id'));
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];
                $query->where('vehicle_type', $type)->where('vehicle_id', $id);
            }
        }
        if ($request->filled('assignment_type')) {
            $query->where('assignment_type', $request->input('assignment_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('task_description', 'LIKE', "%{$search}%")
                    ->orWhere('destination', 'LIKE', "%{$search}%");
            });
        }

        // Tarih filtreleri
        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where('end_time', '>=', $dateFrom);
            } catch (\Exception $e) {
            }
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                $query->where('start_time', '<=', $dateTo);
            } catch (\Exception $e) {
            }
        }
        $assignments = $query->orderByRaw("CASE WHEN status IN ('pending', 'waiting_assignment') THEN 0 ELSE 1 END")
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        $filters = $request->only(['vehicle_id', 'assignment_type', 'status', 'search', 'date_from', 'date_to']);

        $companyVehicles = Vehicle::active()->orderBy('plate_number')->get()->map(function ($vehicle) {
            $vehicle->filter_key = get_class($vehicle) . '|' . $vehicle->id;
            $vehicle->display_name = '🚙 ' . $vehicle->plate_number . ' - ' . $vehicle->brand_model;
            return $vehicle;
        });

        $logisticsVehicles = LogisticsVehicle::active()->orderBy('plate_number')->get()->map(function ($vehicle) {
            $vehicle->filter_key = get_class($vehicle) . '|' . $vehicle->id;
            $vehicle->display_name = '🚚 ' . $vehicle->plate_number . ' - ' . $vehicle->brand . ' ' . $vehicle->model;
            return $vehicle;
        });

        $vehicles = $companyVehicles->merge($logisticsVehicles);

        return view('service.assignments.index', compact('assignments', 'filters', 'vehicles'));
    }
    /**
     * Araçsız (Genel) görevleri listeler.
     */
    public function generalIndex(Request $request): View
    {
        $query = VehicleAssignment::with(['createdBy', 'responsible', 'files'])
            ->where('assignment_type', 'general');
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('task_description', 'LIKE', "%{$search}%");
            });
        }

        $assignments = $query->orderBy('start_time', 'desc')->paginate(15);
        return view('service.assignments.general_index', compact('assignments'));
    }

    /**
     * Yeni araç atama formunu gösterir.
     */
    public function create(): View|RedirectResponse
    {
        //  Şirket araçları ve Nakliye araçlarını ayrı ayrı çek
        $companyVehicles = Vehicle::active()->orderBy('plate_number')->get();
        $logisticsVehicles = LogisticsVehicle::where('status', 'active')->orderBy('plate_number')->get();

        // Kullanıcıları ve Takımları al
        $users = User::orderBy('name')->get();
        $teams = Team::active()->with('users')->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('service.assignments.create', compact('companyVehicles', 'logisticsVehicles', 'users', 'teams', 'customers'));
    }
    /**
     * Kullanıcının başkalarına atadığı görevleri listeler.
     */
    public function assignedByMe(): View
    {
        $user = Auth::user();

        $assignments = VehicleAssignment::with(['vehicle', 'responsible'])
            ->where('user_id', $user->id) // user_id = Görevi Oluşturan (Creator)
            ->latest('created_at')
            ->paginate(15);

        return view('service.assignments.assigned_by_me', compact('assignments'));
    }

    /**
     * Yeni görev/araç talebi kaydetme
     */
    public function store(Request $request): RedirectResponse
    {
        // --- CRM "Lojistik" Sekmesinden Gelen İstek ---
        if ($request->has('type') && $request->input('type') === 'logistics') {

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'customer_id' => 'required|exists:customers,id',
                'vehicle_id' => 'nullable|exists:vehicles,id',
                'start_time' => 'required|date',
                
                // Gönderi Türü ve Numune/Ürün Ayrımı
                'shipment_type' => 'required|in:product,sample',
                'customer_product_id' => 'nullable|required_if:shipment_type,product|exists:customer_products,id',
                'customer_sample_id' => 'nullable|required_if:shipment_type,sample|exists:customer_samples,id',
                
                'quantity' => 'nullable|numeric',
                'unit' => 'nullable|string',
                'description' => 'nullable|string',
                'user_id' => 'nullable|exists:users,id',
            ]);

            DB::beginTransaction();
            try {
                $startTime = Carbon::parse($validated['start_time']);
                $endTime = $startTime->copy()->addHours(4); // Varsayılan süre

                // Gönderi türüne göre verileri temizle (Ürün seçiliyse numuneyi, numune seçiliyse ürünü sıfırla)
                $productId = $validated['shipment_type'] === 'product' ? ($validated['customer_product_id'] ?? null) : null;
                $sampleId = $validated['shipment_type'] === 'sample' ? ($validated['customer_sample_id'] ?? null) : null;

                // 1. ADIM: MÜŞTERİ MODÜLÜ İÇİN GÖREV (VehicleAssignment) OLUŞTUR
                $assignment = VehicleAssignment::create([
                    'business_unit_id' => 1,
                    'assignment_type' => 'logistics',
                    'title' => $validated['title'],
                    'customer_id' => $validated['customer_id'],
                    'vehicle_id' => $validated['vehicle_id'],
                    'vehicle_type' => $validated['vehicle_id'] ? Vehicle::class : null,
                    'user_id' => Auth::id(),
                    'assigned_by' => Auth::id(),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'shipment_type' => $validated['shipment_type'],
                    'customer_product_id' => $productId,
                    'customer_sample_id' => $sampleId,
                    'quantity' => $validated['quantity'] ?? null,
                    'unit' => $validated['unit'] ?? null,
                    'task_description' => $validated['description'] ?? $validated['title'],
                    'status' => 'pending',
                    'responsible_type' => User::class,
                    'responsible_id' => $validated['user_id'] ?? Auth::id(),
                    'is_important' => false
                ]);

                // 2. ADIM: LOJİSTİK MODÜLÜ İÇİN SEVKİYAT (Shipment) TASLAĞI OLUŞTUR
                $kargoIcerigi = $validated['title'];
                if ($validated['shipment_type'] === 'product' && $productId) {
                    $product = CustomerProduct::find($productId);
                    $kargoIcerigi = "Ürün: " . ($product->name ?? 'Bilinmiyor');
                } elseif ($validated['shipment_type'] === 'sample' && $sampleId) {
                    $sample = CustomerSample::find($sampleId);
                    $kargoIcerigi = "Numune: " . ($sample->subject ?? 'Bilinmiyor');
                }

                $plaka = null;
                if ($validated['vehicle_id']) {
                    $vehicle = Vehicle::find($validated['vehicle_id']);
                    $plaka = $vehicle ? $vehicle->plate_number : null;
                }

                Shipment::create([
                    'vehicle_assignment_id' => $assignment->id, // Köprüyü Kurduk!
                    'business_unit_id' => 1,
                    'user_id' => Auth::id(), 
                    'created_by' => Auth::id(),
                    'shipment_status' => 'pending', 
                    'shipment_type' => 'export', // Giden mal
                    'is_important' => false,
                    'arac_tipi' => 'kamyon', // Varsayılan
                    'plaka' => $plaka,
                    'sofor_adi' => $assignment->responsible ? $assignment->responsible->name : null,
                    'kargo_icerigi' => $kargoIcerigi,
                    'kargo_tipi' => $validated['shipment_type'] === 'sample' ? 'Numune' : 'Standart Ürün',
                    'kargo_miktari' => ($validated['quantity'] ?? 'Belirtilmedi') . ' ' . ($validated['unit'] ?? ''),
                    'cikis_tarihi' => $startTime,
                    'tahmini_varis_tarihi' => $endTime,
                    'aciklamalar' => "CRM (Müşteri Kartı) üzerinden oluşturulan otomatik taslak kaydıdır.\nNot: " . ($validated['description'] ?? ''),
                ]);

                DB::commit();
                return back()->with('success', 'Lojistik görevi oluşturuldu ve Lojistik Departmanına taslak olarak iletildi.');

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('CRM Lojistik Kayıt Hatası: ' . $e->getMessage());
                return back()->with('error', 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage());
            }
        }
        
        // --- SENARYO 2: STANDART ARAÇ TALEP İŞLEMİ (Eski Kod) ---
        $vehicleTypeInput = $request->input('vehicle_type');

        // 1. Validasyon
        $validatedData = $request->validate([
            'needs_vehicle' => 'required|in:yes,no',
            'vehicle_type' => 'nullable|required_if:needs_vehicle,yes|in:company,logistics',
            'responsible_type' => 'required|in:user,team',
            'responsible_user_id' => 'required_if:responsible_type,user|exists:users,id',
            'responsible_team_id' => 'required_if:responsible_type,team|exists:teams,id',
            'title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'destination' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'nullable|date|after:start_time',
        ]);

        $assignmentType = $validatedData['needs_vehicle'] === 'yes' ? 'vehicle' : 'general';

        // 2. Modeli Doldur
        $assignment = new VehicleAssignment();
        $assignment->assignment_type = $assignmentType;
        $assignment->title = $validatedData['title'];
        $assignment->task_description = $validatedData['task_description'];
        $assignment->destination = $validatedData['destination'] ?? null;
        $assignment->requester_name = Auth::user()->name;
        $assignment->notes = $validatedData['notes'] ?? null;
        $assignment->user_id = auth()->id();
        $assignment->assigned_by = auth()->id();
        $assignment->customer_id = $request->input('customer_id');

        // Sorumlu Ata (Polymorphic İlişki)
        if ($validatedData['responsible_type'] === 'user') {
            $assignment->responsible_type = User::class;
            $assignment->responsible_id = $validatedData['responsible_user_id'];
        } else {
            $assignment->responsible_type = Team::class;
            $assignment->responsible_id = $validatedData['responsible_team_id'];
        }

        // 3. Durum ve Tarih Ayarları
        if ($assignmentType === 'vehicle') {
            // Araç talebi ise 'pending' (yönetici onayı bekliyor)
            $assignment->status = 'pending';
            $assignment->vehicle_id = null;

            if ($vehicleTypeInput === 'logistics') {
                $assignment->vehicle_type = LogisticsVehicle::class;
            } else {
                $assignment->vehicle_type = Vehicle::class;
            }
            $successMessage = 'Araç talebiniz başarıyla oluşturuldu ve Ulaştırma birimine iletildi.';
        } else {
            // Genel görev ise direkt 'pending' (yapılmayı bekliyor)
            $assignment->status = 'pending';
            $successMessage = 'Genel görev başarıyla atandı.';
        }

        $assignment->start_time = Carbon::parse($validatedData['start_time']);
        $assignment->end_time = isset($validatedData['end_time']) ? Carbon::parse($validatedData['end_time']) : null;

        $assignment->save();

        // --- 4. BİLDİRİM MANTIĞI ---
        try {
            // SENARYO A: ARAÇ TALEBİ İSE -> YÖNETİCİLERE GİT
            if ($assignmentType === 'vehicle') {
                $managers = User::where(function ($query) {
                    $query->where('role', 'admin')
                        ->orWhere(function ($q) {
                            $q->whereIn('role', ['müdür', 'yönetici', 'mudur'])
                                ->whereHas('department', function ($d) {
                                    $d->where('slug', 'ulastirma');
                                });
                        });
                })->get();

                if ($managers->count() > 0) {
                    Notification::send($managers, new NewRequestForManager($assignment));
                }
            }

            // SENARYO B: GENEL GÖREV İSE -> ATANAN KİŞİYE GİT
            else {
                $assigneeRecipients = collect();

                Log::info('Genel Görev Atama Başladı', [
                    'Sorumlu Tipi' => $validatedData['responsible_type'],
                    'Sorumlu ID' => $validatedData['responsible_type'] === 'user' ? $validatedData['responsible_user_id'] : $validatedData['responsible_team_id']
                ]);

                if ($validatedData['responsible_type'] === 'user') {
                    $userId = (int) $validatedData['responsible_user_id'];
                    $user = User::find($userId);

                    if ($user) {
                        Log::info('Kullanıcı bulundu:', ['isim' => $user->name, 'id' => $user->id]);
                        if ($user->id !== auth()->id()) {
                            $assigneeRecipients->push($user);
                        } else {
                            Log::warning('Kullanıcı kendine görev atadığı için bildirim gönderilmedi.');
                        }
                    } else {
                        Log::error('Atanacak kullanıcı veritabanında bulunamadı ID: ' . $userId);
                    }
                } elseif ($validatedData['responsible_type'] === 'team') {
                    $teamId = (int) $validatedData['responsible_team_id'];
                    $team = Team::with('users')->find($teamId);

                    if ($team) {
                        Log::info('Takım bulundu:', ['takim' => $team->name, 'uye_sayisi' => $team->users->count()]);
                        $assigneeRecipients = $team->users->filter(fn($u) => $u->id !== auth()->id());
                    } else {
                        Log::error('Atanacak takım bulunamadı ID: ' . $teamId);
                    }
                }

                if ($assigneeRecipients->isNotEmpty()) {
                    Log::info('Bildirim gönderiliyor. Alıcı sayısı: ' . $assigneeRecipients->count());
                    try {
                        Notification::send($assigneeRecipients, new TaskAssignedNotification($assignment));
                        Log::info('Bildirim başarıyla kuyruğa/veritabanına gönderildi.');
                    } catch (\Exception $e) {
                        Log::error('Notification::send hatası: ' . $e->getMessage());
                    }
                } else {
                    Log::warning('Alıcı listesi boş, bildirim gönderilmedi.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Bildirim gönderilirken hata oluştu: ' . $e->getMessage());
        }

        $redirectRoute = ($assignmentType === 'vehicle') ? 'service.assignments.index' : 'service.general-tasks.index';
        return redirect()->route($redirectRoute)->with('success', $successMessage);
    }

    /**
     * YENİ FONKSİYON: Müdür (Ömer Bey) için Araç Atama İşlemi
     * Route: PUT /service/assignments/{assignment}/assign
     */
    public function assignVehicle(Request $request, VehicleAssignment $assignment): RedirectResponse
    {
        // Yetki kontrolü (Opsiyonel: Sadece Müdür yapabilsin)
        // if (!auth()->user()->hasRole('Müdür')) { abort(403); }

        $validated = $request->validate([
            'vehicle_id' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            // Ek olarak şoför vs. seçtiriyorsanız buraya ekleyebilirsiniz.
        ]);

        // Aracı Kontrol Et (Tipe göre doğru tabloda var mı?)
        if ($assignment->vehicle_type == LogisticsVehicle::class) {
            if (!LogisticsVehicle::where('id', $validated['vehicle_id'])->exists()) {
                return back()->withErrors(['vehicle_id' => 'Seçilen nakliye aracı bulunamadı.']);
            }
        } else {
            if (!Vehicle::where('id', $validated['vehicle_id'])->exists()) {
                return back()->withErrors(['vehicle_id' => 'Seçilen şirket aracı bulunamadı.']);
            }
        }

        // Atamayı Yap
        $assignment->vehicle_id = $validated['vehicle_id'];
        $assignment->start_time = Carbon::parse($validated['start_time']);
        $assignment->end_time = Carbon::parse($validated['end_time']);
        $assignment->status = 'pending'; // Artık görev aktif ve yapılmayı bekliyor

        // Atamayı Yapan (Müdür) olarak not düşülebilir veya loglanabilir
        $assignment->assigned_by = auth()->id();

        $assignment->save();

        // Talep edene (Requester) Bildirim Gönder: "Aracınız atandı!"
        if ($assignment->createdBy) {
            $assignment->createdBy->notify(new VehicleAssignmentCreated($assignment)); // Mesaj içeriği "Atandı" olarak dinamikleşmeli
        }

        // Görevi yapacak kişiye (Sorumlu) Bildirim Gönder
        $this->forceNotificationUnread($assignment);

        return back()->with('success', 'Araç ataması başarıyla yapıldı ve ilgililere bildirildi.');
    }

    public function show(VehicleAssignment $assignment): View
    {
        $assignment->load(['vehicle', 'createdBy', 'responsible', 'files.uploader']);
        return view('service.assignments.show', compact('assignment'));
    }

    /**
     * Bir sonraki sefer zamanını bulur.
     */
    private function findNextDeparture(): ?Carbon
    {
        $localTimezone = 'Europe/Istanbul';
        $schedules = ServiceSchedule::where('is_active', true)
            ->orderBy('departure_time')
            ->get();

        if ($schedules->isEmpty()) {
            return null;
        }

        $now = Carbon::now($localTimezone);

        // Bugünkü seferlere bak
        foreach ($schedules as $schedule) {
            $departureTime = Carbon::today($localTimezone)
                ->setTimeFromTimeString($schedule->departure_time);
            $cutoffTime = $departureTime->copy()->subMinutes($schedule->cutoff_minutes);

            if ($now->lt($cutoffTime)) {
                return $departureTime;
            }
        }

        // Bugün uygun sefer yoksa yarının ilk seferi
        $firstSchedule = $schedules->first();
        return Carbon::tomorrow($localTimezone)
            ->setTimeFromTimeString($firstSchedule->departure_time);
    }

    /**
     * Düzenleme formu
     */
    public function edit(VehicleAssignment $assignment): View
    {
        $this->authorize('manage-assignment', $assignment);

        $companyVehicles = Vehicle::active()->orderBy('plate_number')->get();
        $logisticsVehicles = LogisticsVehicle::where('status', 'active')->orderBy('plate_number')->get();
        $users = User::orderBy('name')->get();
        $teams = Team::active()->with('users')->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('service.assignments.edit', compact(
            'assignment',
            'companyVehicles',
            'logisticsVehicles',
            'users',
            'teams',
            'customers',
        ));
    }

    /**
     * Güncelleme Metodu (Final Versiyon)
     */
    /**
     * Güncelleme Metodu (Final Versiyon - CRM Destekli)
     */
    public function update(Request $request, VehicleAssignment $assignment)
    {
        if ($request->has('type') && $request->input('type') === 'logistics') {

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'customer_id' => 'required|exists:customers,id',
                'vehicle_id' => 'nullable|exists:vehicles,id',
                'start_time' => 'required|date',

                // YENİ ALANLAR: Gönderi Türü ve Numune/Ürün Ayrımı
                'shipment_type' => 'required|in:product,sample',
                'customer_product_id' => 'nullable|required_if:shipment_type,product|exists:customer_products,id',
                'customer_sample_id' => 'nullable|required_if:shipment_type,sample|exists:customer_samples,id',

                'quantity' => 'nullable|numeric',
                'unit' => 'nullable|string',
                'description' => 'nullable|string',
                'user_id' => 'nullable|exists:users,id',
                'status' => 'required|in:pending,on_road,completed,cancelled',
            ]);

            $startTime = Carbon::parse($validated['start_time']);
            $endTime = $startTime->copy()->addHours(4);

            $productId = $validated['shipment_type'] === 'product' ? ($validated['customer_product_id'] ?? null) : null;
            $sampleId = $validated['shipment_type'] === 'sample' ? ($validated['customer_sample_id'] ?? null) : null;

            $assignment->update([
                'title' => $validated['title'],
                'customer_id' => $validated['customer_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'vehicle_type' => $validated['vehicle_id'] ? Vehicle::class : null,
                'start_time' => $startTime,
                'end_time' => $endTime,

                // YENİ VERİLER GÜNCELLENİYOR
                'shipment_type' => $validated['shipment_type'],
                'customer_product_id' => $productId,
                'customer_sample_id' => $sampleId,

                'quantity' => $validated['quantity'],
                'unit' => $validated['unit'],
                'task_description' => $validated['description'] ?? $validated['title'],
                'status' => $validated['status'],
                'responsible_id' => $validated['user_id'] ?? $assignment->responsible_id,
            ]);

            return back()->with('success', 'Lojistik görevi güncellendi.');
        }

        // --- SENARYO 2: STANDART ARAÇ TALEP GÜNCELLEMESİ (Eski Kodun) ---

        // 1. Validasyon Kuralları
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'status' => 'required|in:waiting_assignment,pending,in_progress,completed,cancelled',
            'destination' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string',
            'vehicle_selection' => 'nullable|string', // Blade'den gelen birleşik veri
            'start_km' => 'nullable|numeric|min:0',
            'final_km' => ['nullable', 'numeric', 'gte:start_km'],
            'fuel_cost' => 'nullable|numeric|min:0',
            'start_fuel_level' => 'nullable|string',
            'final_fuel' => 'nullable|string',
        ]);

        // 2. DEĞİŞİKLİK ÖNCESİ DURUMLARI SAKLA
        $oldStatus = $assignment->status;
        $oldVehicleId = $assignment->vehicle_id;

        // 3. TEMEL BİLGİLERİ GÜNCELLE
        $assignment->title = $validatedData['title'];
        $assignment->task_description = $validatedData['task_description'];
        $assignment->status = $validatedData['status'];

        if ($request->has('destination')) $assignment->destination = $validatedData['destination'];
        if ($request->has('customer_id')) $assignment->customer_id = $validatedData['customer_id'];
        if ($request->has('notes')) $assignment->notes = $validatedData['notes'];

        // KM ve Yakıt Bilgileri
        $assignment->start_km = $request->input('start_km', $assignment->start_km);
        $assignment->end_km = $request->input('final_km', $assignment->end_km);
        $assignment->start_fuel_level = $request->input('start_fuel_level', $assignment->start_fuel_level);
        $assignment->end_fuel_level = $request->input('final_fuel', $assignment->end_fuel_level);
        $assignment->fuel_cost = $request->input('fuel_cost', $assignment->fuel_cost);

        // 4. ARAÇ SEÇİMİ
        $selection = $request->input('vehicle_selection');
        if (!empty($selection)) {
            $parts = explode('_', $selection);
            if (count($parts) == 2) {
                $typeKey = $parts[0];
                $idVal = $parts[1];
                $assignment->vehicle_id = $idVal;
                $assignment->vehicle_type = ($typeKey === 'logistics') ? 'App\Models\LogisticsVehicle' : 'App\Models\Vehicle';
            }
        } else {
            // Eğer seçim alanı formda yoksa (CRM'den geliyorsa) mevcudu koru, varsa ve boşsa sil
            if ($request->has('vehicle_selection')) {
                $assignment->vehicle_id = null;
                $assignment->vehicle_type = null;
            }
        }

        $assignment->save();

        // 6. BİLDİRİM SİSTEMİ (Aynen koruyoruz)
        try {
            $creator = User::find($assignment->assigned_by);

            if (is_null($oldVehicleId) && !is_null($assignment->vehicle_id)) {
                if ($creator && $creator->id !== auth()->id()) {
                    $creator->notify(new VehicleAssignedNotification($assignment));
                }
            }

            if ($assignment->status !== $oldStatus) {
                if ($creator && $creator->id !== auth()->id()) {
                    $creator->notify(new TaskStatusUpdatedNotification($assignment, $oldStatus));
                }
            }
        } catch (\Exception $e) {
            Log::error('Bildirim Gönderim Hatası: ' . $e->getMessage());
        }

        // 7. YÖNLENDİRME
        $redirectRoute = $assignment->assignment_type === 'vehicle' ? 'service.assignments.index' : 'service.general-tasks.index';
        return redirect()->route($redirectRoute)->with('success', 'Görev başarıyla güncellendi.');
    }

    /**
     * Silme
     */
    public function destroy(VehicleAssignment $assignment): RedirectResponse
    {
        if (Gate::denies('manage-assignment', $assignment)) {
            abort(403, 'Bu işlemi yapma yetkiniz bulunmamaktadır.');
        }

        $assignment->delete();

        return redirect()->route('service.assignments.index')
            ->with('success', 'Görev başarıyla silindi.');
    }

    /**
     * KULLANICININ GÖREVLERİ (Bana Atananlar)
     * Kişisel + Takım Görevleri + Filtreleme + Akıllı Sıralama
     */
    public function myAssignments(Request $request)
    {
        $user = Auth::user();

        // Kullanıcının takımlarını al
        $teamIds = $user->teams()->pluck('teams.id');

        // Temel Sorgu
        // İptal edilenleri varsayılan olarak gizle (ama filtrede seçilirse gelebilir opsiyonu da eklenebilir)
        // Şimdilik sadece aktif işler odağımız olsun.
        $query = VehicleAssignment::with([
            'vehicle',
            'createdBy',
            'responsible' => function ($morphTo) {
                $morphTo->morphWith([
                    Team::class => ['users', 'files'], // Takım üyelerini de yükle (Blade'de kullanıyoruz)
                ]);
            }
        ])
            ->where('status', '<>', 'cancelled');

        // 1. YETKİ SORGUSU
        $query->where(function ($q) use ($user, $teamIds) {
            // A. Doğrudan kullanıcıya atananlar
            $q->where(function ($sub) use ($user) {
                $sub->where('responsible_type', User::class)
                    ->where('responsible_id', $user->id);
            })
                // B. Veya üyesi olduğu takıma atananlar
                ->orWhere(function ($sub) use ($teamIds) {
                    if ($teamIds->isNotEmpty()) {
                        $sub->where('responsible_type', Team::class)
                            ->whereIn('responsible_id', $teamIds);
                    }
                });
        });

        // 2. FİLTRELER
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type')) { // Araçlı mı Genel mi?
            $query->where('assignment_type', $request->input('type'));
        }

        // 3. SIRALAMA
        // Acil işler (Süren, Bekleyen) en üste, Bitenler en alta
        $tasks = $query->orderByRaw("CASE 
                        WHEN status = 'in_progress' THEN 1 
                        WHEN status = 'pending' THEN 2 
                        WHEN status = 'waiting_assignment' THEN 3 
                        ELSE 4 END")
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        // Sayfalama yaparken mevcut query string parametrelerini koru
        $tasks->appends($request->query());

        // Blade dosyasına 'tasks' değişkeniyle gönderiyoruz
        return view('service.assignments.my_assignments', compact('tasks'));
    }
    /**
     * Görev durumunu günceller (AJAX ve Form)
     */
    public function updateStatus(Request $request, VehicleAssignment $assignment)
    {
        // 'on_road' durumunu validasyona ekledik
        $validatedData = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled,on_road,waiting_assignment',
        ]);

        $oldStatus = $assignment->status;
        $newStatus = $validatedData['status'];
        $assignment->status = $newStatus;

        // SENARYO 1: Görev Bitti veya İptal Edildi
        // Bildirimleri temizle ve bitiş zamanını kaydet
        if (in_array($newStatus, ['completed', 'cancelled'])) {
            $this->deleteAssignmentNotifications($assignment);

            if ($newStatus === 'completed' && empty($assignment->end_time)) {
                $assignment->end_time = now();
            }
        }

        // SENARYO 2: Görev Tamamlandı'dan Geri Döndü (Aktifleşti)
        // Eskileri temizle ve YENİ bildirim oluştur
        elseif ($oldStatus === 'completed' && in_array($newStatus, ['pending', 'in_progress', 'on_road'])) {
            $this->deleteAssignmentNotifications($assignment); // Temizlik
            
            // HATA VEREN SATIR BURADAN KALDIRILDI
            // $assignment->end_time = null; 

            // Bildirimi zorla oluştur ve okunmamış yap
            $this->forceNotificationUnread($assignment);
        }

        // SENARYO 3: Aktif Durumlar Arası Geçiş (Örn: Beklemede -> Yolda -> Devam Ediyor)
        // Bildirim varsa okunmamış yap, yoksa yeni oluştur
        elseif (in_array($oldStatus, ['pending', 'in_progress', 'on_road']) && in_array($newStatus, ['pending', 'in_progress', 'on_road'])) {
            $this->forceNotificationUnread($assignment);
        }

        $assignment->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Görev durumu güncellendi.',
                'status' => $assignment->status
            ]);
        }

        return back()->with('success', 'Görev durumu başarıyla güncellendi.');
    }
    /**
     * Bildirimi bulup okunmamış yapar, yoksa yeni oluşturur.
     */
    private function forceNotificationUnread(VehicleAssignment $assignment): void
    {
        // 1. Alıcıları Belirle
        $recipients = collect();
        if ($assignment->responsible_type === User::class) {
            $recipients->push($assignment->responsible);
        } elseif ($assignment->responsible_type === Team::class && $assignment->responsible) {
            $assignment->responsible->loadMissing('users');
            $recipients = $recipients->merge($assignment->responsible->users);
        }

        // 2. Her Alıcı İçin İşlem Yap
        foreach ($recipients->unique() as $recipient) {
            if (!$recipient)
                continue;

            // Mevcut bildirimi ara (Data içindeki ID'ye göre)
            $notification = $recipient->notifications()
                ->where('data', 'like', '%"assignment_id":' . $assignment->id . '%')
                ->latest()
                ->first();

            if ($notification) {
                // VARSA: Sadece okunmamış (null) yap
                $notification->update(['read_at' => null]);
            } else {
                // YOKSA: Yeni bildirim gönder
                $recipient->notify(new VehicleAssignmentCreated($assignment));

                // Ve gönderilen bu yeni bildirimi hemen bulup okunmamış olduğundan emin ol (Garanti)
                // (Sync kuyrukta bu genellikle otomatiktir ama manual update garanti sağlar)
                $latest = $recipient->notifications()->latest()->first();
                if ($latest) {
                    $latest->update(['read_at' => null]);
                }
            }
        }
    }
    /**
     * Göreve ait tüm bildirimleri siler.
     */
    private function deleteAssignmentNotifications(VehicleAssignment $assignment): void
    {
        $recipients = collect();

        if ($assignment->responsible_type === User::class) {
            $recipients->push($assignment->responsible);
        } elseif ($assignment->responsible_type === Team::class && $assignment->responsible) {
            $assignment->responsible->loadMissing('users');
            $recipients = $recipients->merge($assignment->responsible->users);
        }

        foreach ($recipients->unique() as $recipient) {
            if (!$recipient)
                continue;

            $recipient->notifications()
                ->where('data', 'like', '%"assignment_id":' . $assignment->id . '%')
                ->delete();
        }
    }

    /**
     * CSV OLARAK DIŞA AKTAR
     * URL Örnekleri:
     * - /export (Hepsi)
     * - /export?type=general (Sadece Araçsız/Genel)
     * - /export?type=vehicle (Sadece Araçlı)
     */
    public function export(Request $request)
    {
        // 1. İsteğe göre Dosya Adını ve Filtreyi Belirle
        $filterType = $request->query('type'); // URL'den gelen ?type=... parametresi

        $filePrefix = match ($filterType) {
            'general' => 'genel-gorevler-',     // Araçsız
            'vehicle' => 'arac-gorevleri-',     // Araçlı
            default => 'tum-gorevler-',       // Hepsi
        };

        $fileName = $filePrefix . date('d-m-Y') . '.csv';

        // 2. Sorguyu Hazırla
        $query = VehicleAssignment::with(['vehicle', 'createdBy', 'responsible'])
            // Eğer URL'de ?type=general varsa sadece onları getir
            ->when($filterType, function ($q) use ($filterType) {
                return $q->where('assignment_type', $filterType);
            })
            ->latest();

        // 3. Başlıklar
        $headers = [
            'ID',
            'Görev Tipi', // <-- Yeni Sütun: Karışıklık olmasın diye tipi de ekleyelim
            'Görev Başlığı',
            'Plaka',
            'Sorumlu',
            'Görevi Atayan',
            'Başlangıç',
            'Bitiş',
            'Durum',
            'Yakıt (TL)'
        ];

        // 4. Servisi Çağır
        return CsvExporter::streamDownload(
            query: $query,
            headers: $headers,
            fileName: $fileName,
            rowMapper: function ($task) {

                // -- Sorumlu Mantığı --
                $sorumlu = 'Bilinmiyor';
                if ($task->responsible) {
                    $sorumlu = $task->responsible_type === 'App\Models\Team'
                        ? $task->responsible->name . ' (Takım)'
                        : $task->responsible->name;
                }

                // -- Durum Mantığı --
                $durum = match ($task->status) {
                    'waiting_assignment' => 'Atama Bekliyor',
                    'pending' => 'Bekliyor',
                    'in_progress' => 'Devam Ediyor',
                    'completed' => 'Tamamlandı',
                    'cancelled' => 'İptal',
                    default => $task->status,
                };

                // -- Tip Türkçeleştirme --
                $tip = match ($task->assignment_type) {
                    'general' => 'Genel (Araçsız)',
                    'vehicle' => 'Araç Görevi',
                    default => $task->assignment_type
                };

                return [
                    $task->id,
                    $tip, // Yeni eklediğimiz sütun
                    $task->title,
                    $task->vehicle ? $task->vehicle->plate_number : '-', // Araçsızsa tire koy
                    $sorumlu,
                    $task->createdBy ? $task->createdBy->name : '-',
                    $task->start_time ? Carbon::parse($task->start_time)->format('d.m.Y H:i') : '-',
                    $task->end_time ? Carbon::parse($task->end_time)->format('d.m.Y H:i') : '-',
                    $durum,
                    $task->fuel_cost ?? '0'
                ];
            }
        );
    }
    private function exportListLogic($request)
    {
        $filterType = $request->query('type');
        $fileName = 'gorev-listesi-' . date('d.m.Y') . '.csv';

        $query = VehicleAssignment::with(['vehicle', 'responsible'])->latest();
        if ($filterType)
            $query->where('assignment_type', $filterType);

        return CsvExporter::streamDownload($query, [
            'ID',
            'Başlık',
            'Plaka',
            'Sorumlu',
            'Durum'
        ], function ($task) {
            return [
                $task->id,
                $task->title,
                $task->vehicle->plate_number ?? '-',
                $task->responsible->name ?? '-',
                $task->status
            ];
        }, $fileName);
    }
    /**
     * 2. TEKİL GÖREV EMRİ (YENİ)
     * Şoföre verilecek kağıt çıktı
     */
    public function exportDetail(VehicleAssignment $assignment)
    {
        $fileName = 'gorev-emri-' . $assignment->id . '.csv';

        $callback = function () use ($assignment) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ARAÇ/GÖREV EMRİ'], ';');
            fputcsv($file, [], ';');

            fputcsv($file, ['Görev ID', $assignment->id], ';');
            fputcsv($file, ['Görev Başlığı', $assignment->title], ';');
            fputcsv($file, ['Atanan Araç', $assignment->vehicle->plate_number ?? 'Araçsız'], ';');
            fputcsv($file, ['Sorumlu', $assignment->responsible->name ?? '-'], ';');
            fputcsv($file, ['Çıkış KM', $assignment->start_km ?? '-'], ';');
            fputcsv($file, ['Dönüş KM', $assignment->end_km ?? '-'], ';');

            fputcsv($file, [], ';');
            fputcsv($file, ['Açıklama', $assignment->task_description], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName"
        ]);
    }
}
