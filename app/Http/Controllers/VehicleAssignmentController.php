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

class VehicleAssignmentController extends Controller
{
    /**
     * Araç atamalarını listeler ve filtreler.
     */
    public function index(Request $request): View
    {
        $query = VehicleAssignment::with([
            'vehicle',
            'createdBy',
            'responsible' // Polymorphic ilişki
        ]);

        // ESKİ KOD: $query->whereNotNull('vehicle_id');
        // YENİ MANTIK: Araç ID'si dolu olanlar VEYA durumu 'waiting_assignment' olanlar listelensin.
        $query->where(function ($q) {
            $q->whereNotNull('vehicle_id')
                ->orWhere('status', 'waiting_assignment');
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
        // --- FİLTRELEME SONU ---

        // Bekleyen atamaları en üste, diğerlerini tarihe göre sırala
        $assignments = $query->orderByRaw("CASE WHEN status = 'waiting_assignment' THEN 0 ELSE 1 END")
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        $filters = $request->only(['vehicle_id', 'assignment_type', 'status', 'search', 'date_from', 'date_to']);

        // Filtreleme dropdownları için araç listesi
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
        $query = VehicleAssignment::with(['createdBy', 'responsible']);
        $query->whereNull('vehicle_id')
            ->where('status', '<>', 'waiting_assignment'); // Araç bekleyenler buraya düşmesin

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
     * Yeni araç talebini (veya genel görevi) kaydeder.
     * Kullanıcı araç seçmez, sadece tip seçer.
     */
    public function store(Request $request): RedirectResponse
    {
        $vehicleTypeInput = $request->input('vehicle_type');

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
            'end_time' => 'required|date|after:start_time',
        ]);

        $assignmentType = $validatedData['needs_vehicle'] === 'yes' ? 'vehicle' : 'general';

        $assignment = new \App\Models\VehicleAssignment();
        $assignment->assignment_type = $assignmentType;
        $assignment->title = $validatedData['title'];
        $assignment->task_description = $validatedData['task_description'];
        $assignment->destination = $validatedData['destination'] ?? null;
        $assignment->requester_name = Auth::user()->name;
        $assignment->notes = $validatedData['notes'] ?? null;
        $assignment->user_id = auth()->id(); // Oluşturan kişi (created_by)
        $assignment->assigned_by = auth()->id(); // İkisi de dolu olsun garanti olsun
        $assignment->customer_id = $request->input('customer_id');

        // Sorumlu Ata
        if ($validatedData['responsible_type'] === 'user') {
            $assignment->responsible_type = User::class;
            $assignment->responsible_id = $validatedData['responsible_user_id'];
        } else {
            $assignment->responsible_type = \App\Models\Team::class;
            $assignment->responsible_id = $validatedData['responsible_team_id'];
        }

        // --- ARAÇLI GÖREV MANTIĞI ---
        if ($assignmentType === 'vehicle') {
            // DİKKAT: Dashboard'un görmesi için status 'pending' olmalı!
            $assignment->status = 'pending';
            $assignment->vehicle_id = null;

            // Model sınıfını kaydet
            if ($vehicleTypeInput === 'logistics') {
                $assignment->vehicle_type = \App\Models\LogisticsVehicle::class;
            } else {
                $assignment->vehicle_type = \App\Models\Vehicle::class;
            }

            $assignment->start_time = Carbon::parse($validatedData['start_time']);
            $assignment->end_time = Carbon::parse($validatedData['end_time']);

            $successMessage = 'Araç talebiniz başarıyla oluşturuldu ve Ulaştırma birimine iletildi.';
        } else {
            // Genel görevler (Araçsız)
            $assignment->status = 'pending';
            $assignment->start_time = now();
            $assignment->end_time = now()->addDay();
            $successMessage = 'Genel görev başarıyla oluşturuldu.';
        }

        $assignment->save();

        // --- BİLDİRİM GÖNDERME (SADECE ARAÇ TALEBİ İSE) ---
        if ($assignmentType === 'vehicle') {
            try {
                // 1. Bildirimi alacakları bul: (Adminler + Ulaştırma Müdürleri)
                $recipients = User::where(function ($query) {
                    $query->where('role', 'admin') // Adminler her zaman görsün
                        ->orWhere(function ($q) {
                            // Rolü 'müdür' veya 'yönetici' olup departmanı 'ulastirma' olanlar
                            $q->whereIn('role', ['müdür', 'yönetici', 'mudur'])
                                ->whereHas('department', function ($d) {
                                $d->where('slug', 'ulastirma');
                            });
                        });
                })->get();

                // 2. Yeni bildirimi gönder
                if ($recipients->count() > 0) {
                    // NewRequestForManager sınıfını kullandık (Simge ve renk ayarlı olan)
                    Notification::send($recipients, new NewRequestForManager($assignment));
                }
            } catch (\Exception $e) {
                Log::error('Bildirim hatası: ' . $e->getMessage());
            }
        }

        // (Opsiyonel) Eğer genel görevse sorumlu kişiye bildirim gönderme kodu buraya eklenebilir.

        $redirectRoute = ($assignmentType === 'vehicle') ? 'home' : 'home'; // İstersen ilgili listeye yönlendir
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
    /**
     * Oturum açmış kullanıcıya atanmış görevleri listeler.
     */
    public function myAssignments(): View
    {
        $user = Auth::user();

        // Kullanıcının üye olduğu takımların ID'leri
        $teamIds = $user->teams()->pluck('teams.id');

        $assignments = VehicleAssignment::with([
            'vehicle',
            'createdBy',
            'responsible' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                        // Eğer responsible bir Team ise, Team'in 'users' ilişkisini yükle
                    Team::class => ['users'],
                        // Eğer responsible bir User ise, ek ilişki yükleme
                    User::class => [],
                ]);
            }
        ])
            ->where(function ($query) use ($user, $teamIds) {
                // 1. Kural: Kullanıcıya bireysel atanmış görevler
                $query->where(function ($q) use ($user) {
                    $q->where('responsible_type', User::class)
                        ->where('responsible_id', $user->id);
                })
                    // 2. Kural: Kullanıcının üyesi olduğu takımlara atanmış görevler
                    ->orWhere(function ($q) use ($teamIds) {
                    $q->where('responsible_type', Team::class)
                        ->whereIn('responsible_id', $teamIds);
                });
            })
            ->latest('start_time')
            ->paginate(15);

        return view('service.assignments.my_assignments', compact('assignments'));
    }
    public function show(VehicleAssignment $assignment): View
    {
        // Gerekli ilişkileri yükleyelim
        $assignment->load(['vehicle', 'createdBy', 'responsible']);

        // Yetki kontrolü eklemek isteyebilirsiniz (Örn: sadece atanana veya admin'e göster)
        // if (Gate::denies('view-assignment', $assignment)) { /* ... */ }

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
     * Güncelleme
     */
    public function update(Request $request, VehicleAssignment $assignment): RedirectResponse
    {
        $this->authorize('manage-assignment', $assignment);
        $needsVehicle = $assignment->requiresVehicle() ? 'yes' : 'no';
        // Formdan gelen araç tipi (company veya logistics)
        $vehicleTypeInput = $request->input('vehicle_type');
        // Eğer formdan gelmezse mevcut olandan türet
        if (!$vehicleTypeInput) {
            $vehicleTypeInput = $assignment->isLogistics() ? 'logistics' : 'company';
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'destination' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'vehicle_type' => 'nullable|in:company,logistics',
            'vehicle_id' => [
                'nullable',
                // Eğer araç gerekli ise zorunlu
                Rule::requiredIf($request->has('vehicle_id')),
                function ($attribute, $value, $fail) use ($vehicleTypeInput) {
                    if (!$value)
                        return; // Boşsa geç
        
                    if ($vehicleTypeInput === 'company') {
                        if (!Vehicle::where('id', $value)->exists()) {
                            $fail('Seçilen şirket aracı sistemde bulunamadı.');
                        }
                    } elseif ($vehicleTypeInput === 'logistics') {
                        if (!LogisticsVehicle::where('id', $value)->exists()) {
                            $fail('Seçilen nakliye aracı sistemde bulunamadı.');
                        }
                    }
                },
            ],

            // Nakliye tamamlama alanları
            'final_km' => [
                'nullable',
                Rule::requiredIf($assignment->isLogistics() && $request->input('status') === 'completed'),
                'numeric',
                'min:0'
            ],
            'final_fuel' => [
                'nullable',
                Rule::requiredIf($assignment->isLogistics() && $request->input('status') === 'completed'),
                'string'
            ],
            'fuel_cost' => [
                'nullable',
                Rule::requiredIf($assignment->isLogistics() && $request->input('status') === 'completed'),
                'numeric',
                'min:0'
            ],
        ]);

        // --- VERİ GÜNCELLEME ---
        $assignment->title = $validatedData['title'];
        $assignment->task_description = $validatedData['task_description'];
        $assignment->destination = $validatedData['destination'];
        $assignment->customer_id = $request->input('customer_id');
        $assignment->status = $validatedData['status'];
        $assignment->notes = $validatedData['notes'];

        // Araç Değişikliği Yapıldıysa Türünü de Güncelle
        if ($request->filled('vehicle_id')) {
            $assignment->vehicle_id = $validatedData['vehicle_id'];

            if ($vehicleTypeInput === 'logistics') {
                $assignment->vehicle_type = LogisticsVehicle::class;
            } else {
                $assignment->vehicle_type = Vehicle::class;
            }
        }

        // Nakliye bitiş verileri
        if ($request->filled('final_km'))
            $assignment->end_km = $validatedData['final_km'];
        if ($request->filled('final_fuel'))
            $assignment->end_fuel_level = $validatedData['final_fuel'];
        if ($request->filled('fuel_cost'))
            $assignment->fuel_cost = $validatedData['fuel_cost'];

        // Görev tamamlandıysa bitiş zamanı ata
        if ($assignment->status === 'completed' && !$assignment->end_time) {
            $assignment->end_time = now();
        }

        $assignment->update();

        return redirect()->route('service.assignments.index')
            ->with('success', 'Görev başarıyla güncellendi.');
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
     * Giriş yapan kullanıcının sorumlu olduğu görevleri listeler.
     */
    public function myTasks(): View
    {
        $user = Auth::user();

        // Kullanıcının üye olduğu takımların ID'leri
        $teamIds = $user->teams()->pluck('teams.id');

        // Görevleri çek
        $tasks = VehicleAssignment::with(['vehicle', 'createdBy'])
            ->where(function ($query) use ($user, $teamIds) {
                // Doğrudan kullanıcıya atanan görevler
                $query->where(function ($q) use ($user) {
                    $q->where('responsible_type', User::class)
                        ->where('responsible_id', $user->id);
                })
                    // VEYA Kullanıcının takımlarına atanan görevler
                    ->orWhere(function ($q) use ($teamIds) {
                    $q->where('responsible_type', Team::class)
                        ->whereIn('responsible_id', $teamIds);
                });
            })
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return view('service.assignments.my_tasks', compact('tasks'));
    }
    /**
     * Görev durumunu günceller (AJAX)
     */
    public function updateStatus(Request $request, VehicleAssignment $assignment)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $oldStatus = $assignment->status;
        $newStatus = $validatedData['status'];
        $assignment->status = $newStatus;

        // SENARYO 1: Görev Bitti veya İptal Edildi
        // Bildirimleri temizle ve bitiş zamanını kaydet
        if (in_array($newStatus, ['completed', 'cancelled'])) {
            $this->deleteAssignmentNotifications($assignment);

            if ($newStatus === 'completed' && !$assignment->end_time) {
                $assignment->end_time = now();
            }
        }

        // SENARYO 2: Görev Tamamlandı'dan Geri Döndü (Aktifleşti)
        // Eskileri temizle, Bitiş zamanını sil ve YENİ bildirim oluştur
        elseif ($oldStatus === 'completed' && in_array($newStatus, ['pending', 'in_progress'])) {
            $this->deleteAssignmentNotifications($assignment); // Temizlik
            $assignment->end_time = null;

            // Bildirimi zorla oluştur ve okunmamış yap
            $this->forceNotificationUnread($assignment);
        }

        // SENARYO 3: Aktif Durumlar Arası Geçiş (Örn: Beklemede -> Devam Ediyor)
        // Bildirim varsa okunmamış yap, yoksa yeni oluştur
        elseif (in_array($oldStatus, ['pending', 'in_progress']) && in_array($newStatus, ['pending', 'in_progress'])) {
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

        return back()->with('success', 'Görev durumu güncellendi.');
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
}