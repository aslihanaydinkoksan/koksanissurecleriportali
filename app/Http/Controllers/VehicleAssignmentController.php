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

class VehicleAssignmentController extends Controller
{
    /**
     * Araç atamalarını listeler ve filtreler.
     */
    public function index(Request $request): View
    {
        $query = VehicleAssignment::with(['vehicle', 'createdBy', 'responsible'])
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
        $query = VehicleAssignment::with(['createdBy', 'responsible'])
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
            'end_time' => 'required|date|after:start_time',
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
        $assignment->end_time = Carbon::parse($validatedData['end_time']);

        $assignment->save();

        // --- 4. BİLDİRİM MANTIĞI (DÜZELTİLDİ) ---

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

                // Debug için log atalım (storage/logs/laravel.log dosyasına yazar)
                Log::info('Genel Görev Atama Başladı', [
                    'Sorumlu Tipi' => $validatedData['responsible_type'],
                    'Sorumlu ID' => $validatedData['responsible_type'] === 'user' ? $validatedData['responsible_user_id'] : $validatedData['responsible_team_id']
                ]);

                // Eğer "Kullanıcı" seçildiyse
                if ($validatedData['responsible_type'] === 'user') {
                    // ID'yi integer'a çevirerek arayalım
                    $userId = (int) $validatedData['responsible_user_id'];
                    $user = User::find($userId);

                    if ($user) {
                        Log::info('Kullanıcı bulundu:', ['isim' => $user->name, 'id' => $user->id]);

                        // Kendine görev atadıysa bildirim gitmesin kontrolü
                        if ($user->id !== auth()->id()) {
                            $assigneeRecipients->push($user);
                        } else {
                            Log::warning('Kullanıcı kendine görev atadığı için bildirim gönderilmedi.');
                        }
                    } else {
                        Log::error('Atanacak kullanıcı veritabanında bulunamadı ID: ' . $userId);
                    }
                }
                // Eğer "Takım" seçildiyse
                elseif ($validatedData['responsible_type'] === 'team') {
                    $teamId = (int) $validatedData['responsible_team_id'];
                    $team = Team::with('users')->find($teamId);

                    if ($team) {
                        Log::info('Takım bulundu:', ['takim' => $team->name, 'uye_sayisi' => $team->users->count()]);

                        // Takımdaki herkesi al, atayan kişiyi hariç tut
                        $assigneeRecipients = $team->users->filter(fn($u) => $u->id !== auth()->id());
                    } else {
                        Log::error('Atanacak takım bulunamadı ID: ' . $teamId);
                    }
                }

                // Bildirimi Gönder
                if ($assigneeRecipients->isNotEmpty()) {
                    Log::info('Bildirim gönderiliyor. Alıcı sayısı: ' . $assigneeRecipients->count());

                    // TaskAssignedNotification sınıfının doğru çalıştığından emin olalım
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

        $redirectRoute = 'home'; // Hepsini ana sayfaya yönlendir
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
     * Güncelleme
     */
    public function update(Request $request, VehicleAssignment $assignment)
    {
        $vehicleTypeInput = $request->input('vehicle_type');

        // 1. Validasyon Kuralları
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'status' => 'required|in:waiting_assignment,pending,in_progress,completed,cancelled',
            // Aşağıdaki alanlar nullable (boş olabilir) ama kurallarda olmalı
            'destination' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string',
            // Araç ve Yakıt Bilgileri
            'vehicle_id' => 'nullable',
            'start_km' => 'nullable|numeric|min:0',
            'final_km' => [
                'nullable',
                // Eğer durum completed ise ve araçlı görevse zorunlu olsun (İsteğe bağlı kural)
                // Rule::requiredIf($assignment->assignment_type === 'vehicle' && $request->input('status') === 'completed'),
                'numeric',
                'gte:start_km' // Başlangıçtan büyük veya eşit olmalı
            ],
            'fuel_cost' => 'nullable|numeric|min:0',
            'start_fuel_level' => 'nullable|string',
            'final_fuel' => 'nullable|string',
        ]);

        // 2. ESKİ DURUMU SAKLA (Bildirim için)
        $oldStatus = $assignment->status;

        // 3. GÜNCELLEME İŞLEMİ

        // Temel Bilgiler (Formdan gelmezse eski veriyi koru)
        $assignment->title = $request->input('title', $assignment->title);
        $assignment->task_description = $request->input('task_description', $assignment->task_description);
        $assignment->status = $validatedData['status'];

        // Opsiyonel Alanlar (?? null kullanarak hata almayı engelliyoruz)
        // Not: Eğer input disabled ise $request->input null dönebilir, bu durumda eski veriyi korumak daha güvenlidir.
        // Ancak bu alanlar düzenlenebilir olduğu için direkt alıyoruz.
        if ($request->has('destination')) {
            $assignment->destination = $validatedData['destination'];
        }

        if ($request->has('customer_id')) {
            $assignment->customer_id = $validatedData['customer_id'];
        }

        if ($request->has('notes')) {
            $assignment->notes = $validatedData['notes'];
        }

        // 4. ARAÇ ve LOJİSTİK BİLGİLERİ
        // Eğer araç seçimi yapıldıysa güncelle
        if ($request->has('vehicle_id')) {
            $assignment->vehicle_id = $validatedData['vehicle_id'];

            // Araç tipini güncelle (Eğer input varsa)
            if ($vehicleTypeInput) {
                if ($vehicleTypeInput === 'logistics') {
                    $assignment->vehicle_type = \App\Models\LogisticsVehicle::class;
                } else {
                    $assignment->vehicle_type = \App\Models\Vehicle::class;
                }
            }
        }

        // KM ve Yakıt Bilgileri
        $assignment->start_km = $request->input('start_km', $assignment->start_km);
        $assignment->end_km = $request->input('final_km', $assignment->end_km);
        $assignment->start_fuel_level = $request->input('start_fuel_level', $assignment->start_fuel_level);
        $assignment->end_fuel_level = $request->input('final_fuel', $assignment->end_fuel_level); // Blade'de name="final_fuel"
        $assignment->fuel_cost = $request->input('fuel_cost', $assignment->fuel_cost);

        $assignment->save();

        // 5. BİLDİRİM MANTIĞI (DEBUG EKLENDİ)
        if ($assignment->status !== $oldStatus) {

            // Log'a yazalım: Ne oluyor?
            Log::info('Durum Değişikliği Algılandı', [
                'Görev ID' => $assignment->id,
                'Eski Durum' => $oldStatus,
                'Yeni Durum' => $assignment->status,
                'Oluşturan ID (created_by)' => $assignment->assigned_by,
                'Güncelleyen ID (auth)' => auth()->id()
            ]);

            try {
                $creator = User::find($assignment->assigned_by);

                if (!$creator) {
                    Log::error('HATA: Görevi oluşturan kullanıcı veritabanında bulunamadı (created_by ID yok veya silinmiş).');
                } elseif ($creator->id === auth()->id()) {
                    Log::warning('UYARI: Kullanıcı kendi oluşturduğu görevi güncellediği için bildirim GÖNDERİLMEDİ.');

                    // TEST İÇİN: Eğer testi tek kişi yapıyorsan aşağıdaki satırı yoruma al, bu bloğu pasif et.
                    // Notification::send($creator, new \App\Notifications\TaskStatusUpdatedNotification($assignment, $oldStatus));
                } else {
                    Notification::send($creator, new \App\Notifications\TaskStatusUpdatedNotification($assignment, $oldStatus));
                    Log::info('BAŞARILI: Bildirim görevi oluşturan kişiye (' . $creator->name . ') gönderildi.');
                }

            } catch (\Exception $e) {
                Log::error('Bildirim Exception: ' . $e->getMessage());
            }
        }

        return redirect()->route('service.general-tasks.index')
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

    /**
     * CSV OLARAK DIŞA AKTAR 
     */
    public function export()
    {
        $fileName = 'arac-gorevleri-' . date('d-m-Y') . '.csv';

        // Verileri Çek
        $assignments = VehicleAssignment::with(['vehicle', 'createdBy', 'responsibleUser'])->latest()->get();

        $headers = [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($assignments) {
            $file = fopen('php://output', 'w');

            // Türkçe karakter sorunu olmasın diye BOM (Byte Order Mark) ekliyoruz
            fputs($file, "\xEF\xBB\xBF");

            // 1. Satır: Başlıklar (Noktalı virgül kullanıyoruz ki Excel sütunları tanısın)
            fputcsv($file, [
                'ID',
                'Görev Başlığı',
                'Plaka',
                'Sorumlu',
                'Görevi Atayan',
                'Başlangıç',
                'Bitiş',
                'Durum',
                'Yakıt (TL)'
            ], ';');

            // 2. Satır ve sonrası: Veriler
            foreach ($assignments as $task) {
                // Sorumlu adını bul
                $sorumlu = 'Bilinmiyor';
                if ($task->responsible_type === 'App\Models\User' && $task->responsibleUser) {
                    $sorumlu = $task->responsibleUser->name;
                } elseif ($task->responsible_type === 'App\Models\Team') {
                    $sorumlu = 'Takım ID: ' . $task->responsible_id;
                }

                // Durumu Türkçeleştir
                $durum = match ($task->status) {
                    'pending' => 'Bekliyor',
                    'in_progress' => 'Devam Ediyor',
                    'completed' => 'Tamamlandı',
                    'cancelled' => 'İptal',
                    default => $task->status,
                };

                fputcsv($file, [
                    $task->id,
                    $task->title,
                    $task->vehicle ? $task->vehicle->plate_number : 'Araçsız',
                    $sorumlu,
                    $task->createdBy ? $task->createdBy->name : '-',
                    \Carbon\Carbon::parse($task->start_time)->format('d.m.Y H:i'),
                    \Carbon\Carbon::parse($task->end_time)->format('d.m.Y H:i'),
                    $durum,
                    $task->fuel_cost ?? '0'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}