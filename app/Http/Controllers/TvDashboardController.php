<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Shipment;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use App\Models\MaintenancePlan;
use App\Models\Travel;
use App\Models\User;
use App\Models\BusinessUnit;

class TvDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today = Carbon::today();

        $kpiData = $this->getKpiData($today);
        $importantItems = $this->getImportantItems($today);

        // DİNAMİK MATRİS VERİSİ VE BAŞLIKLARI
        $matrixResult = $this->getMatrixData();

        $currentDataHash = $this->getSystemDataHash();

        return view('tv.dashboard', array_merge(
            compact('kpiData', 'importantItems', 'currentDataHash'),
            $matrixResult // 'matrixHeaders' ve 'matrixData' buranın içinden çıkacak
        ));
    }

    public function checkUpdates()
    {
        return response()->json(['hash' => $this->getSystemDataHash()]);
    }
    /**
     * 1. KPI VERİLERİ (LOJİSTİK VE ETKİNLİK DAHİL - TAM FABRİKA YÜKÜ)
     */
    private function getKpiData(Carbon $today): array
    {
        // --- A. STANDART ÜST KART VERİLERİ ---

        // 1. SEVKİYAT (Genel Toplam)
        $shipmentGroups = Shipment::withoutGlobalScope('business_unit_scope')
            ->whereDate('tahmini_varis_tarihi', $today)
            ->select(['arac_tipi', DB::raw('count(*) as count')])
            ->groupBy('arac_tipi')
            ->get();

        $totalShipment = $shipmentGroups->sum('count');
        $shipmentDetails = $shipmentGroups->map(function ($item) {
            $type = $item->arac_tipi ? Str::title($item->arac_tipi) : 'Diğer';
            return "{$item->count} {$type}";
        })->implode(', ');

        // 2. ETKİNLİK (Genel Toplam)
        $eventGroups = Event::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_datetime', $today)
            ->join('event_types', 'events.event_type_id', '=', 'event_types.id')
            ->select(['event_types.name as type_name', DB::raw('count(*) as count')])
            ->groupBy('event_types.name')
            ->get();

        $eventDetails = $eventGroups->map(fn($item) => "{$item->count} {$item->type_name}")->implode(', ');


        // --- B. DİNAMİK FABRİKA KARTLARI (BURASI GÜNCELLENDİ) ---

        // 1. Fabrikaları Çek
        $units = BusinessUnit::orderBy('name')->get();

        // 2. TÜM VERİLERİ ÇEK (Business Unit ID'sine göre eşleştireceğiz)

        // Üretim: Gelecek dahil
        $activeProduction = ProductionPlan::withoutGlobalScope('business_unit_scope')
            ->whereDate('week_start_date', '>=', $today)
            ->get();

        // Bakım: Tamamlanmamış hepsi
        $activeMaintenance = MaintenancePlan::withoutGlobalScope('business_unit_scope')
            ->whereNotIn('status', ['completed', 'cancelled', 'iptal', 'tamamlandi'])
            ->get();

        // YENİ: Sevkiyat (Lojistik) - Bugüne ait olanlar
        $activeShipments = Shipment::withoutGlobalScope('business_unit_scope')
            ->whereDate('tahmini_varis_tarihi', $today)
            ->get();

        // YENİ: Etkinlikler - Bugüne ait olanlar
        $activeEvents = Event::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_datetime', $today)
            ->get();

        // 3. Hesaplama ve Kart Oluşturma
        $unitStats = $units->map(function ($unit) use ($activeProduction, $activeMaintenance, $activeShipments, $activeEvents) {

            // Sayımları Yap
            $prodCount = $activeProduction->where('business_unit_id', $unit->id)->count();
            $maintCount = $activeMaintenance->where('business_unit_id', $unit->id)->count();
            $shipCount = $activeShipments->where('business_unit_id', $unit->id)->count(); // Lojistik Sayısı
            $eventCount = $activeEvents->where('business_unit_id', $unit->id)->count();   // Etkinlik Sayısı

            // Toplam Yük
            $total = $prodCount + $maintCount + $shipCount + $eventCount;

            // Kartın Durumunu En Yoğun Departmana Göre Belirle
            $status = 'Beklemede';
            $icon = 'fa-pause';
            $subLabel = 'Sistem Hazır';

            // Öncelik Sıralaması ve Etiketleme
            if ($total > 0) {
                // Hangi sayı en büyükse kartın kimliğini o belirlesin
                $maxVal = max($prodCount, $maintCount, $shipCount, $eventCount);

                if ($maxVal == $prodCount) {
                    $status = 'Üretim';
                    $icon = 'fa-bolt';
                    $subLabel = 'Planlanan';
                } elseif ($maxVal == $shipCount) {
                    $status = 'Sevkiyat';     // <--- ARTIK LOJİSTİK GÖRÜNECEK
                    $icon = 'fa-truck-ramp-box';
                    $subLabel = 'Lojistik';
                } elseif ($maxVal == $maintCount) {
                    $status = 'Bakım';
                    $icon = 'fa-wrench';
                    $subLabel = 'Arıza/Bakım';
                } elseif ($maxVal == $eventCount) {
                    $status = 'Ziyaret';
                    $icon = 'fa-users';
                    $subLabel = 'Etkinlik';
                }
            }

            return [
                'id' => $unit->id,
                'name' => Str::upper($unit->name),
                'count' => $total,
                'status' => $status,
                'icon' => $icon,
                'sub_label' => $subLabel,
                'progress' => ($total > 0) ? rand(40, 90) : 0
            ];
        });


        // --- C. DİĞER VERİLER ---

        $vehicleCount = VehicleAssignment::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_time', $today)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        $travelCount = Travel::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_date', $today)
            ->count();

        return [
            'sevkiyat_sayisi' => $totalShipment,
            'sevkiyat_detay' => $shipmentDetails,
            'plan_sayisi' => $activeProduction->count(),

            'unit_stats' => $unitStats, // GÜNCELLENMİŞ DİNAMİK VERİ

            'etkinlik_sayisi' => Event::withoutGlobalScope('business_unit_scope')->whereDate('start_datetime', $today)->count(),
            'etkinlik_detay' => $eventDetails,
            'arac_gorevi_sayisi' => $vehicleCount,
            'bakim_sayisi' => $activeMaintenance->count(),
            'kullanici_sayisi' => User::count(),
            'seyahat_sayisi' => $travelCount
        ];
    }

    /**
     * 2. ÖNEMLİ BİLDİRİMLER (Değişiklik Yok)
     */
    private function getImportantItems(Carbon $today): \Illuminate\Support\Collection
    {
        $items = collect();

        // 1. SEVKİYAT
        $shipments = Shipment::withoutGlobalScope('business_unit_scope')
            ->where('is_important', true)
            ->where('tahmini_varis_tarihi', '>=', $today)
            ->get()
            ->map(function ($i) {
                $cat = ' SEVKİYAT';
                if (!empty($i->arac_tipi)) {
                    $cat .= ' (' . Str::upper($i->arac_tipi) . ')';
                }
                return (object) [
                    'category' => $cat,
                    'content' => Str::limit($i->kargo_icerigi, 30),
                    'date' => $i->tahmini_varis_tarihi,
                    'type' => 'shipment'
                ];
            });
        $items = $items->merge($shipments);

        // 2. ÜRETİM PLANLARI
        $plans = ProductionPlan::withoutGlobalScope('business_unit_scope')
            ->with('businessUnit')
            ->where('is_important', true)
            ->where('week_start_date', '>=', $today)
            ->get()
            ->map(function ($i) {
                $category = 'ÜRETİM';
                if ($i->businessUnit) {
                    $category .= ' (' . Str::upper($i->businessUnit->name) . ')';
                }
                return (object) [
                    'category' => $category,
                    'content' => Str::limit($i->plan_title, 30),
                    'date' => $i->week_start_date,
                    'type' => 'productionplan'
                ];
            });
        $items = $items->merge($plans);

        // 3. ETKİNLİKLER
        $events = Event::withoutGlobalScope('business_unit_scope')
            ->where('is_important', true)
            ->where('start_datetime', '>=', $today)
            ->get()
            ->map(function ($i) {
                return (object) [
                    'category' => ' ETKİNLİK',
                    'content' => Str::limit($i->title, 30),
                    'date' => $i->start_datetime,
                    'type' => 'event'
                ];
            });
        $items = $items->merge($events);

        // 4. ARAÇ GÖREVLERİ
        $vehicles = VehicleAssignment::withoutGlobalScope('business_unit_scope')
            ->where('is_important', true)
            ->where('start_time', '>=', $today)
            ->get()
            ->map(function ($i) {
                return (object) [
                    'category' => ' ARAÇ GÖREVİ',
                    'content' => Str::limit($i->task_description, 30),
                    'date' => $i->start_time,
                    'type' => 'vehicleassignment'
                ];
            });
        $items = $items->merge($vehicles);

        // 5. SEYAHATLER
        $travels = Travel::withoutGlobalScope('business_unit_scope')
            ->where('is_important', true)
            ->where('start_date', '>=', $today)
            ->get()
            ->map(function ($i) {
                return (object) [
                    'category' => ' SEYAHAT',
                    'content' => Str::limit($i->name, 30),
                    'date' => $i->start_date,
                    'type' => 'travel'
                ];
            });
        $items = $items->merge($travels);

        // 6. BAKIM (Priority kontrolü)
        $maintenance = MaintenancePlan::withoutGlobalScope('business_unit_scope')
            ->whereIn('priority', ['high', 'critical'])
            ->where('planned_start_date', '>=', $today)
            ->get()
            ->map(fn($i) => (object) [
                'category' => 'BAKIM',
                'content' => $i->title ?? 'Bakım Planı',
                'date' => $i->planned_start_date,
                'type' => 'maintenance'
            ]);

        $items = $items->merge($maintenance);

        return $items->sortBy('date')->take(6);
    }

    /**
     * 3. FABRİKA DURUM MATRİSİ (TAM DİNAMİK YAPILANDIRMA)
     */
    private function getMatrixData(): array
    {
        $today = Carbon::today();
        $units = BusinessUnit::orderBy('name')->get();

        // 1. SÜTUN AYARLARI (YENİ SÜTUN EKLEMEK İÇİN TEK YAPMAN GEREKEN BURAYA EKLEMEK)
        // key: Veritabanı veya mantıksal anahtar
        // label: Tablo başlığı
        // icon: İkon sınıfı
        // color: Bootstrap/Tema renk sınıfı (info, warning, primary, secondary)
        $columnsConfig = [
            'production' => ['label' => 'ÜRETİM', 'icon' => 'fa-industry', 'color' => 'info'],
            'shipment' => ['label' => 'SEVKİYAT', 'icon' => 'fa-truck', 'color' => 'primary'],
            'maintenance' => ['label' => 'BAKIM/ARIZA', 'icon' => 'fa-wrench', 'color' => 'warning'],
            'event' => ['label' => 'ETKİNLİK', 'icon' => 'fa-users', 'color' => 'secondary'],
            // Yarın 'kalite' eklersen: 'quality' => ['label' => 'KALİTE', 'icon' => 'fa-check', 'color' => 'success']
        ];

        // 2. Verileri Çek
        $activeProduction = ProductionPlan::withoutGlobalScope('business_unit_scope')
            ->whereDate('week_start_date', '>=', $today)->get();

        $activeMaintenance = MaintenancePlan::withoutGlobalScope('business_unit_scope')
            ->whereNotIn('status', ['completed', 'cancelled', 'iptal', 'tamamlandi'])->get();

        $activeShipments = Shipment::withoutGlobalScope('business_unit_scope')
            ->whereDate('tahmini_varis_tarihi', $today)->get();

        $activeEvents = Event::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_datetime', $today)->get();

        $matrixRows = [];

        foreach ($units as $unit) {
            // Sayımları al
            $counts = [
                'production' => $activeProduction->where('business_unit_id', $unit->id)->count(),
                'maintenance' => $activeMaintenance->where('business_unit_id', $unit->id)->count(),
                'shipment' => $activeShipments->where('business_unit_id', $unit->id)->count(),
                'event' => $activeEvents->where('business_unit_id', $unit->id)->count(),
            ];

            // Eğer hareket yoksa atla
            if (array_sum($counts) == 0)
                continue;

            // Durum ve Renk Belirleme (Öncelik Sırası: Bakım > Üretim > Sevkiyat)
            $statusText = 'HAZIR';
            $statusColor = 'secondary';
            $glowColor = 'secondary'; // Sol baştaki nokta rengi

            if ($counts['maintenance'] > 0) {
                $statusText = 'BAKIMDA';
                $statusColor = 'warning'; // Yazı rengi
                $glowColor = 'danger';    // Nokta rengi
            } elseif ($counts['production'] > 0) {
                $statusText = 'ÜRETİMDE';
                $statusColor = 'success';
                $glowColor = 'success';
            } elseif ($counts['shipment'] > 0) {
                $statusText = 'SEVKİYAT';
                $statusColor = 'primary';
                $glowColor = 'primary';
            }

            // Sütunları Yapılandır (Blade'e hazır gönderiyoruz)
            $preparedColumns = [];
            foreach ($columnsConfig as $key => $config) {
                $count = $counts[$key] ?? 0;
                $preparedColumns[] = [
                    'count' => $count,
                    'has_data' => $count > 0,
                    'icon' => $config['icon'],
                    'color' => $config['color'],
                    // Blade'de if kontrolü yapmamak için style class'larını buradan gönderiyoruz
                    'badge_class' => $count > 0 ? "badge bg-{$config['color']} px-3 py-2 rounded-pill" : "text-muted opacity-25",
                    'icon_html' => $count > 0 ? "<i class='fa-solid {$config['icon']} me-1'></i> $count" : "<i class='fa-solid fa-minus'></i>"
                ];
            }

            $matrixRows[] = [
                'name' => Str::upper($unit->name),
                'status_dot_color' => $glowColor, // Sol baştaki nokta
                'columns' => $preparedColumns,    // Hazırlanmış sütun verileri
                'status_text' => $statusText,
                'status_text_class' => "fw-bold text-{$statusColor}"
            ];
        }

        // Blade tarafında başlıkları basmak için sadece label'ları gönderiyoruz
        $headers = array_column($columnsConfig, 'label');

        return [
            'matrixHeaders' => $headers,
            'matrixData' => $matrixRows
        ];
    }

    /**
     * 4. SİSTEM HASH
     */
    private function getSystemDataHash()
    {
        $timestamps = [
            Shipment::withoutGlobalScope('business_unit_scope')->max('updated_at'),
            ProductionPlan::withoutGlobalScope('business_unit_scope')->max('updated_at'),
            Event::withoutGlobalScope('business_unit_scope')->max('updated_at'),
            VehicleAssignment::withoutGlobalScope('business_unit_scope')->max('updated_at'),
            MaintenancePlan::withoutGlobalScope('business_unit_scope')->max('updated_at'),
            Travel::withoutGlobalScope('business_unit_scope')->max('updated_at'),
            BusinessUnit::max('updated_at'),
        ];
        return md5(json_encode($timestamps));
    }
}