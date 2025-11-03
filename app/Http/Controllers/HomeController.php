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

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ===============================================
    // GÜNCELLENMİŞ INDEX METODU
    // ===============================================
    public function index(Request $request)
    {
        // --- Departman Bilgisi ---
        $user = Auth::user();
        $departmentSlug = $user->department?->slug;
        $departmentName = $user->department?->name ?? 'Genel';

        $events = []; // Takvim olayları
        $now = Carbon::now();
        $appTimezone = config('app.timezone');

        // --- Departmana Göre Filtrelenmiş Takvim Verileri ---

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
                    'eventType' => 'shipment', // Evrensel modal için tip
                    'title' => '🚚 Sevkiyat Detayı: ' . $normalizedKargo, // Modal başlığı
                    'id' => $shipment->id,
                    'user_id' => $shipment->user_id,
                    'editUrl' => route('shipments.edit', $shipment->id),
                    'deleteUrl' => route('shipments.destroy', $shipment->id),
                    'exportUrl' => route('shipments.export', $shipment->id),
                    'onayUrl' => route('shipments.onayla', $shipment->id),
                    'onayKaldirUrl' => route('shipments.onayiGeriAl', $shipment->id),
                    'details' => [ // Dinamik modal içeriği için
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
            $plans = ProductionPlan::with('user')->get(); // Oluşturanı da alalım
            foreach ($plans as $plan) {
                $events[] = [
                    'title' => 'Üretim: ' . $plan->plan_title,
                    'start' => $plan->week_start_date->startOfDay()->toIso8601String(),
                    'end'   => $plan->week_start_date->copy()->addDay()->startOfDay()->toIso8601String(),
                    'color' => '#4FD1C5', // Üretim rengi
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
                            'Plan Detayları' => $plan->plan_details, // JS'de tabloya çevrilecek
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
                    'title' => '🚗 Araç Atama Detayı',
                    'id' => $assignment->id,
                    // DİKKAT: Düzenleme linkini de yetkiye bağlayalım
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
        // --- Takvim Verileri Sonu ---


        // --- Departmana Özel İstatistik Verileri ---
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
    // ===============================================
    // INDEX METODU BİTİŞİ
    // ===============================================


    // ===============================================
    // GÜNCELLENEN WELCOME METODU BAŞLANGICI
    // ===============================================
    public function welcome()
    {
        $user = Auth::user();
        $departmentSlug = $user->department?->slug;

        $welcomeTitle = "Bugünkü Görevler";
        $todayItems = collect();
        $chartType = 'sankey';
        $chartData = [];
        $chartTitle = "Genel Veri Akışı";

        // DEBUG: Departman bilgisini logla
        Log::info('Welcome sayfası yükleniyor', [
            'user_id' => $user->id,
            'department_slug' => $departmentSlug
        ]);

        // --- Departmana Göre Veri Hazırla ---

        if ($departmentSlug === 'lojistik') {
            $welcomeTitle = "Bugün Yaklaşan Sevkiyatlar";
            $chartTitle = "Kargo İçeriği -> Araç Tipi Akışı (Tüm Zamanlar)";

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
                Log::warning('Lojistik departmanı için Sankey verisi bulunamadı. Placeholder veri gösteriliyor.');
                $chartData[] = ['Veri Yok', 'Henüz Sevkiyat Girilmedi', 1];
            }
        } elseif ($departmentSlug === 'uretim') {
            $welcomeTitle = "Bugün Başlayan Üretim Planları";
            $chartTitle = "Makine -> Ürün Planlama Akışı (Toplam Adet)";

            $todayItems = ProductionPlan::whereDate('week_start_date', Carbon::today())
                ->orderBy('created_at', 'asc')
                ->get();

            $plans = ProductionPlan::whereNotNull('plan_details')->get();

            Log::info('Üretim planları sorgulandı', ['plan_count' => $plans->count()]);

            $flowCounts = [];

            foreach ($plans as $plan) {
                if (is_array($plan->plan_details)) {
                    foreach ($plan->plan_details as $detail) {
                        // ÖNEMLİ: Tüm değerleri string'e çevir
                        $machine = trim(strval($detail['machine'] ?? 'Bilinmiyor'));
                        $productRaw = $detail['product'] ?? 'Bilinmiyor';

                        // Eğer product bir sayı ise, önüne "Ürün-" ekle
                        if (is_numeric($productRaw)) {
                            $product = 'Ürün-' . $productRaw;
                        } else {
                            $product = trim(strval($productRaw));
                        }

                        $quantity = (int)($detail['quantity'] ?? 0);

                        // Boş string kontrolü
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
                        // ÖNEMLİ: Tüm değerlerin string olduğundan emin ol
                        $chartData[] = [
                            strval($machine),  // Kaynak (string)
                            strval($product),  // Hedef (string)
                            (int)$weight       // Ağırlık (integer)
                        ];
                    }
                }
            }

            if (empty($chartData)) {
                Log::warning('Üretim departmanı için Sankey verisi bulunamadı. Placeholder veri gösteriliyor.');
                $chartData[] = ['Veri Yok', 'Henüz Plan Girilmedi', 1];
            }

            Log::info('Üretim chartData oluşturuldu', [
                'data_count' => count($chartData),
                'sample_data' => array_slice($chartData, 0, 3)
            ]);
        } elseif ($departmentSlug === 'hizmet') {
            $welcomeTitle = "Bugünkü Etkinlikler ve Araç Görevleri";
            $chartTitle = "Araç -> Görev Yeri Akışı (Toplam Görev Sayısı)";

            $todayEvents = Event::whereDate('start_datetime', Carbon::today())
                ->orderBy('start_datetime', 'asc')
                ->get();

            $todayAssignments = VehicleAssignment::whereDate('start_time', Carbon::today())
                ->with('vehicle')
                ->orderBy('start_time', 'asc')
                ->get();

            $todayItems = $todayEvents->merge($todayAssignments)
                ->sortBy(fn($item) => $item->start_datetime ?? $item->start_time);

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

                if (empty($chartData)) {
                    Log::warning('Hizmet departmanı için Sankey verisi bulunamadı. Placeholder veri gösteriliyor.');
                    $chartData[] = ['Veri Yok', 'Henüz Görev Girilmedi', 1];
                }
            }

            Log::info('Hizmet chartData oluşturuldu', [
                'data_count' => count($chartData),
                'sample_data' => array_slice($chartData, 0, 3)
            ]);
        } else {
            // Departmanı olmayanlar veya Admin/Yönetici için varsayılan
            $welcomeTitle = "Bugün Yaklaşan Sevkiyatlar (Genel Bakış)";
            $chartTitle = "Kargo İçeriği -> Araç Tipi Akışı (Tüm Zamanlar)";

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
                Log::warning('Genel görünüm için Sankey verisi bulunamadı. Placeholder veri gösteriliyor.');
                $chartData[] = ['Veri Yok', 'Henüz Sevkiyat Girilmedi', 1];
            }
        }

        // SON KONTROL: Hiçbir durumda chartData boş kalmasın
        if (empty($chartData)) {
            Log::error('Welcome sayfası için hiç Sankey verisi üretilemedi!');
            $chartData[] = ['Sistem', 'Veri Bulunamadı', 1];
            $chartTitle = '⚠️ Grafik Verisi Bulunamadı';
        }

        // DEBUG: Final veriyi logla
        Log::info('Welcome view\'e gönderilen veri', [
            'chartData_count' => count($chartData),
            'chartData_sample' => array_slice($chartData, 0, 5),
            'chartTitle' => $chartTitle
        ]);

        return view('welcome', compact(
            'welcomeTitle',
            'todayItems',
            'chartType',
            'chartData',
            'chartTitle',
            'departmentSlug'
        ));
    }

    public function showStatistics(Request $request)
    {
        $user = Auth::user();
        $departmentSlug = $user->department?->slug;
        $departmentName = $user->department?->name ?? 'Genel';
        $pageTitle = $departmentName . " İstatistikleri";

        $chartData = [];
        $shipmentsForFiltering = [];
        $availableYears = [];

        // --- Departmana Göre İstatistik Hesaplama ---
        if ($departmentSlug === 'lojistik') {
            $pageTitle = "Ayrıntılı Sevkiyat İstatistikleri";

            // 1. Saatlik Yoğunluk
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

            // 2. Günlük Yoğunluk (Haftanın günleri)
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

            // 3. Aylık Yoğunluk (Bu yıl)
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

            // 4. Yıllık Yoğunluk
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

            // 5. Araç Tipi Dağılımı (Pasta)
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

            // 6. Filtrelenebilir Grafikler İçin Veri
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

            // DEBUG LOG
            Log::info('Lojistik İstatistik Verileri', [
                'hourly_count' => count($chartData['hourly']['data']),
                'daily_count' => count($chartData['daily']['data']),
                'monthly_count' => count($chartData['monthly']['data']),
                'yearly_count' => count($chartData['yearly']['data']),
                'pie_count' => count($chartData['pie']['data']),
                'filtering_shipments' => count($shipmentsForFiltering)
            ]);
        } elseif ($departmentSlug === 'uretim') {
            $statsStartDate = Carbon::now()->subYear()->startOfMonth();
            $endDate = Carbon::now();

            // Haftalık Plan Sayısı
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

            // Aylık Plan Sayısı
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

            // DEBUG LOG
            Log::info('Üretim İstatistik Verileri', [
                'weekly_count' => count($chartData['weekly_prod']['data']),
                'monthly_count' => count($chartData['monthly_prod']['data']),
                'date_range' => $statsStartDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')
            ]);
        } elseif ($departmentSlug === 'hizmet') {
            $statsStartDate = Carbon::now()->subYear()->startOfMonth();
            $endDate = Carbon::now();

            // Etkinlik Tipi Dağılımı (Pasta)
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

            // Aylık Araç Atama Sayısı
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

            // DEBUG LOG
            Log::info('Hizmet İstatistik Verileri', [
                'event_pie_count' => count($chartData['event_type_pie']['data']),
                'monthly_assign_count' => count($chartData['monthly_assign']['data']),
                'date_range' => $statsStartDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')
            ]);
        }

        // Final kontrol ve log
        Log::info('Statistics View\'e gönderilen veriler', [
            'department' => $departmentSlug,
            'chart_keys' => array_keys($chartData),
            'chart_data_sample' => collect($chartData)->map(fn($chart) => [
                'labels_count' => count($chart['labels'] ?? []),
                'data_count' => count($chart['data'] ?? []),
                'title' => $chart['title'] ?? 'N/A'
            ])
        ]);

        return view('statistics.index', compact(
            'pageTitle',
            'departmentSlug',
            'chartData',
            'shipmentsForFiltering',
            'availableYears'
        ));
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
}
