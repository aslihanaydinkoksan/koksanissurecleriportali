<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use App\Models\Travel;
use Illuminate\Support\Facades\Gate;
use App\Models\Department;
use App\Models\MaintenancePlan;
use Illuminate\Support\Collection;
use App\Services\StatisticsService;

class HomeController extends Controller
{
    protected $statsService;

    public function __construct(StatisticsService $statsService)
    {
        $this->middleware('auth');
        $this->statsService = $statsService;

    }

    /**
     * ANA TAKVİM SAYFASI (Kişisel)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Görünüm için departman bilgisi (Veri çekmek için değil, sadece başlık için)
        $departmentSlug = $user->department ? strtolower(trim($user->department->slug)) : 'genel';
        $departmentName = $user->department?->name ?? 'Genel';

        $allEvents = [];
        $statsTitle = "Takvimim";

        // NOT: Artık switch-case yerine YETKİ kontrolü yapıyoruz.
        // Bir kişi hem Lojistik hem Üretim yetkisine sahipse ikisini de görebilir.

        // 1. Lojistik Verileri
        if ($user->can('view_logistics')) {
            $data = $this->getLojistikIndexData($user);
            $allEvents = array_merge($allEvents, $data['events']);
        }

        // 2. Üretim Verileri
        if ($user->can('view_production')) {
            $data = $this->getUretimIndexData($user);
            $allEvents = array_merge($allEvents, $data['events']);
        }

        // 3. Bakım Verileri
        if ($user->can('view_maintenance')) {
            $data = $this->getBakimIndexData($user);
            $allEvents = array_merge($allEvents, $data['events']);
        }

        // 4. İdari İşler / Hizmet Verileri
        if ($user->can('view_administrative')) {
            $data = $this->getHizmetIndexData($user);
            $allEvents = array_merge($allEvents, $data['events']);
        }
        $todos = \App\Models\Todo::forUser($user) // Scope devrede!
            ->where('user_id', $user->id) // Sadece benimkiler
            ->whereNotNull('due_date')
            ->where('is_completed', false)
            ->get();

        foreach ($todos as $todo) {
            $color = match ($todo->priority) {
                'high' => '#dc3545', // Kırmızı
                'medium' => '#fd7e14', // Turuncu
                'low' => '#20c997', // Yeşil
                default => '#6c757d'
            };

            $allEvents[] = [
                'title' => '📝 ' . $todo->title,
                'start' => $todo->due_date->toIso8601String(),
                'color' => $color, // Görevler için farklı bir renk
                'allDay' => true, // Genelde saatlik olmaz, gün boyu olur
                'extendedProps' => [
                    'model_type' => 'todo',
                    'id' => $todo->id,
                    'is_important' => false,
                    'details' => ['Not' => $todo->description ?? 'Açıklama yok']
                ]
            ];
        }

        // Kullanıcı Listesi (Sadece Yöneticiler İçin)
        $users = collect();
        if ($user->hasRole(['admin', 'yonetici', 'mudur'])) {
            $users = User::with('department')->orderBy('name')->get();
        }
        $allEvents = collect($allEvents)->unique(function ($item) {
            return $item['extendedProps']['model_type'] . '-' . $item['extendedProps']['id'];
        })->values()->all();

        return view('home', [
            'users' => $users,
            'departmentName' => $departmentName,
            'departmentSlug' => $departmentSlug,
            'events' => $allEvents,
            'chartData' => [],
            'statsTitle' => $statsTitle
        ]);

    }

    /**
     * KARŞILAMA / DASHBOARD SAYFASI
     */
    public function welcome(Request $request)
    {
        $user = Auth::user();

        // Önemli Öğeler (Sağ Sidebar)
        $allItems = $this->getMappedImportantItems($request);
        $importantItems = $allItems->take(4);
        $importantItemsCount = $allItems->count();

        // Dashboard Tipini Belirle (Hangi grafikler gösterilecek?)
        $departmentSlug = $user->department ? trim($user->department->slug) : null;

        // Admin veya Yönetici ise, ve bir departman atanmamışsa "Genel/Admin" dashboard göster
        if ($user->hasRole(['admin', 'yonetici']) && !$departmentSlug) {
            $departmentSlug = 'admin';
        }

        // TV Kullanıcısı kontrolü
        if ($user->email === 'tv@koksan.com') {
            $departmentSlug = 'admin';
        }

        // Tarihler
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        // Varsayılan Değerler
        $welcomeTitle = "Hoş Geldiniz";
        $chartTitle = "Genel Bakış";
        $chartData = [];
        $kpiData = [];
        $todayItems = collect();
        $weeklyItems = collect();
        $monthlyItems = collect();

        // --- DASHBOARD VERİLERİNİ DOLDUR ---

        if ($departmentSlug === 'uretim') {
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->getProductionWelcomeData($user);
            $query = ProductionPlan::forUser($user); // Scope Eklendi
            $todayItems = (clone $query)->whereDate('week_start_date', $today)->get();
            $weeklyItems = (clone $query)->whereBetween('week_start_date', [$weekStart, $weekEnd])->get();
            $monthlyItems = (clone $query)->whereBetween('week_start_date', [$monthStart, $monthEnd])->get();

        } elseif ($departmentSlug === 'hizmet') {
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->getServiceWelcomeData($user);

            // Etkinlikler
            $eventQ = Event::forUser($user); // Scope Eklendi
            $tEvents = (clone $eventQ)->whereDate('start_datetime', $today)->get();
            $wEvents = (clone $eventQ)->whereBetween('start_datetime', [$weekStart, $weekEnd])->get();
            $mEvents = (clone $eventQ)->whereBetween('start_datetime', [$monthStart, $monthEnd])->get();

            // Araç Görevleri
            $vehicleQ = VehicleAssignment::forUser($user)->whereIn('status', ['pending', 'in_progress', 'approved']); // Scope Eklendi
            $tVehicle = (clone $vehicleQ)->whereDate('start_time', $today)->get();
            $wVehicle = (clone $vehicleQ)->whereBetween('start_time', [$weekStart, $weekEnd])->get();
            $mVehicle = (clone $vehicleQ)->whereBetween('start_time', [$monthStart, $monthEnd])->get();

            $todayItems = $tEvents->merge($tVehicle)->sortBy('start_datetime');
            $weeklyItems = $wEvents->merge($wVehicle)->sortBy('start_datetime');
            $monthlyItems = $mEvents->merge($mVehicle)->sortBy('start_datetime');

            // Hizmet KPI
            $kpiData = [
                'etkinlik_sayisi' => Event::forUser($user)->whereDate('start_datetime', '>=', $today)
                    ->where('visit_status', '!=', 'iptal')->count(),
                'musteri_ziyareti' => Event::forUser($user)->has('customerVisit')->count(),
                'rezervasyon_sayisi' => DB::table('bookings')->count(), // Booking modeline geçtiğinde scope ekle
                'toplam_arac' => \App\Models\Vehicle::count(), // Araçlar genelde globaldir
            ];

        } elseif ($departmentSlug === 'ulastirma') {
            // Ulaştırma KPI ve Verileri
            // Not: statsService metodlarını da güncellemek gerekebilir, şimdilik manuel çekiyoruz
            $welcomeTitle = "Ulaştırma Yönetimi";
            $chartTitle = "Araç Görev Durumları";

            $query = VehicleAssignment::forUser($user)->whereIn('status', ['pending', 'approved', 'in_progress']); // Scope Eklendi
            $todayItems = (clone $query)->whereDate('start_time', $today)->orderBy('start_time')->get();
            $weeklyItems = (clone $query)->whereBetween('start_time', [$weekStart, $weekEnd])->orderBy('start_time')->get();
            $monthlyItems = (clone $query)->whereBetween('start_time', [$monthStart, $monthEnd])->orderBy('start_time')->get();

            $kpiData = [
                'aktif_gorev' => VehicleAssignment::forUser($user)->where('status', 'in_progress')->count(),
                'bekleyen_talep' => VehicleAssignment::forUser($user)->where('status', 'pending')->count(),
                'toplam_arac' => \App\Models\Vehicle::count(),
                'bugunku_gorev' => $todayItems->count()
            ];

        } elseif ($departmentSlug === 'lojistik') {
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->getLogisticsWelcomeData($user);
            $query = Shipment::forUser($user); // Scope Eklendi
            $todayItems = (clone $query)->whereDate('tahmini_varis_tarihi', $today)->get();
            $weeklyItems = (clone $query)->whereBetween('tahmini_varis_tarihi', [$weekStart, $weekEnd])->get();
            $monthlyItems = (clone $query)->whereBetween('tahmini_varis_tarihi', [$monthStart, $monthEnd])->get();

        } elseif ($departmentSlug === 'bakim') {
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->getMaintenanceWelcomeData($user);
            $query = MaintenancePlan::forUser($user)->with('asset'); // Scope Eklendi
            $todayItems = (clone $query)->whereDate('planned_start_date', $today)->get();
            $weeklyItems = (clone $query)->whereBetween('planned_start_date', [$weekStart, $weekEnd])->get();
            $monthlyItems = (clone $query)->whereBetween('planned_start_date', [$monthStart, $monthEnd])->get();

        } else {
            // ADMIN / GENEL DASHBOARD
            // Admin bile olsa "Active Unit" ne ise onun verisini göstermeli
            $adminData = $this->getAdminDashboardData($user, $today, $weekStart, $weekEnd, $monthStart, $monthEnd);
            $welcomeTitle = $adminData['welcomeTitle'];
            $chartTitle = $adminData['chartTitle'];
            $todayItems = $adminData['todayItems'];
            $weeklyItems = $adminData['weeklyItems'];
            $monthlyItems = $adminData['monthlyItems'];
            $kpiData = $adminData['kpiData'];
            $chartData = $adminData['chartData'];
        }

        $chartType = 'sankey';

        return view('welcome', compact(
            'importantItems',
            'importantItemsCount',
            'welcomeTitle',
            'todayItems',
            'weeklyItems',
            'monthlyItems',
            'chartType',
            'chartData',
            'chartTitle',
            'departmentSlug',
            'kpiData'
        ));
    }

    /**
     * Takvim üzerinde "Önemli" işaretleme işlemi
     */
    public function toggleImportant(Request $request)
    {
        $user = Auth::user();

        // 1. GÜVENLİK: Spatie Rol Kontrolü (Eski in_array kaldırıldı)
        // Admin, Yönetici veya Müdür yetkisi olanlar yapabilsin
        if (!$user || !$user->hasRole(['admin', 'yonetici', 'mudur'])) {
            return response()->json(['success' => false, 'message' => 'Bu işlem için yetkiniz yok.'], 403);
        }

        // 2. VALIDATION
        $validated = $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'is_important' => 'required|boolean', // true/false/0/1/ "true" hepsini kabul eder
        ]);

        $modelId = $validated['model_id'];
        // Laravel helper ile boolean çevrimi
        $isImportant = $request->boolean('is_important');

        try {
            // Hangi Model?
            $modelClass = match ($validated['model_type']) {
                'shipment' => \App\Models\Shipment::class,
                'production_plan' => \App\Models\ProductionPlan::class,
                'event' => \App\Models\Event::class,
                'vehicle_assignment' => \App\Models\VehicleAssignment::class,
                'travel' => \App\Models\Travel::class,
                'maintenance_plan' => \App\Models\MaintenancePlan::class,
                default => null,
            };

            if (!$modelClass) {
                return response()->json(['success' => false, 'message' => 'Geçersiz veri türü.'], 400);
            }

            // 3. VERİ GÜVENLİĞİ (BUSINESS UNIT CHECK) 🔒
            // forUser($user) ekleyerek, kullanıcının sadece kendi fabrikasındaki veriyi
            // bulabilmesini sağlıyoruz. Başkasının ID'sini gönderirse null döner.
            $record = $modelClass::forUser($user)->find($modelId);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Kayıt bulunamadı veya yetkiniz yok.'], 404);
            }

            // 4. GÜNCELLEME İŞLEMİ
            if ($validated['model_type'] === 'maintenance_plan') {
                // Bakım planı için priority sütununu kullanıyoruz
                $record->priority = $isImportant ? 'critical' : 'normal';
            } else {
                // Diğerleri için is_important sütunu
                $record->is_important = $isImportant;
            }

            $record->save();

            return response()->json([
                'success' => true,
                'message' => 'Durum güncellendi.',
                'new_state' => $isImportant
            ]);

        } catch (\Exception $e) {
            Log::error('ToggleImportant Hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Sunucu hatası oluştu.'], 500);
        }
    }

    public function showAllImportant(Request $request)
    {
        $filters = $request->only(['type', 'department_id', 'date_from', 'date_to']);
        $departments = Department::orderBy('name')->get();
        $allItems = $this->getMappedImportantItems($request);
        return view('important-items', [
            'importantItems' => $allItems,
            'filters' => $filters,
            'departments' => $departments
        ]);
    }

    // --- TAKVİM ve DASHBOARD İÇİN YARDIMCI FONKSİYONLAR ---

    private function getLojistikIndexData($user)
    {
        $events = [];
        $now = Carbon::now();
        // Sadece kullanıcının aktif birimine ait sevkiyatlar
        $shipments = Shipment::forUser($user)->with('onaylayanKullanici')->get()->unique('id');

        foreach ($shipments as $shipment) {
            $cikisTarihi = $shipment->cikis_tarihi ? Carbon::parse($shipment->cikis_tarihi) : null;
            $varisTarihi = $shipment->tahmini_varis_tarihi ? Carbon::parse($shipment->tahmini_varis_tarihi) : null;

            $color = '#0d6efd';
            if ($shipment->onaylanma_tarihi)
                $color = '#198754';
            elseif ($varisTarihi) {
                if ($now->greaterThan($varisTarihi))
                    $color = '#dc3545';
                elseif ($varisTarihi->isBetween($now, $now->copy()->addDays(3)))
                    $color = '#ffc107';
            }

            $extendedProps = ['eventType' => 'shipment', 'model_type' => 'shipment', 'id' => $shipment->id, 'is_important' => $shipment->is_important, 'title' => '🚚 ' . $shipment->kargo_icerigi, 'details' => []];

            if ($cikisTarihi)
                $events[] = ['title' => 'ÇIKIŞ: ' . $shipment->kargo_icerigi, 'start' => $cikisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
            if ($varisTarihi)
                $events[] = ['title' => 'VARIŞ: ' . $shipment->kargo_icerigi, 'start' => $varisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
        }
        return ['events' => $events, 'chartData' => [], 'statsTitle' => "Sevkiyat Takvimi"];
    }

    private function getUretimIndexData($user)
    {
        $events = [];
        $plans = ProductionPlan::forUser($user)->with('user')->get()->unique('id'); // Scope

        foreach ($plans as $plan) {
            $events[] = [
                'title' => 'Üretim: ' . $plan->plan_title,
                'model_type' => 'production_plan',
                'is_important' => $plan->is_important,
                'start' => $plan->week_start_date->startOfDay()->toIso8601String(),
                'end' => $plan->week_start_date->copy()->addDay()->startOfDay()->toIso8601String(),
                'color' => '#4FD1C5',
                'extendedProps' => ['eventType' => 'production', 'id' => $plan->id, 'title' => $plan->plan_title, 'details' => ['Plan Detayları' => $plan->plan_details]]
            ];
        }
        return ['events' => $events, 'chartData' => [], 'statsTitle' => "Üretim Takvimi"];
    }

    private function getHizmetIndexData($user)
    {
        $events = [];
        $serviceEvents = Event::forUser($user)->with('user')->get()->unique('id'); // Scope
        foreach ($serviceEvents as $event) {
            $events[] = [
                'title' => 'Etkinlik: ' . $event->title,
                'start' => $event->start_datetime->format('Y-m-d\TH:i:s'),
                'end' => $event->end_datetime->format('Y-m-d\TH:i:s'),
                'color' => '#F093FB',
                'extendedProps' => ['eventType' => 'service_event', 'model_type' => 'event', 'is_important' => $event->is_important, 'id' => $event->id, 'details' => ['Konum' => $event->location]]
            ];
        }

        $assignments = VehicleAssignment::forUser($user)->with(['vehicle', 'createdBy'])->get(); // Scope
        foreach ($assignments as $assignment) {
            $events[] = [
                'title' => 'Araç: ' . ($assignment->vehicle?->plate_number ?? '?') . ' - ' . $assignment->task_description,
                'start' => $assignment->start_time->format('Y-m-d\TH:i:s'),
                'end' => $assignment->end_time->format('Y-m-d\TH:i:s'),
                'color' => '#FBD38D',
                'extendedProps' => ['eventType' => 'vehicle_assignment', 'model_type' => 'vehicle_assignment', 'is_important' => $assignment->is_important, 'id' => $assignment->id, 'details' => ['Görev' => $assignment->task_description]]
            ];
        }
        return ['events' => $events, 'chartData' => [], 'statsTitle' => "İdari İşler Takvimi"];
    }

    private function getBakimIndexData($user)
    {
        $events = [];
        $plans = MaintenancePlan::forUser($user)->with(['asset', 'type'])->get()->unique('id'); // Scope
        foreach ($plans as $plan) {
            $color = match ($plan->status) {
                'pending' => '#F6E05E', 'in_progress' => '#3182CE', 'completed' => '#48BB78', 'cancelled' => '#E53E3E', default => '#A0AEC0',
            };
            $events[] = [
                'title' => 'Bakım: ' . ($plan->asset->name ?? '?'),
                'start' => $plan->planned_start_date->format('Y-m-d\TH:i:s'),
                'end' => $plan->planned_end_date->format('Y-m-d\TH:i:s'),
                'color' => $color,
                'extendedProps' => [
                    'eventType' => 'maintenance',
                    'model_type' => 'maintenance_plan',
                    'is_important' => ($plan->priority == 'critical' || $plan->priority == 'high'),
                    'id' => $plan->id,
                    'details' => ['Varlık' => $plan->asset->name ?? '-', 'Durum' => $plan->status]
                ]
            ];
        }
        return ['events' => $events, 'chartData' => [], 'statsTitle' => "Bakım Takvimi"];
    }

    private function getMappedImportantItems(Request $request)
    {
        $user = Auth::user();
        $typeFilter = $request->input('type', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Departman filtresi (Admin panellerinden gelen istekler için)
        $deptFilter = $request->input('department_id', null);

        $allMappedItems = collect();

        // 1. SEVKİYAT
        if ($typeFilter == 'all' || $typeFilter == 'shipment') {
            $query = Shipment::forUser($user)->where('is_important', true); // Scope
            if ($dateFrom)
                $query->where('tahmini_varis_tarihi', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('tahmini_varis_tarihi', '<=', Carbon::parse($dateTo)->endOfDay());
            if ($deptFilter)
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Sevkiyat: ' . ($item->kargo_icerigi ?? 'Detay Yok'),
                'date' => $item->tahmini_varis_tarihi,
                'model_id' => $item->id,
                'model_type' => 'shipment'
            ]));
        }

        // 2. ÜRETİM PLANI
        if ($typeFilter == 'all' || $typeFilter == 'production_plan') {
            $query = ProductionPlan::forUser($user)->where('is_important', true); // Scope
            if ($dateFrom)
                $query->where('week_start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('week_start_date', '<=', Carbon::parse($dateTo)->endOfDay());
            if ($deptFilter)
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Üretim: ' . $item->plan_title,
                'date' => $item->week_start_date,
                'model_id' => $item->id,
                'model_type' => 'production_plan'
            ]));
        }

        // 3. BAKIM PLANI
        if ($typeFilter == 'all' || $typeFilter == 'maintenance_plan') {
            $query = MaintenancePlan::forUser($user)->whereIn('priority', ['high', 'critical']); // Scope
            if ($dateFrom)
                $query->where('planned_start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('planned_start_date', '<=', Carbon::parse($dateTo)->endOfDay());

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Bakım: ' . ($item->asset->name ?? 'Bilinmiyor') . ' (' . $item->title . ')',
                'date' => $item->planned_start_date,
                'model_id' => $item->id,
                'model_type' => 'maintenance_plan'
            ]));
        }

        // 4. ETKİNLİK
        if ($typeFilter == 'all' || $typeFilter == 'event') {
            $query = Event::forUser($user)->where('is_important', true); // Scope
            if ($dateFrom)
                $query->where('start_datetime', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_datetime', '<=', Carbon::parse($dateTo)->endOfDay());

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Etkinlik: ' . $item->title,
                'date' => $item->start_datetime,
                'model_id' => $item->id,
                'model_type' => 'event'
            ]));
        }

        // 5. ARAÇ GÖREVİ
        if ($typeFilter == 'all' || $typeFilter == 'vehicle_assignment') {
            $query = VehicleAssignment::forUser($user)->where('is_important', true); // Scope
            if ($dateFrom)
                $query->where('start_time', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_time', '<=', Carbon::parse($dateTo)->endOfDay());

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Araç Görevi: ' . Str::limit($item->task_description, 30),
                'date' => $item->start_time,
                'model_id' => $item->id,
                'model_type' => 'vehicle_assignment'
            ]));
        }

        // 6. SEYAHAT
        if ($typeFilter == 'all' || $typeFilter == 'travel') {
            $query = Travel::forUser($user)->where('is_important', true); // Scope
            if ($dateFrom)
                $query->where('start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_date', '<=', Carbon::parse($dateTo)->endOfDay());

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => '✈️ Seyahat: ' . Str::limit($item->name, 30),
                'date' => $item->start_date,
                'model_id' => $item->id,
                'model_type' => 'travel'
            ]));
        }

        // 7. GECİKEN GÖREVLER
        $overdueQuery = VehicleAssignment::forUser($user) // Scope
            ->where('start_time', '<', Carbon::today())
            ->whereIn('status', ['pending', 'in_progress']);

        $overdueItems = $overdueQuery->get()->map(fn($item) => (object) [
            'title' => '⚠️ GECİKEN GÖREV: ' . Str::limit($item->task_description, 40),
            'date' => $item->start_time,
            'model_id' => $item->id,
            'model_type' => 'vehicle_assignment',
            'is_overdue' => true
        ]);

        $allMappedItems = $allMappedItems->merge($overdueItems);

        return $allMappedItems->sortByDesc('date');
    }

    // --- WELCOME SANKEY GRAFİKLERİ İÇİN SERVİS ÇAĞRILARI (DÜZELTİLDİ) ---

    private function getLogisticsWelcomeData($user)
    {
        $welcomeTitle = "Yaklaşan Sevkiyatlar (Genel Bakış)";
        $chartTitle = "Kargo İçeriği -> Araç Tipi Akışı ";
        $chartData = [];

        $todayItems = Shipment::forUser($user) // Scope
            ->whereBetween('tahmini_varis_tarihi', [Carbon::today()->startOfDay(), Carbon::today()->addDays(3)->endOfDay()])
            ->orderBy('tahmini_varis_tarihi', 'asc')->get();

        $sankeyFlow = Shipment::forUser($user) // Scope
            ->select(['kargo_icerigi', 'arac_tipi', DB::raw('COUNT(*) as weight')])
            ->whereNotNull('kargo_icerigi')
            ->whereNotNull('arac_tipi')
            ->groupBy('kargo_icerigi', 'arac_tipi')
            ->having('weight', '>', 0)
            ->get();

        foreach ($sankeyFlow as $flow) {
            $normalizedKargo = $this->normalizeCargoContent($flow->kargo_icerigi); // Bu metodlar Controller içinde aşağıda olmalı
            $normalizedArac = $this->normalizeVehicleType($flow->arac_tipi);
            $chartData[] = [strval($normalizedKargo), strval($normalizedArac), (int) $flow->weight];
        }
        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Henüz Sevkiyat Girilmedi', 1];
        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getProductionWelcomeData($user)
    {
        $welcomeTitle = "Bugün Başlayan Üretim Planları";
        $chartTitle = "Makine -> Ürün Planlama Akışı (Toplam Adet)";
        $chartData = [];

        $todayItems = ProductionPlan::forUser($user) // Scope
            ->whereBetween('week_start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('week_start_date', 'asc')->get();

        $plans = ProductionPlan::forUser($user)->whereNotNull('plan_details')->get(); // Scope

        // ... (Chart mantığı aynı kalacak) ...
        $flowCounts = [];
        foreach ($plans as $plan) {
            if (is_array($plan->plan_details)) {
                foreach ($plan->plan_details as $detail) {
                    $machine = trim(strval($detail['machine'] ?? 'Bilinmiyor'));
                    $productRaw = $detail['product'] ?? 'Bilinmiyor';
                    $product = is_numeric($productRaw) ? 'Ürün-' . $productRaw : trim(strval($productRaw));
                    $quantity = (int) ($detail['quantity'] ?? 0);
                    if ($machine !== 'Bilinmiyor' && $product !== 'Bilinmiyor' && $quantity > 0) {
                        if (!isset($flowCounts[$machine]))
                            $flowCounts[$machine] = [];
                        if (!isset($flowCounts[$machine][$product]))
                            $flowCounts[$machine][$product] = 0;
                        $flowCounts[$machine][$product] += $quantity;
                    }
                }
            }
        }
        foreach ($flowCounts as $machine => $products) {
            foreach ($products as $product => $weight) {
                $chartData[] = [strval($machine), strval($product), (int) $weight];
            }
        }
        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Henüz Plan Girilmedi', 1];

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getServiceWelcomeData($user)
    {
        $welcomeTitle = "Hizmet ve Operasyon Yönetimi";
        $chartTitle = "Etkinlik Durumu & Seyahat Dağılımı";

        // Listeleme (Scope Eklendi)
        $todayEvents = Event::forUser($user)->whereDate('start_datetime', Carbon::today())->orderBy('start_datetime', 'asc')->get();
        $todayAssignments = VehicleAssignment::forUser($user)->whereDate('start_time', Carbon::today())->with('vehicle')->orderBy('start_time', 'asc')->get();
        $todayTravels = Travel::forUser($user)->whereDate('start_date', Carbon::today())->orderBy('start_date', 'asc')->get();

        $todayItems = $todayEvents->merge($todayAssignments)->merge($todayTravels)->sortBy(fn($item) => $item->start_datetime ?? $item->start_time ?? $item->start_date);

        // Chart Verisi (Scope Eklendi)
        $chartData = [];
        $eventStats = Event::forUser($user)
            ->selectRaw('event_type, visit_status, count(*) as total')
            ->groupBy('event_type', 'visit_status')
            ->get();

        foreach ($eventStats as $stat) {
            $source = $stat->event_type ? ucfirst($stat->event_type) : 'Diğer Etkinlikler';
            $target = match ($stat->visit_status) {
                'planlandi' => 'Planlandı', 'gerceklesti' => 'Gerçekleşti', 'iptal' => 'İptal', 'ertelendi' => 'Ertelendi', default => 'Durum Belirsiz'
            };
            if ($source === $target)
                $target .= ' ';
            $chartData[] = [strval($source), strval($target), (int) $stat->total];
        }

        // Rezervasyonlar (Bookings) - Scope eklenmeli (Eğer Booking modeli Trait'e sahipse)
        // Eğer Booking modelin henüz hazır değilse DB::table kullanıyorsun, onu BusinessUnit'e göre manuel filtrelemen gerekebilir.
        // Şimdilik varsayılan bırakıyorum.
        $bookingStats = DB::table('bookings')
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        foreach ($bookingStats as $stat) {
            $source = 'Seyahat Planlaması';
            $target = match ($stat->type) {
                'flight' => 'Uçak Bileti', 'hotel' => 'Otel Konaklama', 'bus' => 'Otobüs/Transfer', 'car' => 'Araç Kiralama', default => 'Diğer Rezervasyon'
            };
            $chartData[] = [$source, $target, (int) $stat->total];
        }

        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Kayıt Bulunamadı', 1];

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getMaintenanceWelcomeData($user)
    {
        $welcomeTitle = "Bugünkü Bakım Planları";
        $chartTitle = "Bakım Türü -> Varlık Akışı";
        $chartData = [];

        $todayItems = MaintenancePlan::forUser($user) // Scope
            ->with(['asset', 'type'])
            ->whereBetween('planned_start_date', [Carbon::today()->startOfDay(), Carbon::today()->addDays(2)->endOfDay()])
            ->orderBy('planned_start_date', 'asc')->get();

        $plans = MaintenancePlan::forUser($user)->with(['type', 'asset'])->get(); // Scope

        $flowCounts = [];
        foreach ($plans as $plan) {
            $source = $plan->type->name ?? 'Diğer';
            $target = $plan->asset->name ?? 'Bilinmiyor';
            if (!isset($flowCounts[$source]))
                $flowCounts[$source] = [];
            if (!isset($flowCounts[$source][$target]))
                $flowCounts[$source][$target] = 0;
            $flowCounts[$source][$target]++;
        }
        foreach ($flowCounts as $source => $targets) {
            foreach ($targets as $target => $weight) {
                $chartData[] = [strval($source), strval($target), (int) $weight];
            }
        }
        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Henüz Plan Girilmedi', 1];

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }
    private function getAdminDashboardData($user, $today, $weekStart, $weekEnd, $monthStart, $monthEnd)
    {
        // Admin Dashboard Verileri (Seçilen Birime Göre Filtreli)
        $kpiData = [
            'sevkiyat_sayisi' => Shipment::forUser($user)->whereDate('tahmini_varis_tarihi', $today)->count(),
            'plan_sayisi' => ProductionPlan::forUser($user)->whereDate('week_start_date', $today)->count(),
            'etkinlik_sayisi' => Event::forUser($user)->whereDate('start_datetime', $today)->count(),
            'arac_gorevi_sayisi' => VehicleAssignment::forUser($user)->whereDate('start_time', $today)->count(),
            'bakim_sayisi' => MaintenancePlan::forUser($user)->whereDate('planned_start_date', $today)->count(),
            'kullanici_sayisi' => User::count() // Kullanıcılar globaldir
        ];

        // Chart Data (Tüm zamanlar)
        $chartData = [];
        $allLojistik = Shipment::forUser($user)->count();
        $allUretim = ProductionPlan::forUser($user)->count();
        $allEtkinlik = Event::forUser($user)->count();
        $allBakim = MaintenancePlan::forUser($user)->count();

        if ($allLojistik > 0)
            $chartData[] = ['Lojistik', 'Sevkiyatlar', $allLojistik];
        if ($allUretim > 0)
            $chartData[] = ['Üretim', 'Planlar', $allUretim];
        if ($allEtkinlik > 0)
            $chartData[] = ['İdari İşler', 'Etkinlikler', $allEtkinlik];
        if ($allBakim > 0)
            $chartData[] = ['Bakım', 'Bakım Planları', $allBakim];

        if (empty($chartData))
            $chartData[] = ['Sistem', 'Henüz Kayıt Yok', 1];

        // Admin ekranında tablo verisi göstermiyoruz, sadece özet.
        return [
            'welcomeTitle' => session('active_unit_name', 'Genel') . " Özeti",
            'chartTitle' => "Departman Dağılımı",
            'todayItems' => collect(),
            'weeklyItems' => collect(),
            'monthlyItems' => collect(),
            'kpiData' => $kpiData,
            'chartData' => $chartData
        ];
    }
    /**
     * BİLDİRİM OKUMA VE YÖNLENDİRME
     * Kullanıcı bildirime tıkladığında bu fonksiyon çalışır.
     */
    public function readNotification($id)
    {
        // 1. Kullanıcının bildirimleri içinde bu ID'ye sahip olanı bul
        $notification = auth()->user()->unreadNotifications->where('id', $id)->first();

        if ($notification) {
            // 2. Okundu olarak işaretle (Veritabanında read_at sütununu doldurur)
            $notification->markAsRead();

            // 3. Bildirimin içindeki 'link' verisine yönlendir
            // Eğer link yoksa anasayfaya at
            return redirect($notification->data['link'] ?? route('home'));
        }

        // Eğer bildirim bulunamazsa (zaten okunmuşsa veya yoksa) direkt geri dön
        return back();
    }

    /**
     * TÜM BİLDİRİMLERİ OKUNDU YAP
     */
    public function readAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }
    /**
     * AJAX İLE BİLDİRİM KONTROLÜ
     */
    public function checkNotifications()
    {
        $notifications = auth()->user()->unreadNotifications;
        $count = $notifications->count();
        $html = '';

        if ($count > 0) {
            foreach ($notifications as $notification) {
                // Rota ve İkon ayarları
                $url = route('notifications.read', $notification->id);
                $icon = $notification->data['icon'] ?? 'fa-info-circle';
                $color = $notification->data['color'] ?? 'primary';
                $title = $notification->data['title'] ?? 'Bildirim';
                $message = $notification->data['message'] ?? '';
                $time = $notification->created_at->diffForHumans();

                // HTML Oluştur (Layout'taki yapının aynısı)
                $html .= '
                <a href="' . $url . '" class="list-group-item list-group-item-action p-3 border-bottom-0 d-flex align-items-start">
                    <div class="me-3 mt-1 text-' . $color . '">
                        <i class="fa-solid ' . $icon . ' fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-dark mb-1">' . $title . '</div>
                        <p class="mb-1 small text-muted lh-sm">' . $message . '</p>
                        <small class="text-secondary fw-bold" style="font-size: 0.7rem;">' . $time . '</small>
                    </div>
                </a>';
            }
        } else {
            $html = '
            <div class="p-4 text-center text-muted">
                <i class="fa-regular fa-bell-slash fa-2x mb-3 text-secondary opacity-50"></i>
                <p class="mb-0 small fw-medium">Şu an yeni bildiriminiz yok.</p>
            </div>';
        }

        return response()->json([
            'count' => $count,
            'html' => $html
        ]);
    }
    public function switchUnit(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:business_units,id'
        ]);

        $user = auth()->user();

        // Güvenlik: Kullanıcı gerçekten bu birime yetkili mi?
        if (!$user->businessUnits->contains('id', $request->unit_id)) {
            abort(403, 'Bu birime erişim yetkiniz yok.');
        }

        // Seçimi kaydet
        $unit = $user->businessUnits->find($request->unit_id);
        session(['active_unit_id' => $unit->id]);
        session(['active_unit_name' => $unit->name]);

        return back()->with('success', "Çalışma alanı {$unit->name} olarak değiştirildi.");
    }
    private function normalizeCargoContent($cargo)
    {
        if (empty($cargo)) {
            return 'Bilinmiyor';
        }

        $normalized = mb_strtoupper(trim($cargo), 'UTF-8');
        $specialCases = [
            'LEVBA' => 'LEVHA',
            'LEVBE' => 'LEVHA',
            'PLASTIC' => 'PLASTİK',
            'PLASTIK' => 'PLASTİK',
            'PREFORM' => 'PREFORM',
            'COPED' => 'COPED'
        ];

        return $specialCases[$normalized] ?? $normalized;
    }

    private function normalizeVehicleType($vehicle)
    {
        if (empty($vehicle)) {
            return 'Bilinmiyor';
        }

        $normalized = mb_strtoupper(trim($vehicle), 'UTF-8');

        $mapping = [
            'TIR' => 'TIR',
            'TRUCK' => 'TIR',
            'GEMI' => 'GEMİ',
            'SHIP' => 'GEMİ',
            'KAMYON' => 'KAMYON',
            'PICKUP' => 'KAMYONET',
            'KAMYONET' => 'KAMYONET'
        ];

        return $mapping[$normalized] ?? $normalized;
    }
}