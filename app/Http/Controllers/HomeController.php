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
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Gate;
use App\Models\Department;
use App\Data\StatisticsData;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            default:
                if ($user->role === 'admin' || ($user->role === 'yönetici' && is_null($departmentSlug))) {

                    $lojistikData = $this->getLojistikIndexData($user);
                    $uretimData = $this->getUretimIndexData($user);
                    $hizmetData = $this->getHizmetIndexData($user);

                    $allEvents = array_merge(
                        $lojistikData['events'],
                        $uretimData['events'],
                        $hizmetData['events']
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

    public function welcome(Request $request)
    {
        $user = Auth::user();
        $allItems = $this->getMappedImportantItems($request);
        $importantItems = $allItems->take(4);
        $importantItemsCount = $allItems->count();

        $departmentSlug = $user->department ? trim($user->department->slug) : null;
        $userRole = $user->role;
        $isTvUser = ($user->email === 'tv@koksan.com');

        //ekrandan takip etmek icin kullanici
        if ($isTvUser) {
            $departmentSlug = null;
        }

        $welcomeTitle = "Hoş Geldiniz";
        $todayItems = collect();
        $chartTitle = "";
        $chartData = [];
        $kpiData = [];
        if ($departmentSlug === 'uretim') {
            list($welcomeTitle, $chartTitle, $todayItems, $chartData) = $this->getProductionWelcomeData();
        } elseif ($departmentSlug === 'hizmet') {
            list($welcomeTitle, $chartTitle, $todayItems, $chartData) = $this->getServiceWelcomeData();
        } elseif ($departmentSlug === 'lojistik') {
            list($welcomeTitle, $chartTitle, $todayItems, $chartData) = $this->getLogisticsWelcomeData();
        } elseif ($userRole == 'admin' || (empty($departmentSlug) && $userRole == 'yönetici') || $isTvUser) {
            $welcomeTitle = "Genel Bakış";
            $chartTitle = "Şirket Geneli İş Akışı (Toplam Kayıt)";

            // KRİTİK EKLEME: Admin/Genel Yönetici için tüm görevleri birleştir.

            $lojistikWelcomeData = $this->getLogisticsWelcomeData();
            $uretimWelcomeData = $this->getProductionWelcomeData();
            $hizmetWelcomeData = $this->getServiceWelcomeData();

            // todayItems'ı tüm metotlardan birleştir (indeks [2] todayItems'dır)
            $todayItems = $lojistikWelcomeData[2]
                ->merge($uretimWelcomeData[2])
                ->merge($hizmetWelcomeData[2])
                ->sortBy(fn($item) => $item->start_datetime ?? $item->start_time ?? $item->start_date ?? $item->week_start_date);
            $kpiData = [
                // 1. Sevkiyatlar (Lojistik)
                'sevkiyat_sayisi' => \App\Models\Shipment::whereDate('tahmini_varis_tarihi', Carbon::today())->count(),

                // 2. Üretim Planları (Üretim)
                'plan_sayisi' => \App\Models\ProductionPlan::whereDate('week_start_date', Carbon::today())->count(),

                // 3. Etkinlikler (Hizmet) - SADECE ETKİNLİKLER
                'etkinlik_sayisi' => \App\Models\Event::whereDate('start_datetime', Carbon::today())->count(),

                // 4. Araç Görevleri (Hizmet) - YENİ: SADECE ARAÇ GÖREVLERİ
                'arac_gorevi_sayisi' => \App\Models\VehicleAssignment::whereDate('start_time', Carbon::today())
                    ->whereIn('status', ['pending', 'in_progress']) // Sadece aktif olanlar
                    ->count(),

                // 5. Toplam Kullanıcı (Sistem)
                'kullanici_sayisi' => \App\Models\User::count()
            ];

            $chartTitle = "Şirket Geneli İş Akışı (Toplam Kayıt)";

            $lojistikCount = (int) \App\Models\Shipment::count();
            $uretimCount = (int) \App\Models\ProductionPlan::count();
            $etkinlikCount = (int) \App\Models\Event::count();
            $aracCount = (int) \App\Models\VehicleAssignment::count();

            $chartData = [];
            if ($lojistikCount > 0)
                $chartData[] = ['Lojistik', 'Sevkiyatlar', $lojistikCount];
            if ($uretimCount > 0)
                $chartData[] = ['Üretim', 'Planlar', $uretimCount];
            if ($etkinlikCount > 0)
                $chartData[] = ['İdari İşler', 'Etkinlikler', $etkinlikCount];
            if ($aracCount > 0)
                $chartData[] = ['İdari İşler', 'Araç Görevleri', $aracCount]; // Bu zaten doğruydu

            if (empty($chartData)) {
                $chartData[] = ['Sistem', 'Henüz Kayıt Yok', 1];
            }
        }

        Log::info('Welcome sayfası yükleniyor (NİHAİ + KPI)', [
            'user_id' => $user->id,
            'department_slug' => $departmentSlug,
            'role' => $userRole,
            'todayItems_count' => $todayItems->count(),
            'chartData_count' => count($chartData),
            'kpiData_count' => count($kpiData)
        ]);

        $chartType = 'sankey';

        return view('welcome', compact(
            'importantItems',
            'importantItemsCount',
            'welcomeTitle',
            'todayItems',
            'chartType',
            'chartData',
            'chartTitle',
            'departmentSlug',
            'kpiData'
        ));
    }

    public function showStatistics(Request $request)
    {
        $user = Auth::user();
        $isTvUser = ($user->email === 'tv@koksan.com');
        $isSuperUser = $user->role === 'admin' || $user->role === 'yönetici' || $isTvUser;
        if ($isSuperUser) {
            $departmentSlug = $request->input('target_dept', 'genel'); // Varsayılan: Genel
        } else {
            $departmentSlug = $user->department ? trim($user->department->slug) : 'genel';
        }

        if ($departmentSlug === 'genel') {
            $departmentName = 'Genel Bakış';
        } else {
            // Seçilen slug'a ait departman ismini bulalım (Görsel açıdan düzgün görünmesi için)
            $targetDeptObj = \App\Models\Department::where('slug', $departmentSlug)->first();
            $departmentName = $targetDeptObj ? $targetDeptObj->name : ucfirst($departmentSlug);
        }
        $pageTitle = $departmentName . " İstatistikleri";


        $defaultStartDate = Carbon::now()->startOfYear();
        $defaultEndDate = Carbon::now()->endOfDay();
        $filters = [
            'date_from' => $request->input('date_from', $defaultStartDate->toDateString()),
            'date_to' => $request->input('date_to', $defaultEndDate->toDateString())
        ];
        $startDate = Carbon::parse($filters['date_from'])->startOfDay();
        $endDate = Carbon::parse($filters['date_to'])->endOfDay();


        $statsData = [];
        switch ($departmentSlug) {
            case 'lojistik':
                $pageTitle = "Ayrıntılı Sevkiyat İstatistikleri";
                $statsData = $this->getLojistikStatsData($startDate, $endDate)->toArray();
                break;
            case 'uretim':
                $pageTitle = "Ayrıntılı Üretim İstatistikleri";
                $statsData = $this->getUretimStatsData($startDate, $endDate)->toArray();
                break;
            case 'hizmet':
                $pageTitle = "Ayrıntılı İdari İşler İstatistikleri";
                $statsData = $this->getHizmetStatsData($startDate, $endDate)->toArray();
                break;
            default:
                $pageTitle = "Genel Bakış İstatistikleri"; // Başlığı da netleştirelim
                $statsData = $this->getGenelBakisData($startDate, $endDate)->toArray();
                break;
        }
        $allDepartments = collect();
        if ($isSuperUser) {
            $allDepartments = Department::orderBy('name')->get();
        }
        return view('statistics.index', array_merge(
            [
                'pageTitle' => $pageTitle,
                'departmentSlug' => $departmentSlug,
                'departmentName' => $departmentName,
                'filters' => $filters,
                'isSuperUser' => $isSuperUser,
                'allDepartments' => $allDepartments,
                'isTvUser' => $isTvUser // View'da stil ayarı için
            ],
            $statsData
        ));
    }
    /**
     * Genel bakış için özet istatistikler
     */
    private function getGenelBakisData(Carbon $startDate, Carbon $endDate): StatisticsData
    {
        // Tüm departmanların özet verilerini topla
        $lojistikCount = \App\Models\Shipment::whereBetween('created_at', [$startDate, $endDate])->count();
        $uretimCount = \App\Models\ProductionPlan::whereBetween('week_start_date', [$startDate, $endDate])->count();
        $hizmetEventCount = \App\Models\Event::whereBetween('start_datetime', [$startDate, $endDate])->count();
        $hizmetTravelCount = \App\Models\Travel::whereBetween('start_date', [$startDate, $endDate])->count();

        $chartData = [
            'departmentSummary' => [
                'title' => 'Departman Aktivite Özeti',
                'labels' => ['Lojistik', 'Üretim', 'İdari İşler'],
                'data' => [
                    $lojistikCount,
                    $uretimCount,
                    $hizmetEventCount + $hizmetTravelCount
                ]
            ]
        ];

        return new StatisticsData(
            chartData: $chartData,
            shipmentsForFiltering: [],
            productionPlansForFiltering: [],
            eventsForFiltering: [],
            assignmentsForFiltering: [],
            vehiclesForFiltering: [],
            monthlyLabels: []
        );
    }
    private function getLojistikIndexData($user)
    {
        $events = [];
        $now = Carbon::now();
        $shipments = Shipment::with('onaylayanKullanici')->get();

        foreach ($shipments as $shipment) {
            $cikisTarihi = null;
            $varisTarihi = null;
            try {
                if ($shipment->cikis_tarihi) {
                    $cikisTarihi = Carbon::parse($shipment->cikis_tarihi);
                }
                if ($shipment->tahmini_varis_tarihi) {
                    $varisTarihi = Carbon::parse($shipment->tahmini_varis_tarihi);
                }
            } catch (\Exception $e) {
                Log::error("Tarih parse hatası - Shipment ID: " . $shipment->id, ['error' => $e->getMessage()]);
            }

            $color = '#0d6efd';
            if ($shipment->onaylanma_tarihi) {
                $color = '#198754';
            } elseif ($varisTarihi) {
                if ($now->greaterThan($varisTarihi)) {
                    $color = '#dc3545';
                } elseif ($varisTarihi->isBetween($now, $now->copy()->addDays(3))) {
                    $color = '#ffc107';
                }
            }

            $normalizedKargo = $this->normalizeCargoContent($shipment->kargo_icerigi);
            $normalizedAracTipi = $this->normalizeVehicleType($shipment->arac_tipi);

            $extendedProps = [
                'eventType' => 'shipment',
                'model_type' => 'shipment',
                'is_important' => $shipment->is_important,
                'title' => '🚚 Sevkiyat Detayı: ' . $normalizedKargo,
                'id' => $shipment->id,
                'user_id' => $shipment->user_id,
                'editUrl' => route('shipments.edit', $shipment->id),
                'deleteUrl' => route('shipments.destroy', $shipment->id),
                'exportUrl' => route('shipments.export', $shipment->id),
                'onayUrl' => route('shipments.onayla', $shipment->id),
                'onayKaldirUrl' => route('shipments.onayiGeriAl', $shipment->id),
                'details' => [
                    'Araç Tipi' => $normalizedAracTipi,
                    'Plaka' => $shipment->plaka,
                    'Dorse Plakası' => $shipment->dorse_plakasi,
                    'Şoför Adı' => $shipment->sofor_adi,
                    'IMO Numarası' => $shipment->imo_numarasi,
                    'Gemi Adı' => $shipment->gemi_adi,
                    'Kalkış Limanı' => $shipment->kalkis_limani,
                    'Varış Limanı' => $shipment->varis_limani,
                    'Kalkış Noktası' => $shipment->kalkis_noktasi,
                    'Varış Noktası' => $shipment->varis_noktasi,
                    'Sevkiyat Türü' => $shipment->shipment_type === 'import' ? 'İthalat' : 'İhracat',
                    'Kargo Yükü' => $normalizedKargo,
                    'Kargo Tipi' => $shipment->kargo_tipi,
                    'Kargo Miktarı' => $shipment->kargo_miktari,
                    'Çıkış Tarihi' => $cikisTarihi ? $cikisTarihi->format('d.m.Y H:i') : '-',
                    'Tahmini Varış' => $varisTarihi ? $varisTarihi->format('d.m.Y H:i') : '-',
                    'Açıklamalar' => $shipment->aciklamalar,
                    'Dosya Yolu' => $shipment->dosya_yolu ? asset('storage/' . $shipment->dosya_yolu) : null,
                    'Onay Durumu' => $shipment->onaylanma_tarihi ? $shipment->onaylanma_tarihi->format('d.m.Y H:i') : null,
                    'Onaylayan' => $shipment->onaylayanKullanici?->name,
                ]
            ];

            if ($cikisTarihi) {
                $events[] = ['title' => 'ÇIKIŞ: ' . $normalizedKargo . ' (' . $normalizedAracTipi . ')', 'start' => $cikisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
            }
            if ($varisTarihi) {
                $events[] = ['title' => 'VARIŞ: ' . $normalizedKargo . ' (' . $normalizedAracTipi . ')', 'start' => $varisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
            }
        }

        $chartData = [];
        $statsTitle = "Sevkiyat İstatistikleri";
        $hourlyLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));
        $hourlyCounts = array_fill_keys(range(0, 23), 0);

        $hourlyDbData = Shipment::select([DB::raw('HOUR(cikis_tarihi) as hour'), DB::raw('COUNT(*) as count')])
            ->groupBy('hour')->pluck('count', 'hour');
        foreach ($hourlyDbData as $hour => $count) {
            if (isset($hourlyCounts[$hour])) {
                $hourlyCounts[$hour] = $count;
            }
        }
        $chartData['hourly'] = ['labels' => $hourlyLabels, 'data' => array_values($hourlyCounts), 'title' => '⏰ Saatlik Sevkiyat Yoğunluğu'];

        $dayLabels = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
        $dayCounts = array_fill(0, 7, 0);
        $dayMap = [2 => 0, 3 => 1, 4 => 2, 5 => 3, 6 => 4, 7 => 5, 1 => 6];
        $dailyDbData = Shipment::select([DB::raw('DAYOFWEEK(cikis_tarihi) as day_of_week'), DB::raw('COUNT(*) as count')])
            ->groupBy('day_of_week')->pluck('count', 'day_of_week');
        foreach ($dailyDbData as $dayNum => $count) {
            if (isset($dayMap[$dayNum])) {
                $dayCounts[$dayMap[$dayNum]] = $count;
            }
        }
        $chartData['daily'] = ['labels' => $dayLabels, 'data' => $dayCounts, 'title' => '📅 Haftalık Sevkiyat Yoğunluğu'];

        return ['events' => $events, 'chartData' => $chartData, 'statsTitle' => $statsTitle];
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
                'extendedProps' => [
                    'eventType' => 'production',
                    'title' => '📅 Üretim Planı Detayı',
                    'id' => $plan->id,
                    'user_id' => $plan->user_id,
                    'editUrl' => route('production.plans.edit', $plan->id),
                    'deleteUrl' => route('production.plans.destroy', $plan->id),
                    'details' => [
                        'Plan Başlığı' => $plan->plan_title,
                        'Hafta Başlangıcı' => $plan->week_start_date->format('d.m.Y'),
                        'Plan Detayları' => $plan->plan_details,
                        'Oluşturan' => $plan->user?->name,
                        'Kayıt Tarihi' => $plan->created_at->format('d.m.Y H:i'),
                    ]
                ]
            ];
        }

        $chartData = [];
        $statsTitle = "Üretim İstatistikleri";
        $twelveWeeksAgo = Carbon::now()->subWeeks(11)->startOfWeek();
        $weeklyPlanCounts = ProductionPlan::select([DB::raw('YEARWEEK(week_start_date, 1) as year_week'), DB::raw('COUNT(*) as count')])
            ->where('week_start_date', '>=', $twelveWeeksAgo)
            ->groupBy('year_week')->orderBy('year_week')->pluck('count', 'year_week');

        $weeklyLabels = [];
        $weeklyData = [];
        $currentWeek = $twelveWeeksAgo->copy();
        for ($i = 0; $i < 12; $i++) {
            $yearWeek = $currentWeek->format('oW');
            $weeklyLabels[] = $currentWeek->format('W') . '. Hafta';
            $weeklyData[] = $weeklyPlanCounts[$yearWeek] ?? 0;
            $currentWeek->addWeek();
        }
        $chartData['weekly_plans'] = ['labels' => $weeklyLabels, 'data' => $weeklyData, 'title' => '📅 Son 12 Haftanın Plan Sayısı'];
        $chartData['placeholder'] = ['labels' => [], 'data' => [], 'title' => 'Başka Grafik Gelecek'];

        return ['events' => $events, 'chartData' => $chartData, 'statsTitle' => $statsTitle];
    }

    private function getHizmetIndexData($user)
    {
        $events = [];

        $serviceEvents = Event::with('user')->get();
        foreach ($serviceEvents as $event) {
            $databaseEventType = $event->event_type ?? 'diger';

            $finalEventType = 'service_event';
            if ($databaseEventType === 'diger') {
                $finalEventType = 'general';
            }
            $events[] = [
                'title' => 'Etkinlik: ' . $event->title,
                'start' => $event->start_datetime->format('Y-m-d\TH:i:s'),
                'end' => $event->end_datetime->format('Y-m-d\TH:i:s'),
                'color' => '#F093FB',
                'extendedProps' => [
                    'eventType' => $finalEventType,
                    'model_type' => 'event',
                    'is_important' => $event->is_important,
                    'title' => '🎉 Etkinlik Detayı: ' . $event->title,
                    'id' => $event->id,
                    'user_id' => $event->user_id,
                    'editUrl' => route('service.events.edit', $event->id),
                    'deleteUrl' => route('service.events.destroy', $event->id),
                    'details' => [
                        'Etkinlik Tipi' => $this->getEventTypes()[$event->event_type] ?? ucfirst($event->event_type), // getEventTypes() kullan
                        'Konum' => $event->location,
                        'Başlangıç' => $event->start_datetime->format('d.m.Y H:i'),
                        'Bitiş' => $event->end_datetime->format('d.m.Y H:i'),
                        'Açıklama' => $event->description,
                        'Kayıt Yapan' => $event->user?->name,
                    ]
                ]
            ];
        }

        $assignments = VehicleAssignment::with(['vehicle', 'createdBy'])->get();
        foreach ($assignments as $assignment) {
            $extendedProps = [
                'eventType' => 'vehicle_assignment',
                'model_type' => 'vehicle_assignment',
                'is_important' => $assignment->is_important,
                'title' => '🚗 Araç Atama Detayı',
                'id' => $assignment->id,
                'editUrl' => Gate::allows('manage-assignment', $assignment) ? route('service.assignments.edit', $assignment->id) : null,
                'details' => [
                    'Araç' => $assignment->vehicle?->plate_number . ' (' . $assignment->vehicle?->type . ')',
                    'Görev' => $assignment->task_description,
                    'Yer' => $assignment->destination,
                    'Talep Eden' => $assignment->requester_name,
                    'Başlangıç' => $assignment->start_time->format('d.m.Y H:i'),
                    'Bitiş' => $assignment->end_time->format('d.m.Y H:i'),
                    'Notlar' => $assignment->notes,
                    'Kayıt Yapan' => $assignment->createdBy?->name,
                ]
            ];
            if (Gate::allows('manage-assignment', $assignment)) {
                $extendedProps['deleteUrl'] = route('service.assignments.destroy', $assignment->id);
            }
            $events[] = [
                'title' => 'Araç (' . ($assignment->vehicle?->plate_number ?? '?') . '): ' . $assignment->task_description, // DÜZELTME: getVehiclePlate yerine null-safe operatör
                'start' => $assignment->start_time->format('Y-m-d\TH:i:s'),
                'end' => $assignment->end_time->format('Y-m-d\TH:i:s'),
                'color' => '#FBD38D',
                'extendedProps' => $extendedProps
            ];
        }

        $travels = Travel::with('user')->get();
        foreach ($travels as $travel) {
            $period = CarbonPeriod::create($travel->start_date, $travel->end_date);

            foreach ($period as $date) {
                $extendedProps = [
                    'eventType' => 'travel',
                    'model_type' => 'travel',
                    'is_important' => $travel->is_important,
                    'title' => '✈️ Seyahat Detayı: ' . $travel->name,
                    'id' => $travel->id,
                    'user_id' => $travel->user_id,
                    'url' => route('travels.show', $travel),
                    'editUrl' => (Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager')) ? route('travels.edit', $travel) : null,
                    'deleteUrl' => (Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager')) ? route('travels.destroy', $travel) : null,
                    'details' => [
                        'Plan Adı' => $travel->name,
                        'Oluşturan' => $travel->user?->name,
                        'Başlangıç' => $travel->start_date->format('d.m.Y'),
                        'Bitiş' => $travel->end_date->format('d.m.Y'),
                        'Durum' => $travel->status == 'planned' ? 'Planlandı' : 'Tamamlandı',
                    ]
                ];

                $events[] = [
                    'title' => '✈️ Seyahat: ' . $travel->name,
                    'start' => $date->toDateString(),
                    'allDay' => true,
                    'color' => '#A78BFA',
                    'extendedProps' => $extendedProps
                ];
            }
        }

        $chartData = [];
        $statsTitle = "İdari İşler İstatistikleri";
        $thirtyDaysAgo = Carbon::now()->subDays(29)->startOfDay();
        $dailyEventCounts = Event::select([DB::raw('DATE(start_datetime) as date'), DB::raw('COUNT(*) as count')])
            ->where('start_datetime', '>=', $thirtyDaysAgo)
            ->groupBy('date')->orderBy('date')->pluck('count', 'date');
        $dailyLabels = [];
        $dailyEventData = [];
        $currentDay = $thirtyDaysAgo->copy();
        for ($i = 0; $i < 30; $i++) {
            $dateStr = $currentDay->toDateString();
            $dailyLabels[] = $currentDay->format('d M');
            $dailyEventData[] = $dailyEventCounts[$dateStr] ?? 0;
            $currentDay->addDay();
        }
        $chartData['daily_events'] = ['labels' => $dailyLabels, 'data' => $dailyEventData, 'title' => '📅 Son 30 Günlük Etkinlik Sayısı'];


        $dailyAssignmentCounts = VehicleAssignment::select([DB::raw('DATE(start_time) as date'), DB::raw('COUNT(*) as count')])
            ->where('start_time', '>=', $thirtyDaysAgo)
            ->groupBy('date')->orderBy('date')->pluck('count', 'date');
        $dailyAssignmentData = [];
        $currentDay = $thirtyDaysAgo->copy();
        for ($i = 0; $i < 30; $i++) {
            $dateStr = $currentDay->toDateString();
            $dailyAssignmentData[] = $dailyAssignmentCounts[$dateStr] ?? 0;
            $currentDay->addDay();
        }
        $chartData['daily_assignments'] = ['labels' => $dailyLabels, 'data' => $dailyAssignmentData, 'title' => '🚗 Son 30 Günlük Araç Atama Sayısı'];

        return ['events' => $events, 'chartData' => $chartData, 'statsTitle' => $statsTitle];
    }

    private function getLojistikStatsData($startDate, $endDate): StatisticsData
    {
        $chartData = [];
        $shipmentQuery = Shipment::whereNotNull('cikis_tarihi')
            ->whereBetween('cikis_tarihi', [$startDate, $endDate]);


        $hourlyLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));
        $hourlyCounts = array_fill_keys(range(0, 23), 0);
        $hourlyDbData = (clone $shipmentQuery)->select([DB::raw('HOUR(cikis_tarihi) as hour'), DB::raw('COUNT(*) as count')])->groupBy('hour')->pluck('count', 'hour');
        foreach ($hourlyDbData as $hour => $count) {
            if (isset($hourlyCounts[$hour])) {
                $hourlyCounts[$hour] = $count;
            }
        }
        $chartData['hourly'] = ['labels' => $hourlyLabels, 'data' => array_values($hourlyCounts), 'title' => '⏰ Saatlik Sevkiyat Yoğunluğu'];

        $dayLabels = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
        $dayCounts = array_fill(0, 7, 0);
        $dayMap = [2 => 0, 3 => 1, 4 => 2, 5 => 3, 6 => 4, 7 => 5, 1 => 6];
        $dailyDbData = (clone $shipmentQuery)->select([DB::raw('DAYOFWEEK(cikis_tarihi) as day_of_week'), DB::raw('COUNT(*) as count')])->groupBy('day_of_week')->pluck('count', 'day_of_week');
        foreach ($dailyDbData as $dayNum => $count) {
            if (isset($dayMap[$dayNum])) {
                $dayCounts[$dayMap[$dayNum]] = $count;
            }
        }
        $chartData['daily'] = ['labels' => $dayLabels, 'data' => $dayCounts, 'title' => '📅 Haftalık Sevkiyat Yoğunluğu'];

        $monthLabels = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
        $monthCounts = array_fill(0, 12, 0);
        $monthlyDbData = (clone $shipmentQuery)->select([DB::raw('MONTH(cikis_tarihi) as month'), DB::raw('COUNT(*) as count')])->groupBy('month')->pluck('count', 'month');
        foreach ($monthlyDbData as $monthNum => $count) {
            if ($monthNum >= 1 && $monthNum <= 12) {
                $monthCounts[$monthNum - 1] = $count;
            }
        }
        $chartData['monthly'] = ['labels' => $monthLabels, 'data' => $monthCounts, 'title' => 'Aylık Sevkiyat Dağılımı (' . $startDate->format('d M') . ' - ' . $endDate->format('d M Y') . ')'];

        $yearlyDbData = (clone $shipmentQuery)->select([DB::raw('YEAR(cikis_tarihi) as year'), DB::raw('COUNT(*) as count')])->groupBy('year')->orderBy('year')->pluck('count', 'year');
        $chartData['yearly'] = ['labels' => $yearlyDbData->keys()->map(fn($y) => (string) $y)->all(), 'data' => $yearlyDbData->values()->all(), 'title' => 'Yıllara Göre Toplam Sevkiyat Sayısı'];

        $vehicleTypeData = (clone $shipmentQuery)->select(['arac_tipi', DB::raw('COUNT(*) as count')])->whereNotNull('arac_tipi')->groupBy('arac_tipi')->get()
            ->groupBy(fn($item) => $this->normalizeVehicleType($item->arac_tipi))
            ->map(fn($group) => $group->sum('count'));
        $chartData['pie'] = ['labels' => $vehicleTypeData->keys()->map(fn($tip) => $tip ?? 'Bilinmiyor')->all(), 'data' => $vehicleTypeData->values()->all(), 'title' => 'Araç Tipi Dağılımı (Genel)'];

        $shipmentsForFiltering = Shipment::select(['arac_tipi', 'kargo_icerigi', 'shipment_type'])
            ->whereNotNull('cikis_tarihi')
            ->whereBetween('cikis_tarihi', [$startDate, $endDate]) // Ana tarih filtresi uygulanmış olarak
            ->get()
            ->map(function ($shipment) {
                return [
                    'vehicle' => $this->normalizeVehicleType($shipment->arac_tipi ?? 'Bilinmiyor'),
                    'cargo' => $this->normalizeCargoContent($shipment->kargo_icerigi ?? 'Bilinmiyor'),
                    'shipment_type' => $shipment->shipment_type
                ];
            })
            ->values()->all();

        return new StatisticsData(
            chartData: $chartData,
            shipmentsForFiltering: $shipmentsForFiltering
        );
    }

    private function getUretimStatsData($startDate, $endDate): StatisticsData
    {
        $chartData = [];
        $productionQuery = ProductionPlan::whereBetween('week_start_date', [$startDate, $endDate])
            ->whereNotNull('week_start_date');

        $weeklyPlanCounts = (clone $productionQuery)->select([DB::raw('YEARWEEK(week_start_date, 1) as year_week'), DB::raw('COUNT(*) as count')])
            ->groupBy('year_week')->orderBy('year_week')->pluck('count', 'year_week');
        $weeklyLabels = [];
        $weeklyData = [];
        $currentWeek = $startDate->copy()->startOfWeek();
        while ($currentWeek->lte($endDate)) {
            $yearWeek = $currentWeek->format('oW');
            $weeklyLabels[] = $currentWeek->format('W') . '. Hafta';
            $weeklyData[] = $weeklyPlanCounts[$yearWeek] ?? 0;
            $currentWeek->addWeek();
        }
        $chartData['weekly_prod'] = ['labels' => $weeklyLabels, 'data' => $weeklyData, 'title' => '📅 Haftalık Üretim Planı Sayısı'];

        $monthlyPlanCounts = (clone $productionQuery)->select([DB::raw('YEAR(week_start_date) as year'), DB::raw('MONTH(week_start_date) as month'), DB::raw('COUNT(*) as count')])
            ->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();
        $monthlyLabels = [];
        $monthlyData = [];
        $currentMonth = $startDate->copy()->startOfMonth();
        while ($currentMonth->lte($endDate)) {
            $year = $currentMonth->year;
            $month = $currentMonth->month;
            $count = $monthlyPlanCounts->where('year', $year)->where('month', $month)->first()?->count ?? 0;
            $monthlyLabels[] = $currentMonth->translatedFormat('M Y');
            $monthlyData[] = $count;
            $currentMonth->addMonth();
        }
        $chartData['monthly_prod'] = ['labels' => $monthlyLabels, 'data' => $monthlyData, 'title' => '🗓️ Aylık Üretim Planı Sayısı'];

        $allPlansRaw = (clone $productionQuery)->whereNotNull('plan_details')->get(['plan_details']);
        $flatDetails = [];
        foreach ($allPlansRaw as $plan) {
            if (is_array($plan->plan_details)) {
                foreach ($plan->plan_details as $detail) {
                    $machine = trim(strval($detail['machine'] ?? 'Bilinmiyor'));
                    $product = is_numeric($detail['product'] ?? 'Bilinmiyor') ? 'Ürün-' . $detail['product'] : trim(strval($detail['product'] ?? 'Bilinmiyor'));
                    if ($machine !== 'Bilinmiyor' && $product !== 'Bilinmiyor') {
                        $flatDetails[] = [
                            'machine' => $machine,
                            'product' => $product,
                            'quantity' => (int) ($detail['quantity'] ?? 0)
                        ];
                    }
                }
            }
        }
        $productionPlansForFiltering = $flatDetails;

        return new StatisticsData(
            chartData: $chartData,
            productionPlansForFiltering: $productionPlansForFiltering
        );
    }
    private function getHizmetStatsData($startDate, $endDate): StatisticsData
    {
        // YÖNETİCİ FONKSİYON:
        // Artık sadece görev dağıtımı yapıyor.
        $eventTypesList = $this->getEventTypes();

        // 1. Görev: Pasta grafik verisini getir.
        $pieChartData = $this->getHizmetPieChartData($startDate, $endDate, $eventTypesList);

        // 2. Görev: Aylık görev grafiği verisini getir.
        $monthlyAssignmentData = $this->getHizmetMonthlyAssignmentChartData($startDate, $endDate);

        // 3. Görev: Filtreleme için etkinlik verilerini getir.
        $eventsForFiltering = $this->getHizmetEventFilterData($startDate, $endDate, $eventTypesList);

        // 4. Görev: Filtreleme için atama verilerini getir.
        $assignmentsForFiltering = $this->getHizmetAssignmentFilterData($startDate, $endDate);

        // 5. Görev: Filtreleme için araç listesini getir.
        $vehiclesForFiltering = $this->getHizmetVehicleFilterData();

        // Tüm verileri topla
        $chartData = [
            'event_type_pie' => $pieChartData,
            'monthly_assign' => $monthlyAssignmentData['chartData'],
        ];

        // DTO'yu doldur ve döndür
        return new StatisticsData(
            chartData: $chartData,
            eventsForFiltering: $eventsForFiltering,
            assignmentsForFiltering: $assignmentsForFiltering,
            vehiclesForFiltering: $vehiclesForFiltering,
            monthlyLabels: $monthlyAssignmentData['labels'] // Bu veriyi de 2. görevden aldık
        );
    }

    // --- YENİ YARDIMCI FONKSİYONLAR ---
    // (Bu 5 yeni fonksiyonu getHizmetStatsData'nın hemen altına ekleyin)

    /**
     * Hizmet departmanı için "Etkinlik Tipi" pasta grafiği verisini hazırlar.
     */
    private function getHizmetPieChartData($startDate, $endDate, array $eventTypesList): array
    {
        // 1. Etkinlikleri al
        $eventTypeCounts = Event::select(['event_type', DB::raw('COUNT(*) as count')])
            ->whereNotNull('event_type')
            ->whereBetween('start_datetime', [$startDate, $endDate])
            ->groupBy('event_type')->pluck('count', 'event_type')
            ->mapWithKeys(function ($count, $key) use ($eventTypesList) {
                return [$eventTypesList[$key] ?? ucfirst($key) => $count];
            });

        // 2. Seyahatleri al
        $travelCount = Travel::whereBetween('start_date', [$startDate, $endDate])->count();
        if ($travelCount > 0) {
            $eventTypeCounts[' Seyahat Planı'] = $travelCount;
        }

        // 3. Grafik için formatla
        return [
            'labels' => $eventTypeCounts->keys()->all(),
            'data' => $eventTypeCounts->values()->all(),
            'title' => 'Etkinlik ve Seyahat Tipi Dağılımı'
        ];
    }

    /**
     * Hizmet departmanı için "Aylık Araç Atama" çizgi grafiği verisini hazırlar.
     * Hem grafik verisini hem de etiketleri döndürür.
     */
    private function getHizmetMonthlyAssignmentChartData($startDate, $endDate): array
    {
        $monthlyAssignmentCounts = VehicleAssignment::select([DB::raw('YEAR(start_time) as year'), DB::raw('MONTH(start_time) as month'), DB::raw('COUNT(*) as count')])
            ->whereBetween('start_time', [$startDate, $endDate])
            ->whereNotNull('start_time')
            ->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();

        $monthlyLabels = [];
        $monthlyData = [];
        $currentMonth = $startDate->copy()->startOfMonth();

        while ($currentMonth->lte($endDate)) {
            $year = $currentMonth->year;
            $month = $currentMonth->month;
            $count = $monthlyAssignmentCounts->where('year', $year)->where('month', $month)->first()?->count ?? 0;
            $monthlyLabels[] = $currentMonth->translatedFormat('M Y');
            $monthlyData[] = $count;
            $currentMonth->addMonth();
        }

        $chartData = ['labels' => $monthlyLabels, 'data' => $monthlyData, 'title' => '🚗 Aylık Araç Atama Sayısı'];

        return ['chartData' => $chartData, 'labels' => $monthlyLabels];
    }

    /**
     * İstatistik sayfasındaki filtreleme için Etkinlik ve Seyahat verilerini çeker.
     */
    private function getHizmetEventFilterData($startDate, $endDate, array $eventTypesList): array
    {
        $eventsForFiltering = Event::whereBetween('start_datetime', [$startDate, $endDate])
            ->get(['event_type', 'location'])
            ->map(function ($event) use ($eventTypesList) {
                return [
                    'type_name' => $eventTypesList[$event->event_type] ?? ucfirst($event->event_type),
                    'type_slug' => $event->event_type,
                    'group' => 'Etkinlikler',
                ];
            });

        $travelsForFiltering = Travel::whereBetween('start_date', [$startDate, $endDate])
            ->get(['name'])
            ->map(function ($travel) {
                return [
                    'type_name' => ' Seyahat Planı',
                    'type_slug' => 'travel',
                    'group' => 'Seyahatler',
                ];
            });

        return $eventsForFiltering->merge($travelsForFiltering)->all();
    }

    /**
     * İstatistik sayfasındaki filtreleme için Araç Atama verilerini çeker.
     */
    private function getHizmetAssignmentFilterData($startDate, $endDate): array
    {
        return VehicleAssignment::with('vehicle:id,plate_number')
            ->whereBetween('start_time', [$startDate, $endDate])
            ->get(['vehicle_id', 'start_time'])
            ->map(function ($assignment) {
                return [
                    'vehicle_id' => $assignment->vehicle_id,
                    'vehicle_plate' => $assignment->vehicle->plate_number ?? 'Bilinmeyen Araç',
                    'start_month_label' => $assignment->start_time ? $assignment->start_time->translatedFormat('M Y') : null
                ];
            })
            ->filter(fn($a) => $a['start_month_label'] !== null)
            ->all();
    }

    /**
     * İstatistik sayfasındaki filtreleme için tüm araçların listesini çeker.
     */
    private function getHizmetVehicleFilterData(): array
    {
        // Orijinal kodunuzda `->all()` vardı, ben de ekledim.
        // Eğer StatisticsData DTO'su 'Collection' kabul ediyorsa `->all()` kısmını silebilirsiniz.
        return \App\Models\Vehicle::orderBy('plate_number')->get(['id', 'plate_number'])->all();
    }


    private function getMappedImportantItems(Request $request)
    {
        $user = Auth::user();
        $typeFilter = $request->input('type', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Varsayılan departman filtresi (Admin için null/all, bireysel için ID)
        $deptFilter = $request->input('department_id', null);

        // Admin veya Global Yönetici değilse VE bir departmana bağlıysa, 
        // filtreyi kendi departman ID'sine zorla.
        $isUserFiltered = false;
        if ($user->department_id && $user->role !== 'admin' && !Auth::user()->can('is-global-manager')) {
            $deptFilter = $user->department_id; // Departman ID'si ile filtrelemeye zorla
            $isUserFiltered = true;
        }
        // Admin/Global Yönetici ise, $deptFilter null/all kalır ve $isUserFiltered false kalır.

        $allMappedItems = collect();

        // --- SHIPMENT (Sevkiyat) ---
        if ($typeFilter == 'all' || $typeFilter == 'shipment') {
            $query = Shipment::where('is_important', true);
            if ($dateFrom)
                $query->where('tahmini_varis_tarihi', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('tahmini_varis_tarihi', '<=', Carbon::parse($dateTo)->endOfDay());

            // KRİTİK FİLTRE: Sadece bireysel kullanıcı departman filtresine zorlanırsa çalıştır.
            if ($isUserFiltered) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));
            }

            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object) [
                        'title' => 'Sevkiyat: ' . ($item->kargo_icerigi ?? 'Detay Yok'),
                        'date' => $item->tahmini_varis_tarihi,
                        'model_id' => $item->id,
                        'model_type' => 'shipment'
                    ];
                })
            );
        }

        // --- PRODUCTION PLAN (Üretim Planı) ---
        if ($typeFilter == 'all' || $typeFilter == 'production_plan') {
            $query = ProductionPlan::where('is_important', true);
            if ($dateFrom)
                $query->where('week_start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('week_start_date', '<=', Carbon::parse($dateTo)->endOfDay());

            if ($isUserFiltered) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));
            }

            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object) [
                        'title' => 'Üretim: ' . $item->plan_title,
                        'date' => $item->week_start_date,
                        'model_id' => $item->id,
                        'model_type' => 'production_plan'
                    ];
                })
            );
        }

        // --- EVENT (Etkinlik) ---
        if ($typeFilter == 'all' || $typeFilter == 'event') {
            $query = Event::where('is_important', true);
            if ($dateFrom)
                $query->where('start_datetime', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_datetime', '<=', Carbon::parse($dateTo)->endOfDay());

            if ($isUserFiltered) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));
            }

            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object) [
                        'title' => 'Etkinlik: ' . $item->title,
                        'date' => $item->start_datetime,
                        'model_id' => $item->id,
                        'model_type' => 'event'
                    ];
                })
            );
        }

        // --- VEHICLE ASSIGNMENT (Araç Görevi) ---
        if ($typeFilter == 'all' || $typeFilter == 'vehicle_assignment') {
            $query = VehicleAssignment::where('is_important', true);
            if ($dateFrom)
                $query->where('start_time', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_time', '<=', Carbon::parse($dateTo)->endOfDay());

            if ($isUserFiltered) {
                $query->whereHas('createdBy', fn($q) => $q->where('department_id', $deptFilter));
            }

            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object) [
                        'title' => 'Araç Görevi: ' . Str::limit($item->task_description, 30),
                        'date' => $item->start_time,
                        'model_id' => $item->id,
                        'model_type' => 'vehicle_assignment'
                    ];
                })
            );
        }

        // --- TRAVEL (Seyahat) ---
        if ($typeFilter == 'all' || $typeFilter == 'travel') {
            $query = Travel::where('is_important', true);

            if ($dateFrom)
                $query->where('start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_date', '<=', Carbon::parse($dateTo)->endOfDay());

            if ($isUserFiltered) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));
            }

            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object) [
                        'title' => '✈️ Seyahat: ' . Str::limit($item->name, 30),
                        'date' => $item->start_date,
                        'model_id' => $item->id,
                        'model_type' => 'travel'
                    ];
                })
            );
        }
        $overdueQuery = VehicleAssignment::where('start_time', '<', Carbon::today())
            ->whereIn('status', ['pending', 'in_progress']);

        // Admin değilse sadece kendi departmanının/kendisinin gecikenlerini görsün
        if ($isUserFiltered) {
            $overdueQuery->whereHas('createdBy', fn($q) => $q->where('department_id', $deptFilter));
        }

        $overdueItems = $overdueQuery->get()->map(function ($item) {
            return (object) [
                // Başlığa dikkat çekici bir ikon ekliyoruz
                'title' => '⚠️ TAMAMLANMAYAN/GECİKEN GÖREV: ' . Str::limit($item->task_description, 40),
                'date' => $item->start_time,
                'model_id' => $item->id,
                'model_type' => 'vehicle_assignment',
                // İsterseniz view tarafında ayırt etmek için bir flag ekleyebilirsiniz
                'is_overdue' => true
            ];
        });

        // Mevcut önemli öğeler listesine gecikenleri de ekle
        $allMappedItems = $allMappedItems->merge($overdueItems);

        return $allMappedItems->sortByDesc('date');
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
    private function getLogisticsWelcomeData()
    {
        $welcomeTitle = "Yaklaşan Sevkiyatlar (Genel Bakış)";
        $chartTitle = "Kargo İçeriği -> Araç Tipi Akışı (Tüm Zamanlar)";
        $chartData = [];

        $todayItems = Shipment::whereBetween('tahmini_varis_tarihi', [
            Carbon::today()->startOfDay(),
            Carbon::today()->addDays(3)->endOfDay()
        ])
            ->orderBy('tahmini_varis_tarihi', 'asc')
            ->get();
        $sankeyFlow = Shipment::select(['kargo_icerigi', 'arac_tipi', DB::raw('COUNT(*) as weight')])
            ->whereNotNull('kargo_icerigi')
            ->whereNotNull('arac_tipi')
            ->groupBy('kargo_icerigi', 'arac_tipi')
            ->having('weight', '>', 0)
            ->get();

        foreach ($sankeyFlow as $flow) {
            $normalizedKargo = $this->normalizeCargoContent($flow->kargo_icerigi);
            $normalizedArac = $this->normalizeVehicleType($flow->arac_tipi);
            $chartData[] = [
                strval($normalizedKargo),
                strval($normalizedArac),
                (int) $flow->weight
            ];
        }

        if (empty($chartData)) {
            Log::warning('Lojistik/Genel görünüm için Sankey verisi bulunamadı.');
            $chartData[] = ['Veri Yok', 'Henüz Sevkiyat Girilmedi', 1];
        }

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getProductionWelcomeData()
    {
        $welcomeTitle = "Bugün Başlayan Üretim Planları";
        $chartTitle = "Makine -> Ürün Planlama Akışı (Toplam Adet)";
        $chartData = [];

        $todayItems = ProductionPlan::whereBetween('week_start_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
            ->orderBy('week_start_date', 'asc')
            ->get();

        $plans = ProductionPlan::whereNotNull('plan_details')->get();
        $flowCounts = [];

        foreach ($plans as $plan) {
            if (is_array($plan->plan_details)) {
                foreach ($plan->plan_details as $detail) {
                    $machine = trim(strval($detail['machine'] ?? 'Bilinmiyor'));
                    $productRaw = $detail['product'] ?? 'Bilinmiyor';

                    if (is_numeric($productRaw)) {
                        $product = 'Ürün-' . $productRaw;
                    } else {
                        $product = trim(strval($productRaw));
                    }
                    $quantity = (int) ($detail['quantity'] ?? 0);

                    if (empty($machine) || empty($product) || $machine === 'Bilinmiyor' || $product === 'Bilinmiyor' || $quantity === 0) {
                        continue;
                    }

                    if (!isset($flowCounts[$machine]))
                        $flowCounts[$machine] = [];
                    if (!isset($flowCounts[$machine][$product]))
                        $flowCounts[$machine][$product] = 0;

                    $flowCounts[$machine][$product] += $quantity;
                }
            }
        }

        foreach ($flowCounts as $machine => $products) {
            foreach ($products as $product => $weight) {
                if ($weight > 0) {
                    $chartData[] = [
                        strval($machine),
                        strval($product),
                        (int) $weight
                    ];
                }
            }
        }

        if (empty($chartData)) {
            Log::warning('Üretim departmanı için Sankey verisi bulunamadı.');
            $chartData[] = ['Veri Yok', 'Henüz Plan Girilmedi', 1];
        }

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getServiceWelcomeData()
    {
        $welcomeTitle = "Bugünkü Etkinlikler ve Araç Görevleri";
        $chartTitle = "Araç -> Görev Yeri Akışı (Toplam Görev Sayısı)";
        $chartData = [];

        $todayEvents = Event::whereDate('start_datetime', Carbon::today())
            ->orderBy('start_datetime', 'asc')
            ->get();
        $todayAssignments = VehicleAssignment::whereDate('start_time', Carbon::today())
            ->with('vehicle')
            ->orderBy('start_time', 'asc')
            ->get();
        $todayTravels = Travel::whereDate('start_date', Carbon::today())
            ->orderBy('start_date', 'asc')
            ->get();
        $todayItems = $todayEvents->merge($todayAssignments)
            ->merge($todayTravels)
            ->sortBy(fn($item) => $item->start_datetime ?? $item->start_time ?? $item->start_date);

        $assignments = VehicleAssignment::with('vehicle')
            ->whereNotNull('destination')
            ->where('destination', '!=', '')
            ->select(['vehicle_id', 'destination', DB::raw('COUNT(*) as weight')])
            ->groupBy('vehicle_id', 'destination')
            ->having('weight', '>', 0)
            ->get();

        foreach ($assignments as $flow) {
            $vehicleName = $flow->vehicle?->plate_number ?? 'Bilinmeyen Araç';
            $destination = trim($flow->destination);
            if (!empty($destination) && $flow->weight > 0) {
                $chartData[] = [
                    strval($vehicleName),
                    strval($destination),
                    (int) $flow->weight
                ];
            }
        }

        if (empty($chartData)) {
            $chartTitle = "Etkinlik Tipi -> Konum Akışı (Tüm Zamanlar)";
            $eventFlows = Event::whereNotNull('location')
                ->where('location', '!=', '')
                ->select(['event_type', 'location', DB::raw('COUNT(*) as weight')])
                ->groupBy('event_type', 'location')
                ->having('weight', '>', 0)
                ->get();

            $eventTypesList = $this->getEventTypes();

            foreach ($eventFlows as $flow) {
                $eventType = $eventTypesList[$flow->event_type] ?? ucfirst($flow->event_type);
                $location = trim($flow->location);
                if (!empty($location) && $flow->weight > 0) {
                    $chartData[] = [
                        strval($eventType),
                        strval($location),
                        (int) $flow->weight
                    ];
                }
            }
        }

        if (empty($chartData)) {
            Log::warning('Hizmet departmanı için Sankey verisi bulunamadı.');
            $chartData[] = ['Veri Yok', 'Henüz Görev Girilmedi', 1];
        }

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function normalizeCargoContent($cargo)
    {
        if (empty($cargo)) {
            return 'Bilinmiyor';
        }
        $normalized = mb_strtoupper(trim($cargo), 'UTF-8');
        $normalized = Str::ascii($normalized);
        $specialCases = [
            'LEVBA' => 'LEVHA',
            'LEVBE' => 'LEVHA',
            'PLASTIC' => 'PLASTİK',
            'KAPAK' => 'KAPAK',
            'PLASTİK' => 'PLASTİK',
            'LEVHA' => 'LEVHA',
        ];
        return $specialCases[$normalized] ?? $normalized;
    }

    private function normalizeVehicleType($vehicle)
    {
        if (empty($vehicle)) {
            return 'Bilinmiyor';
        }
        $normalized = mb_strtoupper(trim($vehicle), 'UTF-8');
        $vehicleMapping = [
            'TIR' => 'TIR',
            'TİR' => 'TIR',
            'TRUCK' => 'TIR',
            'GEMI' => 'GEMI',
            'GEMİ' => 'GEMI',
            'SHIP' => 'GEMI',
            'KAMYON' => 'KAMYON',
            'TRUCK_SMALL' => 'KAMYON',
            'KAMYONET' => 'KAMYON',
        ];
        return $vehicleMapping[$normalized] ?? $normalized;
    }

    public function getEventTypes()
    {
        return [
            'toplanti' => 'Toplantı',
            'egitim' => 'Eğitim',
            'fuar' => 'Fuar',
            'gezi' => 'Gezi',
            'musteri_ziyareti' => 'Müşteri Ziyareti',
            'misafir_karsilama' => 'Misafir Karşılama',
            'diger' => 'Diğer',
        ];
    }

    private function getEventColor($aracTipi)
    {
        switch (strtolower($aracTipi)) {
            case 'tır':
                return '#0d6efd';
            case 'gemi':
                return '#198754';
            case 'kamyon':
                return '#fd7e14';
            default:
                return '#6c757d';
        }
    }

    private function getVehiclePlate($assignment)
    {
        if (!$assignment->relationLoaded('vehicle')) {
            $assignment->load('vehicle');
        }
        return $assignment->vehicle?->plate_number;
    }

}