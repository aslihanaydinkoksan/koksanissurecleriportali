<?php

namespace App\Http\Controllers;

use App\Models\VehicleAssignment;
use App\Models\Vehicle;
use App\Models\ServiceSchedule;
use App\Models\User;
use App\Models\Team;
use App\Notifications\VehicleAssignmentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
        $query->whereIn('responsible_type', [
            User::class,
            Team::class
        ]);

        // --- FİLTRELEME ---
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->input('vehicle_id'));
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

        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where('end_time', '>=', $dateFrom);
            } catch (\Exception $e) { /* Hatalı tarihi yoksay */
            }
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                $query->where('start_time', '<=', $dateTo);
            } catch (\Exception $e) { /* Hatalı tarihi yoksay */
            }
        }
        // --- FİLTRELEME SONU ---

        $assignments = $query->orderBy('start_time', 'desc')->paginate(15);
        $filters = $request->only([
            'vehicle_id',
            'assignment_type',
            'status',
            'search',
            'date_from',
            'date_to'
        ]);

        $vehicles = Vehicle::active()->orderBy('plate_number')->get();

        return view('service.assignments.index', compact('assignments', 'filters', 'vehicles'));
    }

    /**
     * Yeni araç atama formunu gösterir.
     */
    public function create(): View|RedirectResponse
    {
        // Aktif araçları al
        $vehicles = Vehicle::active()->orderBy('plate_number')->get();

        // Kullanıcıları ve Takımları al
        $users = User::orderBy('name')->get();
        $teams = Team::active()->with('users')->orderBy('name')->get();

        return view('service.assignments.create', compact('vehicles', 'users', 'teams'));
    }

    /**
     * Yeni araç atamasını veritabanında saklar.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. DİNAMİK VALİDASYON
        $validatedData = $request->validate([
            // Temel Alanlar
            'needs_vehicle' => 'required|in:yes,no',
            //'vehicle_id' => 'required_if:needs_vehicle,yes|in:company,logistics',
            'vehicle_id' => 'required_if:needs_vehicle,yes|exists:vehicles,id',

            // Sorumlu Bilgisi
            'responsible_type' => 'required|in:user,team',
            'responsible_user_id' => 'required_if:responsible_type,user|exists:users,id',
            'responsible_team_id' => 'required_if:responsible_type,team|exists:teams,id',

            // Görev Detayları
            'title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'destination' => 'nullable|string|max:255',
            'requester_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',

            // Nakliye Özel Alanları
            'initial_km' => 'nullable|required_if:vehicle_type,logistics|numeric|min:0',
            'initial_fuel' => 'nullable|required_if:vehicle_type,logistics|numeric|min:0',
        ], [
            // Özel Hata Mesajları
            'needs_vehicle.required' => 'Araç gerekliliği seçmelisiniz.',
            'vehicle_id.required_if' => 'Lütfen bir araç seçin.',
            'responsible_user_id.required_if' => 'Lütfen sorumlu kişiyi seçin.',
            'responsible_team_id.required_if' => 'Lütfen sorumlu takımı seçin.',
            'title.required' => 'Görev başlığı zorunludur.',
            'initial_km.required_if' => 'Nakliye görevi için başlangıç KM zorunludur.',
            'initial_fuel.required_if' => 'Nakliye görevi için yakıt miktarı zorunludur.',
        ]);

        // 2. Görev Tipini Belirle
        $assignmentType = $validatedData['needs_vehicle'] === 'yes' ? 'vehicle' : 'general';

        // 3. Görev Nesnesini Oluştur
        $assignment = new VehicleAssignment();
        $assignment->assignment_type = $assignmentType;
        $assignment->title = $validatedData['title'];
        $assignment->task_description = $validatedData['task_description'];
        $assignment->destination = $validatedData['destination'] ?? null;
        $assignment->requester_name = $validatedData['requester_name'] ?? null;
        $assignment->notes = $validatedData['notes'] ?? null;
        $assignment->status = 'pending';
        $assignment->user_id = auth()->id();

        // 4. Sorumluyu Ata (Polymorphic)
        if ($validatedData['responsible_type'] === 'user') {
            $assignment->responsible_type = User::class;
            $assignment->responsible_id = $validatedData['responsible_user_id'];
        } else {
            $assignment->responsible_type = Team::class;
            $assignment->responsible_id = $validatedData['responsible_team_id'];
        }

        // 5. Araçlı Görevler için İşlemler
        if ($assignmentType === 'vehicle') {
            $assignment->vehicle_id = $validatedData['vehicle_id'];

            // Sefer zamanını bul
            $targetDepartureTime = $this->findNextDeparture();
            if (!$targetDepartureTime) {
                return back()->withInput()->withErrors([
                    'vehicle_id' => 'Sistemde tanımlı aktif bir sefer saati bulunamadı.'
                ]);
            }

            $assignment->start_time = $targetDepartureTime;
            $assignment->end_time = $targetDepartureTime->copy()->addHour();

            // Nakliye özel alanları
            if ($validatedData['vehicle_id'] === 'logistics') {
                $assignment->initial_km = $validatedData['initial_km'];
                $assignment->initial_fuel = $validatedData['initial_fuel'];
            }

            $successMessage = 'Görev başarıyla oluşturuldu (' .
                $targetDepartureTime->translatedFormat('d M H:i') . ' seferine eklendi).';
        } else {
            // Genel görevler için
            $assignment->start_time = now();
            $assignment->end_time = now()->addDay(); // 1 gün varsayılan süre
            $successMessage = 'Görev başarıyla oluşturuldu.';
        }

        // 6. Kaydet
        $assignment->save();
        $recipients = collect();

        if ($assignmentType === 'individual') {
            // Bireysel atama: Sadece sorumlu kullanıcıya gönder
            $responsibleUser = User::find($validatedData['responsible_user_id']);
            if ($responsibleUser) {
                $recipients->push($responsibleUser);
            }
        } elseif ($assignmentType === 'team') {
            // Takım ataması: Takımdaki tüm üyelere gönder
            $team = Team::with('users')->find($validatedData['team_id']);
            if ($team) {
                $recipients = $recipients->merge($team->users);
            }
        }

        // Bildirimi alıcılara gönder
        foreach ($recipients->unique() as $recipient) {
            $recipient->notify(new VehicleAssignmentCreated($assignment));
        }

        return redirect()->route('service.assignments.index')
            ->with('success', $successMessage);
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
            // Hata veren kısmı MorphTo ile koşullu yüklüyoruz:
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

        $vehicles = Vehicle::active()->orderBy('plate_number')->get();
        $users = User::orderBy('name')->get();
        $teams = Team::active()->with('users')->orderBy('name')->get();

        return view('service.assignments.edit', compact(
            'assignment',
            'vehicles',
            'users',
            'teams'
        ));
    }

    /**
     * Güncelleme
     */
    public function update(Request $request, VehicleAssignment $assignment): RedirectResponse
    {
        $this->authorize('manage-assignment', $assignment);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'destination' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string',

            // Nakliye tamamlama alanları
            'final_km' => 'nullable|numeric|min:0',
            'final_fuel' => 'nullable|numeric|min:0',
            'fuel_cost' => 'nullable|numeric|min:0',
        ]);

        $assignment->update($validatedData);

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

        // 1. ESKİ DURUMU KAYDET
        $oldStatus = $assignment->status;
        $newStatus = $validatedData['status'];

        $assignment->status = $newStatus;

        // 2. TAMAMLANDIYSA İŞLEM YAP
        if ($newStatus === 'completed') {
            // Tamamlama zamanını ayarla
            if (!$assignment->end_time) {
                $assignment->end_time = now();
            }

            // Görev tamamlandığı için bildirimleri SİL (Rozeti kaldır)
            $this->deleteAssignmentNotifications($assignment);

            // 3. TAMAMLANMIŞTAN GERİ DÖNÜYORSA İŞLEM YAP
        } elseif ($oldStatus === 'completed' && in_array($newStatus, ['pending', 'in_progress'])) {
            // Tamamlanmış bir görev tekrar aktif bir duruma alındı.
            // Bildirimi tekrar OLUŞTUR ve alıcılara gönder (Rozeti geri getir)

            // Sorumluları bul
            $recipients = collect();
            if ($assignment->responsible_type === User::class && $assignment->responsible) {
                $recipients->push($assignment->responsible);
            } elseif ($assignment->responsible_type === Team::class && $assignment->responsible) {
                $assignment->responsible->loadMissing('users');
                $recipients = $recipients->merge($assignment->responsible->users);
            }

            // Bildirimi gönder (Notification Created sınıfınız kullanılacak)
            foreach ($recipients->unique() as $recipient) {
                $recipient->notify(new VehicleAssignmentCreated($assignment));
            }

            // Bitiş zamanını temizle (istenirse)
            $assignment->end_time = null;
        }

        // Not: Görev 'cancelled' (iptal) edilirse de bildirimlerin silinmesi mantıklı olabilir.
        if ($newStatus === 'cancelled') {
            $this->deleteAssignmentNotifications($assignment);
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
    private function deleteAssignmentNotifications(VehicleAssignment $assignment): void
    {
        // Bildirimlerin alıcıları (Sorumlu Kullanıcı ve Takım Üyeleri)
        $recipients = collect();

        // 1. Bireysel Sorumluluk
        if ($assignment->responsible_type === User::class) {
            $recipients->push($assignment->responsible);
        }
        // 2. Takım Sorumluluğu
        elseif ($assignment->responsible_type === Team::class && $assignment->responsible) {
            // Team üyelerini yükle
            $assignment->responsible->loadMissing('users');
            $recipients = $recipients->merge($assignment->responsible->users);
        }

        // Alıcıları döngüye al
        foreach ($recipients->unique() as $recipient) {
            // Kullanıcının, bu assignment_id'yi içeren okunmamış bildirimlerini sil
            $recipient->unreadNotifications()
                ->where('data', 'like', '%"assignment_id":' . $assignment->id . '%') // Bildirim verisi içinde assignment_id'yi ara
                ->markAsRead();

            // Not: Eğer bildirimler okunduktan sonra da kalıyorsa, 'notifications()' yerine 
            // 'notifications()' kullanıp tüm bildirimleri silmeyi düşünebilirsiniz.
        }
    }
}