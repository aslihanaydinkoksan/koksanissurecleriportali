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
use App\Services\StatisticsService; // EKLENDİ

class HomeController extends Controller
{
    protected $statsService; // EKLENDİ

    // Servisi buraya enjekte ediyoruz
    public function __construct(StatisticsService $statsService)
    {
        $this->middleware('auth');
        $this->statsService = $statsService;
    }

    /**
     * ANA TAKVİM SAYFASI
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $departmentSlug = $user->department ? strtolower(trim($user->department->slug)) : null;
        $departmentName = $user->department?->name ?? 'Genel';

        $departmentData = [];
        switch ($departmentSlug) {
            case 'lojistik':
                $departmentData = $this->getLojistikIndexData($user);
                break;
            case 'uretim':
                $departmentData = $this->getUretimIndexData($user);
                break;
            case 'hizmet':
                $departmentData = $this->getHizmetIndexData($user);
                break;
            case 'bakim':
                $departmentData = $this->getBakimIndexData($user);
                break;
            default:
                if ($user->role === 'admin' || ($user->role === 'yönetici' && is_null($departmentSlug))) {

                    $lojistikData = $this->getLojistikIndexData($user);
                    $uretimData = $this->getUretimIndexData($user);
                    $hizmetData = $this->getHizmetIndexData($user);
                    $bakimData = $this->getBakimIndexData($user);

                    $allEvents = array_merge(
                        $lojistikData['events'],
                        $uretimData['events'],
                        $hizmetData['events'],
                        $bakimData['events']
                    );

                    $departmentData = [
                        'events' => $allEvents,
                        'chartData' => [],
                        'statsTitle' => "Tüm Departmanlar (Genel Bakış)"
                    ];
                    $departmentName = "Genel Takvim";

                } else {
                    $departmentData = [
                        'events' => [],
                        'chartData' => [],
                        'statsTitle' => $departmentName . " İstatistikleri"
                    ];
                }
                break;
        }
        $users = collect();
        if (in_array($user->role, ['admin', 'yönetici'])) {
            $users = User::with('department')->orderBy('name')->get();
        }
        return view('home', array_merge(
            [
                'users' => $users,
                'departmentName' => $departmentName,
                'departmentSlug' => $departmentSlug,
            ],
            $departmentData
        ));
    }

    /**
     * KARŞILAMA / DASHBOARD SAYFASI
     */
    public function welcome(Request $request)
    {
        $user = Auth::user();
        $allItems = $this->getMappedImportantItems($request);
        $importantItems = $allItems->take(4);
        $importantItemsCount = $allItems->count();

        $departmentSlug = $user->department ? trim($user->department->slug) : null;
        $userRole = $user->role;
        $isTvUser = ($user->email === 'tv@koksan.com');

        if ($isTvUser) {
            $departmentSlug = null;
        }

        $today = \Carbon\Carbon::today();
        $weekStart = \Carbon\Carbon::now()->startOfWeek();
        $weekEnd = \Carbon\Carbon::now()->endOfWeek();
        $monthStart = \Carbon\Carbon::now()->startOfMonth();
        $monthEnd = \Carbon\Carbon::now()->endOfMonth();

        $welcomeTitle = "Hoş Geldiniz";
        $chartTitle = "Genel Bakış";
        $chartData = [];
        $kpiData = []; // KPI Verileri dizisi

        $todayItems = collect();
        $weeklyItems = collect();
        $monthlyItems = collect();

        if ($departmentSlug === 'uretim') {
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->getProductionWelcomeData();
            $query = \App\Models\ProductionPlan::query();
            $todayItems = (clone $query)->whereDate('week_start_date', $today)->get();
            $weeklyItems = (clone $query)->whereBetween('week_start_date', [$weekStart, $weekEnd])->get();
            $monthlyItems = (clone $query)->whereBetween('week_start_date', [$monthStart, $monthEnd])->get();

        } elseif ($departmentSlug === 'hizmet') {
            // HİZMET DEPARTMANI İÇİN ÖZEL VERİLER
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->getServiceWelcomeData();

            // Listeler (Etkinlik + Araç Görevleri)
            $eventQ = \App\Models\Event::query();
            $tEvents = (clone $eventQ)->whereDate('start_datetime', $today)->get();
            $wEvents = (clone $eventQ)->whereBetween('start_datetime', [$weekStart, $weekEnd])->get();
            $mEvents = (clone $eventQ)->whereBetween('start_datetime', [$monthStart, $monthEnd])->get();

            $vehicleQ = \App\Models\VehicleAssignment::whereIn('status', ['pending', 'in_progress', 'approved']);
            $tVehicle = (clone $vehicleQ)->whereDate('start_time', $today)->get();
            $wVehicle = (clone $vehicleQ)->whereBetween('start_time', [$weekStart, $weekEnd])->get();
            $mVehicle = (clone $vehicleQ)->whereBetween('start_time', [$monthStart, $monthEnd])->get();

            $todayItems = $tEvents->merge($tVehicle)->sortBy('start_datetime');
            $weeklyItems = $wEvents->merge($wVehicle)->sortBy('start_datetime');
            $monthlyItems = $mEvents->merge($mVehicle)->sortBy('start_datetime');

            // HİZMET KPI VERİLERİ (Blade'deki kutucuklar için)
            $kpiData = [
                // Gelecek etkinlikler (iptal olmayanlar)
                'etkinlik_sayisi' => \App\Models\Event::whereDate('start_datetime', '>=', $today)
                    ->where('visit_status', '!=', 'iptal')
                    ->count(),

                // Müşteri ziyareti olan etkinlikler
                'musteri_ziyareti' => \App\Models\Event::has('customerVisit')->count(),

                // Rezervasyon/Seyahat Sayısı (Bookings tablosu veya Event'e bağlı bookingler)
                'rezervasyon_sayisi' => DB::table('bookings')->count(),

                // Toplam Araç (Admin için görünmesi istenirse)
                'toplam_arac' => \App\Models\Vehicle::count(),
            ];

        } elseif ($departmentSlug === 'ulastirma') {
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->statsService->getUlastirmaWelcomeData();

            $query = \App\Models\VehicleAssignment::whereIn('status', ['pending', 'approved', 'in_progress']);
            $todayItems = (clone $query)->whereDate('start_time', $today)->orderBy('start_time')->get();
            $weeklyItems = (clone $query)->whereBetween('start_time', [$weekStart, $weekEnd])->orderBy('start_time')->get();
            $monthlyItems = (clone $query)->whereBetween('start_time', [$monthStart, $monthEnd])->orderBy('start_time')->get();

            // Ulaştırma KPI
            $kpiData = [
                'aktif_gorev' => \App\Models\VehicleAssignment::where('status', 'in_progress')->count(),
                'bekleyen_talep' => \App\Models\VehicleAssignment::where('status', 'pending')->count(),
                'toplam_arac' => \App\Models\Vehicle::count(),
                'bugunku_gorev' => $todayItems->count()
            ];

        } elseif ($departmentSlug === 'lojistik') {
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->getLogisticsWelcomeData();
            $query = \App\Models\Shipment::query();
            $todayItems = (clone $query)->whereDate('tahmini_varis_tarihi', $today)->get();
            $weeklyItems = (clone $query)->whereBetween('tahmini_varis_tarihi', [$weekStart, $weekEnd])->get();
            $monthlyItems = (clone $query)->whereBetween('tahmini_varis_tarihi', [$monthStart, $monthEnd])->get();

        } elseif ($departmentSlug === 'bakim') {
            list($welcomeTitle, $chartTitle, $dummyToday, $chartData) = $this->getMaintenanceWelcomeData();
            $query = \App\Models\MaintenancePlan::with('asset');
            $todayItems = (clone $query)->whereDate('planned_start_date', $today)->get();
            $weeklyItems = (clone $query)->whereBetween('planned_start_date', [$weekStart, $weekEnd])->get();
            $monthlyItems = (clone $query)->whereBetween('planned_start_date', [$monthStart, $monthEnd])->get();

        } elseif ($userRole == 'admin' || (empty($departmentSlug) && $userRole == 'yönetici') || $isTvUser) {
            $adminData = $this->getAdminDashboardData($today, $weekStart, $weekEnd, $monthStart, $monthEnd);
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
        $modelType = $request->input('model_type');
        $modelId = $request->input('model_id');
        $isImportant = $request->input('is_important');

        $isManager = in_array($user->role, ['admin', 'yönetici', 'müdür']);
        $isAllowed = false;

        if ($isManager) {
            $isAllowed = true;
        } elseif ($modelType === 'vehicle_assignment') {
            $isAllowed = true;
        }

        if (!$isAllowed) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $model = null;
        switch ($modelType) {
            case 'shipment':
                $model = \App\Models\Shipment::find($modelId);
                break;
            case 'production_plan':
                $model = \App\Models\ProductionPlan::find($modelId);
                break;
            case 'event':
                $model = \App\Models\Event::find($modelId);
                break;
            case 'vehicle_assignment':
                $model = \App\Models\VehicleAssignment::find($modelId);
                break;
            case 'travel':
                $model = \App\Models\Travel::find($modelId);
                break;
            case 'maintenance_plan':
                $model = \App\Models\MaintenancePlan::find($modelId);
                break;
        }

        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Kayıt bulunamadı.'], 404);
        }

        if ($model instanceof \App\Models\MaintenancePlan) {
            $model->priority = $isImportant ? 'critical' : 'normal';
        } else {
            $model->is_important = $isImportant;
        }

        $model->save();

        return response()->json([
            'success' => true,
            'message' => 'İşlem başarılı.',
            'new_status' => $isImportant
        ]);
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
        $shipments = Shipment::with('onaylayanKullanici')->get();

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
        $plans = ProductionPlan::with('user')->get();

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
        $serviceEvents = Event::with('user')->get();
        foreach ($serviceEvents as $event) {
            $events[] = [
                'title' => 'Etkinlik: ' . $event->title,
                'start' => $event->start_datetime->format('Y-m-d\TH:i:s'),
                'end' => $event->end_datetime->format('Y-m-d\TH:i:s'),
                'color' => '#F093FB',
                'extendedProps' => ['eventType' => 'service_event', 'model_type' => 'event', 'is_important' => $event->is_important, 'id' => $event->id, 'details' => ['Konum' => $event->location]]
            ];
        }

        $assignments = VehicleAssignment::with(['vehicle', 'createdBy'])->get();
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
        $plans = MaintenancePlan::with(['asset', 'type'])->get();
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

        // Varsayılan departman filtresi (Admin panellerinden gelen istekler için)
        $deptFilter = $request->input('department_id', null);

        // KURAL: Admin veya Global Yönetici değilse, zorunlu olarak kendi departmanını filtreler.
        // Müdürler de eğer "is-global-manager" yetkisine sahip değilse kendi departmanına hapsolur.
        $isUserFiltered = false;
        if ($user->department_id && $user->role !== 'admin' && !Auth::user()->can('is-global-manager')) {
            $deptFilter = $user->department_id;
            $isUserFiltered = true;
        }

        $allMappedItems = collect();

        // --- 1. SEVKİYAT ---
        if ($typeFilter == 'all' || $typeFilter == 'shipment') {
            $query = Shipment::where('is_important', true);

            if ($dateFrom)
                $query->where('tahmini_varis_tarihi', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('tahmini_varis_tarihi', '<=', Carbon::parse($dateTo)->endOfDay());

            // Kullanıcı yetkisine veya seçilen departmana göre filtrele
            if ($deptFilter)
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Sevkiyat: ' . ($item->kargo_icerigi ?? 'Detay Yok'),
                'date' => $item->tahmini_varis_tarihi,
                'model_id' => $item->id,
                'model_type' => 'shipment'
            ]));
        }

        // --- 2. ÜRETİM PLANI ---
        if ($typeFilter == 'all' || $typeFilter == 'production_plan') {
            $query = ProductionPlan::where('is_important', true);

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

        // --- 3. BAKIM PLANI ---
        if ($typeFilter == 'all' || $typeFilter == 'maintenance_plan') {
            // Bakımda "is_important" yerine genelde öncelik (priority) kullanılır
            $query = MaintenancePlan::whereIn('priority', ['high', 'critical']);

            if ($dateFrom)
                $query->where('planned_start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('planned_start_date', '<=', Carbon::parse($dateTo)->endOfDay());

            if ($deptFilter)
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Bakım: ' . ($item->asset->name ?? 'Bilinmiyor') . ' (' . $item->title . ')',
                'date' => $item->planned_start_date,
                'model_id' => $item->id,
                'model_type' => 'maintenance_plan'
            ]));
        }

        // --- 4. ETKİNLİK ---
        if ($typeFilter == 'all' || $typeFilter == 'event') {
            $query = Event::where('is_important', true);

            if ($dateFrom)
                $query->where('start_datetime', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_datetime', '<=', Carbon::parse($dateTo)->endOfDay());

            if ($deptFilter)
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Etkinlik: ' . $item->title,
                'date' => $item->start_datetime,
                'model_id' => $item->id,
                'model_type' => 'event'
            ]));
        }

        // --- 5. ARAÇ GÖREVİ ---
        if ($typeFilter == 'all' || $typeFilter == 'vehicle_assignment') {
            $query = VehicleAssignment::where('is_important', true);

            if ($dateFrom)
                $query->where('start_time', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_time', '<=', Carbon::parse($dateTo)->endOfDay());

            if ($deptFilter)
                $query->whereHas('createdBy', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Araç Görevi: ' . Str::limit($item->task_description, 30),
                'date' => $item->start_time,
                'model_id' => $item->id,
                'model_type' => 'vehicle_assignment'
            ]));
        }

        // --- 6. SEYAHAT ---
        if ($typeFilter == 'all' || $typeFilter == 'travel') {
            $query = Travel::where('is_important', true);

            if ($dateFrom)
                $query->where('start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_date', '<=', Carbon::parse($dateTo)->endOfDay());

            if ($deptFilter)
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => '✈️ Seyahat: ' . Str::limit($item->name, 30),
                'date' => $item->start_date,
                'model_id' => $item->id,
                'model_type' => 'travel'
            ]));
        }

        // --- 7. GECİKEN GÖREVLER (Her zaman eklenir ama departman filtresine uyar) ---
        // Gecikenler "Important" işaretli olmasa bile önemlidir.
        $overdueQuery = VehicleAssignment::where('start_time', '<', Carbon::today())
            ->whereIn('status', ['pending', 'in_progress']);

        if ($deptFilter) {
            $overdueQuery->whereHas('createdBy', fn($q) => $q->where('department_id', $deptFilter));
        }

        $overdueItems = $overdueQuery->get()->map(fn($item) => (object) [
            'title' => '⚠️ GECİKEN GÖREV: ' . Str::limit($item->task_description, 40),
            'date' => $item->start_time,
            'model_id' => $item->id,
            'model_type' => 'vehicle_assignment',
            'is_overdue' => true
        ]);

        // Gecikenleri de listeye ekle
        $allMappedItems = $allMappedItems->merge($overdueItems);

        // Tarihe göre sırala (En yeni en üstte)
        return $allMappedItems->sortByDesc('date');
    }

    // --- WELCOME SANKEY GRAFİKLERİ İÇİN SERVİS ÇAĞRILARI (DÜZELTİLDİ) ---

    private function getLogisticsWelcomeData()
    {
        $welcomeTitle = "Yaklaşan Sevkiyatlar (Genel Bakış)";
        $chartTitle = "Kargo İçeriği -> Araç Tipi Akışı ";
        $chartData = [];
        $todayItems = Shipment::whereBetween('tahmini_varis_tarihi', [Carbon::today()->startOfDay(), Carbon::today()->addDays(3)->endOfDay()])->orderBy('tahmini_varis_tarihi', 'asc')->get();

        $sankeyFlow = Shipment::select(['kargo_icerigi', 'arac_tipi', DB::raw('COUNT(*) as weight')])->whereNotNull('kargo_icerigi')->whereNotNull('arac_tipi')->groupBy('kargo_icerigi', 'arac_tipi')->having('weight', '>', 0)->get();
        foreach ($sankeyFlow as $flow) {
            // SERVİSTEN ÇAĞIRIYORUZ
            $normalizedKargo = $this->statsService->normalizeCargoContent($flow->kargo_icerigi);
            $normalizedArac = $this->statsService->normalizeVehicleType($flow->arac_tipi);
            $chartData[] = [strval($normalizedKargo), strval($normalizedArac), (int) $flow->weight];
        }
        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Henüz Sevkiyat Girilmedi', 1];
        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getProductionWelcomeData()
    {
        $welcomeTitle = "Bugün Başlayan Üretim Planları";
        $chartTitle = "Makine -> Ürün Planlama Akışı (Toplam Adet)";
        $chartData = [];
        $todayItems = ProductionPlan::whereBetween('week_start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('week_start_date', 'asc')->get();

        $plans = ProductionPlan::whereNotNull('plan_details')->get();
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

    private function getServiceWelcomeData()
    {
        $welcomeTitle = "Hizmet ve Operasyon Yönetimi";
        $chartTitle = "Etkinlik Durumu & Seyahat Dağılımı";

        // --- LİSTELEME VERİLERİ (Aynen Kalıyor) ---
        $todayEvents = Event::whereDate('start_datetime', Carbon::today())->orderBy('start_datetime', 'asc')->get();
        $todayAssignments = VehicleAssignment::whereDate('start_time', Carbon::today())->with('vehicle')->orderBy('start_time', 'asc')->get();
        $todayTravels = Travel::whereDate('start_date', Carbon::today())->orderBy('start_date', 'asc')->get();

        // Listede Bookings (Rezervasyonlar) da görünsün istersek buraya eklenebilir ama şimdilik takvim yapısı bozulmasın diye ellemiyorum.
        $todayItems = $todayEvents->merge($todayAssignments)->merge($todayTravels)->sortBy(fn($item) => $item->start_datetime ?? $item->start_time ?? $item->start_date);


        // --- GRAFİK VERİSİ (SANKEY) ---
        $chartData = [];
        $eventStats = Event::selectRaw('event_type, visit_status, count(*) as total')
            ->groupBy('event_type', 'visit_status')
            ->get();

        foreach ($eventStats as $stat) {
            // Kaynak: Etkinlik Tipi (Boşsa 'Genel Etkinlik' yazsın)
            $source = $stat->event_type ? ucfirst($stat->event_type) : 'Diğer Etkinlikler';

            // Hedef: Durum
            $target = match ($stat->visit_status) {
                'planlandi' => 'Planlandı',
                'gerceklesti' => 'Gerçekleşti',
                'iptal' => 'İptal',
                'ertelendi' => 'Ertelendi',
                default => 'Durum Belirsiz'
            };

            // Döngü olmaması için isim çakışmasını önle
            if ($source === $target)
                $target .= ' ';

            $chartData[] = [strval($source), strval($target), (int) $stat->total];
        }

        // 2. AKIŞ: SEYAHATLER / REZERVASYONLAR (Genel "Seyahat" -> Tür)
        // Bookings tablosundaki verileri alalım (Uçak, Otel vb.)
        $bookingStats = DB::table('bookings')
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        foreach ($bookingStats as $stat) {
            // Kaynak: Sabit bir düğüm olsun
            $source = 'Seyahat Planlaması';

            // Hedef: Rezervasyon Tipi
            $target = match ($stat->type) {
                'flight' => 'Uçak Bileti',
                'hotel' => 'Otel Konaklama',
                'bus' => 'Otobüs/Transfer',
                'car' => 'Araç Kiralama',
                default => 'Diğer Rezervasyon'
            };

            $chartData[] = [$source, $target, (int) $stat->total];
        }

        // Eğer hala veri yoksa
        if (empty($chartData)) {
            $chartData[] = ['Veri Yok', 'Kayıt Bulunamadı', 1];
        }

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getMaintenanceWelcomeData()
    {
        $welcomeTitle = "Bugünkü Bakım Planları";
        $chartTitle = "Bakım Türü -> Varlık Akışı";
        $chartData = [];
        $todayItems = MaintenancePlan::with(['asset', 'type'])->whereBetween('planned_start_date', [Carbon::today()->startOfDay(), Carbon::today()->addDays(2)->endOfDay()])->orderBy('planned_start_date', 'asc')->get();

        $plans = MaintenancePlan::with(['type', 'asset'])->get();
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
    private function getAdminDashboardData($today, $weekStart, $weekEnd, $monthStart, $monthEnd)
    {
        $welcomeTitle = "Genel Bakış";
        $chartTitle = "Şirket Geneli İş Akışı";

        // --- HATA BURADAYDI, EKSİK KEYLERİ EKLEDİK ---
        $kpiData = [
            'sevkiyat_sayisi' => \App\Models\Shipment::whereDate('tahmini_varis_tarihi', $today)->count(),
            'plan_sayisi' => \App\Models\ProductionPlan::whereDate('week_start_date', $today)->count(),
            'etkinlik_sayisi' => \App\Models\Event::whereDate('start_datetime', $today)->count(), // Düzeleltildi
            'arac_gorevi_sayisi' => \App\Models\VehicleAssignment::whereDate('start_time', $today)->count(), // Düzeltildi
            'bakim_sayisi' => \App\Models\MaintenancePlan::whereDate('planned_start_date', $today)->count(), // Düzeltildi
            'kullanici_sayisi' => \App\Models\User::count()
        ];

        // Chart Data (Basit pasta grafiği için veriler)
        $chartData = [];
        $lojistikCount = (int) $kpiData['sevkiyat_sayisi']; // Direkt yukarıdan alabiliriz veya genel count
        // Grafikte tüm zamanları göstermek daha mantıklı olabilir:
        $allLojistik = \App\Models\Shipment::count();
        $allUretim = \App\Models\ProductionPlan::count();
        $allEtkinlik = \App\Models\Event::count();
        $allBakim = \App\Models\MaintenancePlan::count();

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

        // Bugünün verilerini boş collection olarak başlatalım (Admin dashboard'da liste göstermiyorsak)
        // Veya yukarıdaki gibi sorgularla doldurabilirsin. Şimdilik hatayı çözmek için boş geçiyoruz.
        return [
            'welcomeTitle' => $welcomeTitle,
            'chartTitle' => $chartTitle,
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
}