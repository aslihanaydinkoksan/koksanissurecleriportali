<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Gate;
use App\Models\Department;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * ===================================================================
     * /home (Takvim) SAYFASI KONTROLÜ
     * ===================================================================
     */
    public function index(Request $request)
    {
        // --- Departman Bilgisi ---
        $user = Auth::user();
        $departmentSlug = $user->department ? trim($user->department->slug) : null;
        $departmentName = $user->department?->name ?? 'Genel';

        $events = [];
        $now = Carbon::now();

        // Lojistik Departmanı
        if ($departmentSlug === 'lojistik') {
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

                $color = '#0d6efd'; // Default Mavi
                if ($shipment->onaylanma_tarihi) {
                    $color = '#198754';
                } // Onaylandı (Yeşil)
                elseif ($varisTarihi) {
                    if ($now->greaterThan($varisTarihi)) {
                        $color = '#dc3545';
                    } // Gecikti (Kırmızı)
                    elseif ($varisTarihi->isBetween($now, $now->copy()->addDays(3))) {
                        $color = '#ffc107';
                    } // Yaklaşıyor (Sarı)
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
        }
        // Üretim Departmanı
        elseif ($departmentSlug === 'uretim') {
            $plans = ProductionPlan::with('user')->get();
            foreach ($plans as $plan) {
                $events[] = [
                    'title' => 'Üretim: ' . $plan->plan_title,
                    'model_type' => 'production_plan',
                    'is_important' => $plan->is_important,
                    'start' => $plan->week_start_date->startOfDay()->toIso8601String(),
                    'end'   => $plan->week_start_date->copy()->addDay()->startOfDay()->toIso8601String(),
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
        }
        // Hizmet Departmanı
        elseif ($departmentSlug === 'hizmet') {
            // Hizmet: Etkinlikler
            $serviceEvents = Event::with('user')->get();
            foreach ($serviceEvents as $event) {
                $events[] = [
                    'title' => 'Etkinlik: ' . $event->title,
                    'start' => $event->start_datetime->toIso8601String(),
                    'end'   => $event->end_datetime->toIso8601String(),
                    'color' => '#F093FB', // Hizmet Etkinlik rengi
                    'extendedProps' => [
                        'eventType' => 'service_event',
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
            // Hizmet: Araç Atamaları
            $assignments = VehicleAssignment::with(['vehicle', 'user'])->get();
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
                        'Kayıt Yapan' => $assignment->user?->name,
                    ]
                ];
                if (Gate::allows('manage-assignment', $assignment)) {
                    $extendedProps['deleteUrl'] = route('service.assignments.destroy', $assignment->id);
                }
                $events[] = [
                    'title' => 'Araç (' . ($assignment->vehicle->plate_number ?? '?') . '): ' . $assignment->task_description,
                    'start' => $assignment->start_time->toIso8601String(),
                    'end' => $assignment->end_time->toIso8601String(),
                    'color' => '#FBD38D', // Hizmet Araç rengi
                    'extendedProps' => $extendedProps
                ];
            }
        }

        // --- Departmana Özel İstatistik Verileri (Bu bölümde değişiklik yok) ---
        $chartData = [];
        $statsTitle = $departmentName . " İstatistikleri";
        if ($departmentSlug === 'lojistik') {
            $statsTitle = "Sevkiyat İstatistikleri";
            $hourlyLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));
            $hourlyCounts = array_fill_keys(range(0, 23), 0);
            $hourlyDbData = Shipment::select(DB::raw('HOUR(cikis_tarihi) as hour'), DB::raw('COUNT(*) as count'))
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
            $dailyDbData = Shipment::select(DB::raw('DAYOFWEEK(cikis_tarihi) as day_of_week'), DB::raw('COUNT(*) as count'))
                ->groupBy('day_of_week')->pluck('count', 'day_of_week');
            foreach ($dailyDbData as $dayNum => $count) {
                if (isset($dayMap[$dayNum])) {
                    $dayCounts[$dayMap[$dayNum]] = $count;
                }
            }
            $chartData['daily'] = ['labels' => $dayLabels, 'data' => $dayCounts, 'title' => '📅 Haftalık Sevkiyat Yoğunluğu'];
        } elseif ($departmentSlug === 'uretim') {
            $twelveWeeksAgo = Carbon::now()->subWeeks(11)->startOfWeek();
            $weeklyPlanCounts = ProductionPlan::select(DB::raw('YEARWEEK(week_start_date, 1) as year_week'), DB::raw('COUNT(*) as count'))
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
        } elseif ($departmentSlug === 'hizmet') {
            $thirtyDaysAgo = Carbon::now()->subDays(29)->startOfDay();
            $dailyEventCounts = Event::select(DB::raw('DATE(start_datetime) as date'), DB::raw('COUNT(*) as count'))
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

            $dailyAssignmentCounts = VehicleAssignment::select(DB::raw('DATE(start_time) as date'), DB::raw('COUNT(*) as count'))
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
        }

        // --- Kullanıcı Listesi ---
        $users = collect();
        if (in_array($user->role, ['admin', 'yönetici'])) {
            $users = User::with('department')->orderBy('name')->get();
        }

        return view('home', compact(
            'events',
            'users',
            'departmentName',
            'departmentSlug',
            'statsTitle',
            'chartData'
        ));
    }

    public function welcome(Request $request)
    {
        $user = Auth::user();
        $allItems = $this->getMappedImportantItems($request); // Önemli bildirimleri al
        $importantItems = $allItems->take(4);
        $importantItemsCount = $allItems->count();

        // 1. Kullanıcının departmanını ve rolünü al
        $departmentSlug = $user->department ? trim($user->department->slug) : null;
        $userRole = $user->role;

        // 2. Değişkenleri varsayılan olarak ata
        $welcomeTitle = "Hoş Geldiniz";
        $todayItems = collect();
        $chartTitle = "";
        $chartData = [];
        $kpiData = []; // YENİ: KPI kartları için boş dizi

        // 3. Hangi verinin gösterileceğine karar ver

        // Kural 1, 2, 3: Departmanı OLAN Yöneticiler
        if ($departmentSlug === 'uretim') {
            list($welcomeTitle, $chartTitle, $todayItems, $chartData) = $this->getProductionWelcomeData();
        } elseif ($departmentSlug === 'hizmet') {
            list($welcomeTitle, $chartTitle, $todayItems, $chartData) = $this->getServiceWelcomeData();
        } elseif ($departmentSlug === 'lojistik') {
            list($welcomeTitle, $chartTitle, $todayItems, $chartData) = $this->getLogisticsWelcomeData();
        }
        // Kural 4 & 5: Admin VEYA Departmansız Yönetici
        elseif ($userRole == 'admin' || (empty($departmentSlug) && $userRole == 'yönetici')) {
            $welcomeTitle = "Genel Bakış"; // Başlığı değiştir

            // Fikir 1: KPI Kartları için verileri hazırla
            $kpiData = [
                'sevkiyat_sayisi' => \App\Models\Shipment::whereDate('tahmini_varis_tarihi', Carbon::today())->count(),
                'plan_sayisi' => \App\Models\ProductionPlan::whereDate('week_start_date', Carbon::today())->count(),
                'gorev_sayisi' => \App\Models\Event::whereDate('start_datetime', Carbon::today())->count() + \App\Models\VehicleAssignment::whereDate('start_time', Carbon::today())->count(),
                'kullanici_sayisi' => \App\Models\User::count()
            ];

            // Fikir 2: Genel Sankey verisini hazırla
            $chartTitle = "Şirket Geneli İş Akışı (Toplam Kayıt)";

            $lojistikCount = (int)\App\Models\Shipment::count();
            $uretimCount = (int)\App\Models\ProductionPlan::count();
            $etkinlikCount = (int)\App\Models\Event::count();
            $aracCount = (int)\App\Models\VehicleAssignment::count();

            $chartData = [];
            if ($lojistikCount > 0) $chartData[] = ['Lojistik', 'Sevkiyatlar', $lojistikCount];
            if ($uretimCount > 0) $chartData[] = ['Üretim', 'Planlar', $uretimCount];
            if ($etkinlikCount > 0) $chartData[] = ['Hizmet', 'Etkinlikler', $etkinlikCount];
            if ($aracCount > 0) $chartData[] = ['Hizmet', 'Araç Görevleri', $aracCount];

            // Eğer TÜM veriler sıfırsa, placeholder ekle
            if (empty($chartData)) {
                $chartData[] = ['Sistem', 'Henüz Kayıt Yok', 1];
            }
        }
        // 'else' (diğer departmansız kullanıcılar) için her şey boş kalacak.

        Log::info('Welcome sayfası yükleniyor (NİHAİ + KPI)', [
            'user_id' => $user->id,
            'department_slug' => $departmentSlug,
            'role' => $userRole,
            'todayItems_count' => $todayItems->count(),
            'chartData_count' => count($chartData),
            'kpiData_count' => count($kpiData)
        ]);

        $chartType = 'sankey';

        // 5. View'a gönder
        return view('welcome', compact(
            'importantItems',
            'importantItemsCount',
            'welcomeTitle',
            'todayItems',
            'chartType',
            'chartData',
            'chartTitle',
            'departmentSlug',
            'kpiData' // YENİ: KPI verisini View'a gönder
        ));
    }


    /**
     * Lojistik veya Varsayılan (Admin) görünümü için verileri alır.
     */
    private function getLogisticsWelcomeData()
    {
        $welcomeTitle = "Bugün Yaklaşan Sevkiyatlar (Genel Bakış)";
        $chartTitle = "Kargo İçeriği -> Araç Tipi Akışı (Tüm Zamanlar)";
        $chartData = [];

        $todayItems = Shipment::whereDate('tahmini_varis_tarihi', Carbon::today())
            ->orderBy('tahmini_varis_tarihi', 'asc')
            ->get();

        $sankeyFlow = Shipment::select('kargo_icerigi', 'arac_tipi', DB::raw('COUNT(*) as weight'))
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
                (int)$flow->weight
            ];
        }

        if (empty($chartData)) {
            Log::warning('Lojistik/Genel görünüm için Sankey verisi bulunamadı.');
            $chartData[] = ['Veri Yok', 'Henüz Sevkiyat Girilmedi', 1];
        }

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    /**
     * Üretim görünümü için verileri alır.
     */
    private function getProductionWelcomeData()
    {
        $welcomeTitle = "Bugün Başlayan Üretim Planları";
        $chartTitle = "Makine -> Ürün Planlama Akışı (Toplam Adet)";
        $chartData = [];

        $todayItems = ProductionPlan::whereDate('week_start_date', Carbon::today())
            ->orderBy('created_at', 'asc')
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
                    $quantity = (int)($detail['quantity'] ?? 0);

                    if (empty($machine) || empty($product) || $machine === 'Bilinmiyor' || $product === 'Bilinmiyor' || $quantity === 0) {
                        continue;
                    }

                    if (!isset($flowCounts[$machine])) $flowCounts[$machine] = [];
                    if (!isset($flowCounts[$machine][$product])) $flowCounts[$machine][$product] = 0;

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
                        (int)$weight
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

    /**
     * Hizmet görünümü için verileri alır.
     */
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
        $todayItems = $todayEvents->merge($todayAssignments)
            ->sortBy(fn($item) => $item->start_datetime ?? $item->start_time);

        // 1. Araç Atama Grafiğini dene
        $assignments = VehicleAssignment::with('vehicle')
            ->whereNotNull('destination')
            ->where('destination', '!=', '')
            ->select('vehicle_id', 'destination', DB::raw('COUNT(*) as weight'))
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
                    (int)$flow->weight
                ];
            }
        }

        // 2. Eğer Araç verisi yoksa, Etkinlik Grafiğini dene
        if (empty($chartData)) {
            $chartTitle = "Etkinlik Tipi -> Konum Akışı (Tüm Zamanlar)";
            $eventFlows = Event::whereNotNull('location')
                ->where('location', '!=', '')
                ->select('event_type', 'location', DB::raw('COUNT(*) as weight'))
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
                        (int)$flow->weight
                    ];
                }
            }
        }

        // 3. Hala veri yoksa
        if (empty($chartData)) {
            Log::warning('Hizmet departmanı için Sankey verisi bulunamadı.');
            $chartData[] = ['Veri Yok', 'Henüz Görev Girilmedi', 1];
        }

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }


    /**
     * ===================================================================
     * İSTATİSTİK SAYFASI (Değişiklik yok)
     * ===================================================================
     */
    public function showStatistics(Request $request)
    {
        $user = Auth::user();
        $departmentSlug = $user->department ? trim($user->department->slug) : null;
        $departmentName = $user->department?->name ?? 'Genel';
        $pageTitle = $departmentName . " İstatistikleri";

        $chartData = [];
        $shipmentsForFiltering = [];
        $availableYears = [];

        // --- Departmana Göre İstatistik Hesaplama ---
        if ($departmentSlug === 'lojistik') {
            $pageTitle = "Ayrıntılı Sevkiyat İstatistikleri";
            $hourlyLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));
            $hourlyCounts = array_fill_keys(range(0, 23), 0);
            $hourlyDbData = Shipment::select(DB::raw('HOUR(cikis_tarihi) as hour'), DB::raw('COUNT(*) as count'))
                ->whereNotNull('cikis_tarihi')
                ->groupBy('hour')->pluck('count', 'hour');
            foreach ($hourlyDbData as $hour => $count) {
                if (isset($hourlyCounts[$hour])) {
                    $hourlyCounts[$hour] = $count;
                }
            }
            $chartData['hourly'] = [
                'labels' => $hourlyLabels,
                'data' => array_values($hourlyCounts),
                'title' => '⏰ Saatlik Sevkiyat Yoğunluğu (Tüm Zamanlar)'
            ];
            $dayLabels = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
            $dayCounts = array_fill(0, 7, 0);
            $dayMap = [2 => 0, 3 => 1, 4 => 2, 5 => 3, 6 => 4, 7 => 5, 1 => 6];
            $dailyDbData = Shipment::select(DB::raw('DAYOFWEEK(cikis_tarihi) as day_of_week'), DB::raw('COUNT(*) as count'))
                ->whereNotNull('cikis_tarihi')
                ->groupBy('day_of_week')->pluck('count', 'day_of_week');
            foreach ($dailyDbData as $dayNum => $count) {
                if (isset($dayMap[$dayNum])) {
                    $dayCounts[$dayMap[$dayNum]] = $count;
                }
            }
            $chartData['daily'] = [
                'labels' => $dayLabels,
                'data' => $dayCounts,
                'title' => '📅 Haftalık Sevkiyat Yoğunluğu (Tüm Zamanlar)'
            ];
            $currentYear = date('Y');
            $monthLabels = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
            $monthCounts = array_fill(0, 12, 0);
            $monthlyDbData = Shipment::select(DB::raw('MONTH(cikis_tarihi) as month'), DB::raw('COUNT(*) as count'))
                ->whereYear('cikis_tarihi', $currentYear)
                ->whereNotNull('cikis_tarihi')
                ->groupBy('month')->pluck('count', 'month');
            foreach ($monthlyDbData as $monthNum => $count) {
                if ($monthNum >= 1 && $monthNum <= 12) {
                    $monthCounts[$monthNum - 1] = $count;
                }
            }
            $chartData['monthly'] = [
                'labels' => $monthLabels,
                'data' => $monthCounts,
                'title' => $currentYear . ' Yılı Aylık Sevkiyat Dağılımı'
            ];
            $yearlyDbData = Shipment::select(DB::raw('YEAR(cikis_tarihi) as year'), DB::raw('COUNT(*) as count'))
                ->whereNotNull('cikis_tarihi')
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('count', 'year');
            $chartData['yearly'] = [
                'labels' => $yearlyDbData->keys()->map(fn($y) => (string)$y)->all(),
                'data'   => $yearlyDbData->values()->all(),
                'title' => 'Yıllara Göre Toplam Sevkiyat Sayısı'
            ];
            $vehicleTypeData = Shipment::select('arac_tipi', DB::raw('COUNT(*) as count'))
                ->whereNotNull('arac_tipi')
                ->groupBy('arac_tipi')
                ->get()
                ->groupBy(fn($item) => $this->normalizeVehicleType($item->arac_tipi))
                ->map(fn($group) => $group->sum('count'));
            $chartData['pie'] = [
                'labels' => $vehicleTypeData->keys()->map(fn($tip) => $tip ?? 'Bilinmiyor')->all(),
                'data'   => $vehicleTypeData->values()->all(),
                'title' => 'Araç Tipi Dağılımı (Tüm Zamanlar)'
            ];
            $allShipmentsRaw = Shipment::select('id', 'cikis_tarihi', 'arac_tipi', 'kargo_icerigi', 'shipment_type')
                ->whereNotNull('cikis_tarihi')
                ->get()
                ->map(function ($shipment) {
                    try {
                        $shipment->cikis_tarihi_carbon = Carbon::parse($shipment->cikis_tarihi);
                    } catch (\Exception $e) {
                        $shipment->cikis_tarihi_carbon = null;
                        Log::warning("İstatistik için tarih parse hatası - Shipment ID: " . $shipment->id, ['error' => $e->getMessage()]);
                    }
                    return $shipment;
                })
                ->filter(fn($s) => $s->cikis_tarihi_carbon !== null);
            $availableYears = $allShipmentsRaw->pluck('cikis_tarihi_carbon')
                ->map(fn($date) => $date->year)
                ->unique()
                ->sortDesc()
                ->values()
                ->all();
            $shipmentsForFiltering = $allShipmentsRaw->map(function ($shipment) {
                return [
                    'year' => $shipment->cikis_tarihi_carbon->year,
                    'month' => $shipment->cikis_tarihi_carbon->month,
                    'day' => $shipment->cikis_tarihi_carbon->day,
                    'vehicle' => $this->normalizeVehicleType($shipment->arac_tipi ?? 'Bilinmiyor'),
                    'cargo' => $this->normalizeCargoContent($shipment->kargo_icerigi ?? 'Bilinmiyor'),
                    'shipment_type' => $shipment->shipment_type
                ];
            })->values()->all();
        } elseif ($departmentSlug === 'uretim') {
            $statsStartDate = Carbon::now()->subYear()->startOfMonth();
            $endDate = Carbon::now();
            $weeklyPlanCounts = ProductionPlan::select(
                DB::raw('YEARWEEK(week_start_date, 1) as year_week'),
                DB::raw('COUNT(*) as count')
            )
                ->where('week_start_date', '>=', $statsStartDate)
                ->whereNotNull('week_start_date')
                ->groupBy('year_week')
                ->orderBy('year_week')
                ->pluck('count', 'year_week');
            $weeklyLabels = [];
            $weeklyData = [];
            $currentWeek = $statsStartDate->copy()->startOfWeek();
            while ($currentWeek->lte($endDate)) {
                $yearWeek = $currentWeek->format('oW');
                $weeklyLabels[] = $currentWeek->format('W') . '. Hafta';
                $weeklyData[] = $weeklyPlanCounts[$yearWeek] ?? 0;
                $currentWeek->addWeek();
            }
            $chartData['weekly_prod'] = [
                'labels' => $weeklyLabels,
                'data' => $weeklyData,
                'title' => '📅 Haftalık Üretim Planı Sayısı (Son 1 Yıl)'
            ];
            $monthlyPlanCounts = ProductionPlan::select(
                DB::raw('YEAR(week_start_date) as year'),
                DB::raw('MONTH(week_start_date) as month'),
                DB::raw('COUNT(*) as count')
            )
                ->where('week_start_date', '>=', $statsStartDate)
                ->whereNotNull('week_start_date')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
            $monthlyLabels = [];
            $monthlyData = [];
            $currentMonth = $statsStartDate->copy();
            while ($currentMonth->lte($endDate)) {
                $year = $currentMonth->year;
                $month = $currentMonth->month;
                $count = $monthlyPlanCounts
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first()?->count ?? 0;
                $monthlyLabels[] = $currentMonth->translatedFormat('M Y');
                $monthlyData[] = $count;
                $currentMonth->addMonth();
            }
            $chartData['monthly_prod'] = [
                'labels' => $monthlyLabels,
                'data' => $monthlyData,
                'title' => '🗓️ Aylık Üretim Planı Sayısı (Son 1 Yıl)'
            ];
        } elseif ($departmentSlug === 'hizmet') {
            $statsStartDate = Carbon::now()->subYear()->startOfMonth();
            $endDate = Carbon::now();
            $eventTypeCounts = Event::select('event_type', DB::raw('COUNT(*) as count'))
                ->whereNotNull('event_type')
                ->groupBy('event_type')
                ->pluck('count', 'event_type');
            $eventTypesList = $this->getEventTypes();
            $pieLabels = $eventTypeCounts->keys()
                ->map(fn($key) => $eventTypesList[$key] ?? ucfirst($key))
                ->all();
            $chartData['event_type_pie'] = [
                'labels' => $pieLabels,
                'data' => $eventTypeCounts->values()->all(),
                'title' => 'Etkinlik Tipi Dağılımı (Tüm Zamanlar)'
            ];
            $monthlyAssignmentCounts = VehicleAssignment::select(
                DB::raw('YEAR(start_time) as year'),
                DB::raw('MONTH(start_time) as month'),
                DB::raw('COUNT(*) as count')
            )
                ->where('start_time', '>=', $statsStartDate)
                ->where('start_time', '<=', $endDate)
                ->whereNotNull('start_time')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
            $monthlyLabels = [];
            $monthlyData = [];
            $currentMonth = $statsStartDate->copy();
            while ($currentMonth->lte($endDate)) {
                $year = $currentMonth->year;
                $month = $currentMonth->month;
                $count = $monthlyAssignmentCounts
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first()?->count ?? 0;
                $monthlyLabels[] = $currentMonth->translatedFormat('M Y');
                $monthlyData[] = $count;
                $currentMonth->addMonth();
            }
            $chartData['monthly_assign'] = [
                'labels' => $monthlyLabels,
                'data' => $monthlyData,
                'title' => '🚗 Aylık Araç Atama Sayısı (Son 1 Yıl)'
            ];
        }

        return view('statistics.index', compact(
            'pageTitle',
            'departmentSlug',
            'chartData',
            'shipmentsForFiltering',
            'availableYears'
        ));
    }


    /**
     * ===================================================================
     * ÖNEMLİ BİLDİRİMLER (Değişiklik yok)
     * ===================================================================
     */
    private function getMappedImportantItems(Request $request)
    {
        // ... (Bu fonksiyon aynı kalır) ...
        $typeFilter = $request->input('type', 'all');
        $deptFilter = $request->input('department_id', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $allMappedItems = collect();
        if ($typeFilter == 'all' || $typeFilter == 'shipment') {
            $query = Shipment::where('is_important', true);
            if ($dateFrom) $query->where('tahmini_varis_tarihi', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)   $query->where('tahmini_varis_tarihi', '<=', Carbon::parse($dateTo)->endOfDay());
            if ($deptFilter != 'all') {
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));
            }
            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object)[
                        'title' => 'Sevkiyat: ' . ($item->kargo_icerigi ?? 'Detay Yok'),
                        'date'  => $item->tahmini_varis_tarihi,
                        'model_id' => $item->id,
                        'model_type' => 'shipment'
                    ];
                })
            );
        }
        if ($typeFilter == 'all' || $typeFilter == 'production_plan') {
            $query = ProductionPlan::where('is_important', true);
            if ($dateFrom) $query->where('week_start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)   $query->where('week_start_date', '<=', Carbon::parse($dateTo)->endOfDay());
            if ($deptFilter != 'all') {
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));
            }
            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object)[
                        'title' => 'Üretim: ' . $item->plan_title,
                        'date'  => $item->week_start_date,
                        'model_id' => $item->id,
                        'model_type' => 'production_plan'
                    ];
                })
            );
        }
        if ($typeFilter == 'all' || $typeFilter == 'event') {
            $query = Event::where('is_important', true);
            if ($dateFrom) $query->where('start_datetime', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)   $query->where('start_datetime', '<=', Carbon::parse($dateTo)->endOfDay());
            if ($deptFilter != 'all') {
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));
            }
            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object)[
                        'title' => 'Etkinlik: ' . $item->title,
                        'date'  => $item->start_datetime,
                        'model_id' => $item->id,
                        'model_type' => 'event'
                    ];
                })
            );
        }
        if ($typeFilter == 'all' || $typeFilter == 'vehicle_assignment') {
            $query = VehicleAssignment::where('is_important', true);
            if ($dateFrom) $query->where('start_time', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)   $query->where('start_time', '<=', Carbon::parse($dateTo)->endOfDay());
            if ($deptFilter != 'all') {
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));
            }
            $allMappedItems = $allMappedItems->merge(
                $query->get()->map(function ($item) {
                    return (object)[
                        'title' => 'Araç Görevi: ' . Str::limit($item->task_description, 30),
                        'date'  => $item->start_time,
                        'model_id' => $item->id,
                        'model_type' => 'vehicle_assignment'
                    ];
                })
            );
        }
        return $allMappedItems->sortByDesc('date');
    }

    public function showAllImportant(Request $request)
    {
        // ... (Bu fonksiyon aynı kalır) ...
        $filters = $request->only(['type', 'department_id', 'date_from', 'date_to']);
        $departments = Department::orderBy('name')->get();
        $allItems = $this->getMappedImportantItems($request);
        return view('important-items', [
            'importantItems' => $allItems,
            'filters' => $filters,
            'departments' => $departments
        ]);
    }

    /**
     * ===================================================================
     * ÖZEL YARDIMCI METOTLAR (Değişiklik yok)
     * ===================================================================
     */
    private function normalizeCargoContent($cargo)
    {
        // ... (Bu fonksiyon aynı kalır) ...
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
        // ... (Bu fonksiyon aynı kalır) ...
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
        // ... (Bu fonksiyon aynı kalır) ...
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
        // ... (Bu fonksiyon aynı kalır) ...
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
}
