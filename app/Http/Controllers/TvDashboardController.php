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
        $chartData = $this->getSankeyData();
        $currentDataHash = $this->getSystemDataHash();

        return view('tv.dashboard', compact('kpiData', 'importantItems', 'chartData', 'currentDataHash'));
    }

    public function checkUpdates()
    {
        return response()->json(['hash' => $this->getSystemDataHash()]);
    }

    /**
     * 1. KPI VERİLERİ (Tüm Modellerde Scope Kaldırıldı)
     */
    private function getKpiData(Carbon $today): array
    {
        // 1. SEVKİYAT
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

        // 2. ETKİNLİK
        $eventGroups = Event::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_datetime', $today)
            ->join('event_types', 'events.event_type_id', '=', 'event_types.id')
            ->select(['event_types.name as type_name', DB::raw('count(*) as count')])
            ->groupBy('event_types.name')
            ->get();

        $eventDetails = $eventGroups->map(fn($item) => "{$item->count} {$item->type_name}")->implode(', ');

        // 3. FABRİKA BİRİMLERİ (ProductionPlan)
        $activePlans = ProductionPlan::withoutGlobalScope('business_unit_scope')
            ->with('businessUnit')
            ->whereDate('week_start_date', '<=', $today)
            ->get();

        $kopetCount = $activePlans->filter(fn($p) => $p->businessUnit && Str::contains(Str::lower($p->businessUnit->name), 'kopet'))->count();
        $preformCount = $activePlans->filter(fn($p) => $p->businessUnit && Str::contains(Str::lower($p->businessUnit->name), 'preform'))->count();
        $levhaCount = $activePlans->filter(fn($p) => $p->businessUnit && Str::contains(Str::lower($p->businessUnit->name), 'levha'))->count();

        // 4. DİĞER MODELLER (Scope Kaldırıldı)

        // VehicleAssignment (start_time kullanıyor)
        $vehicleCount = VehicleAssignment::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_time', $today)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        // MaintenancePlan (planned_start_date kullanıyor)
        $maintenanceCount = MaintenancePlan::withoutGlobalScope('business_unit_scope')
            ->whereDate('planned_start_date', $today)
            ->count();

        // Travel (start_date kullanıyor)
        $travelCount = Travel::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_date', $today)
            ->count();

        return [
            'sevkiyat_sayisi' => $totalShipment,
            'sevkiyat_detay' => $shipmentDetails,
            'plan_sayisi' => $activePlans->count(),

            'kopet_count' => $kopetCount,
            'preform_count' => $preformCount,
            'levha_count' => $levhaCount,

            'etkinlik_sayisi' => Event::withoutGlobalScope('business_unit_scope')->whereDate('start_datetime', $today)->count(),
            'etkinlik_detay' => $eventDetails,

            'arac_gorevi_sayisi' => $vehicleCount,
            'bakim_sayisi' => $maintenanceCount,
            'kullanici_sayisi' => User::count(), // User modelinde genelde business unit trait olmaz, varsa buna da ekle
            'seyahat_sayisi' => $travelCount
        ];
    }

    /**
     * 2. ÖNEMLİ BİLDİRİMLER (Tüm Modellerde Scope Kaldırıldı)
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

        // 4. ARAÇ GÖREVLERİ (VehicleAssignment)
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

        // 5. SEYAHATLER (Travel)
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

        // 6. BAKIM (MaintenancePlan)
        // Bakım modelinde 'is_important' alanı yok, 'priority' kullanıyoruz.
        $maintenance = MaintenancePlan::withoutGlobalScope('business_unit_scope')
            ->whereIn('priority', ['high', 'critical'])
            ->where('planned_start_date', '>=', $today)
            ->get()
            ->map(fn($i) => (object) [
                'category' => 'BAKIM',
                'content' => $i->title ?? 'Bakım Planı', // title alanı null ise varsayılan
                'date' => $i->planned_start_date,
                'type' => 'maintenance'
            ]);

        $items = $items->merge($maintenance);

        return $items->sortBy('date')->take(6);
    }

    /**
     * 3. SANKEY GRAFİK VERİSİ (TAM FABRİKA RÖNTGENİ)
     * Akış: KÖKSAN -> [FABRİKA ADI] -> [DEPARTMAN (Üretim/Sevkiyat/Bakım)]
     */
    private function getSankeyData(): array
    {
        $data = [];
        $root = 'KÖKSAN';

        // 1. Veritabanındaki tüm Business Unit'leri (Fabrikaları) çek
        $units = BusinessUnit::all();

        // 2. TÜM VERİLERİ BİRİMLERE GÖRE GRUPLAYARAK ÇEK
        // Her sorguda 'withoutGlobalScope' kullanarak tüm veriyi görüyoruz.
        // pluck('count', 'business_unit_id') ile bize şöyle bir dizi döner: [ '1' => 5, '2' => 8 ]

        // A) ÜRETİM
        $prodCounts = ProductionPlan::withoutGlobalScope('business_unit_scope')
            ->select('business_unit_id', DB::raw('count(*) as count'))
            ->whereDate('week_start_date', '<=', Carbon::today()) // Tarih filtreleri
            ->groupBy('business_unit_id')
            ->pluck('count', 'business_unit_id');

        // B) SEVKİYAT
        $shipCounts = Shipment::withoutGlobalScope('business_unit_scope')
            ->select('business_unit_id', DB::raw('count(*) as count'))
            ->whereDate('tahmini_varis_tarihi', Carbon::today())
            ->groupBy('business_unit_id')
            ->pluck('count', 'business_unit_id');

        // C) BAKIM
        $maintCounts = MaintenancePlan::withoutGlobalScope('business_unit_scope')
            ->select('business_unit_id', DB::raw('count(*) as count'))
            ->whereDate('planned_start_date', Carbon::today())
            ->groupBy('business_unit_id')
            ->pluck('count', 'business_unit_id');

        // D) ETKİNLİK / ZİYARET
        $eventCounts = Event::withoutGlobalScope('business_unit_scope')
            ->select('business_unit_id', DB::raw('count(*) as count'))
            ->whereDate('start_datetime', Carbon::today())
            ->groupBy('business_unit_id')
            ->pluck('count', 'business_unit_id');


        // 3. GRAFİĞİ İNŞA ET
        // Her bir fabrika için döngüye girip kollarını oluşturuyoruz.

        $totalSystemActivity = 0; // Sistemin hiç verisi yoksa kontrolü için

        foreach ($units as $unit) {
            $uId = $unit->id;
            $unitName = Str::upper($unit->name); // Örn: PREFORM FAB.

            // Bu fabrikaya ait sayıları al (Yoksa 0)
            $pCount = $prodCounts[$uId] ?? 0; // Üretim
            $sCount = $shipCounts[$uId] ?? 0; // Sevkiyat
            $mCount = $maintCounts[$uId] ?? 0; // Bakım
            $eCount = $eventCounts[$uId] ?? 0; // Etkinlik

            $totalUnitActivity = $pCount + $sCount + $mCount + $eCount;

            // Eğer bu fabrikada bugün hiç hareket yoksa grafiğe ekleme (kalabalık yapmasın)
            if ($totalUnitActivity == 0)
                continue;

            $totalSystemActivity += $totalUnitActivity;

            // ADIM 1: ANA KOL (KÖKSAN -> FABRİKA)
            // Kalınlığı toplam aktivite kadar olacak
            $data[] = [$root, $unitName, (int) $totalUnitActivity];

            // ADIM 2: ALT KOLLAR (FABRİKA -> DEPARTMANLAR)

            // Üretim Kolu
            if ($pCount > 0) {
                // Node ismi "ÜRETİM (PREFORM)" gibi olsun ki diğer fabrikanın üretimiyle karışmasın
                $nodeName = 'ÜRETİM (' . Str::limit($unit->name, 4, '') . ')';
                $data[] = [$unitName, $nodeName, (int) $pCount];
            }

            // Sevkiyat Kolu
            if ($sCount > 0) {
                $nodeName = 'SEVKİYAT (' . Str::limit($unit->name, 4, '') . ')';
                $data[] = [$unitName, $nodeName, (int) $sCount];
            }

            // Bakım Kolu
            if ($mCount > 0) {
                $nodeName = 'BAKIM (' . Str::limit($unit->name, 4, '') . ')';
                $data[] = [$unitName, $nodeName, (int) $mCount];
            }

            // Etkinlik Kolu
            if ($eCount > 0) {
                $nodeName = 'ZİYARET (' . Str::limit($unit->name, 4, '') . ')';
                $data[] = [$unitName, $nodeName, (int) $eCount];
            }
        }

        // 4. GENEL / TANIMSIZ İŞLEMLER (Birim ID'si null olanlar)
        // Bazen idari işler birime bağlı olmaz, onları "GENEL MERKEZ" altında toplayalım.
        $generalP = $prodCounts[''] ?? 0; // Key boş string veya null dönebilir
        $generalS = $shipCounts[''] ?? 0;
        $generalM = $maintCounts[''] ?? 0;
        $generalE = $eventCounts[''] ?? 0;

        $totalGeneral = $generalP + $generalS + $generalM + $generalE;

        if ($totalGeneral > 0) {
            $genLabel = 'GENEL MERKEZ';
            $data[] = [$root, $genLabel, (int) $totalGeneral];

            if ($generalS > 0)
                $data[] = [$genLabel, 'LOJİSTİK (GENEL)', (int) $generalS];
            if ($generalE > 0)
                $data[] = [$genLabel, 'İDARİ/ZİYARET', (int) $generalE];
            if ($generalM > 0)
                $data[] = [$genLabel, 'GENEL BAKIM', (int) $generalM];
        }

        // HİÇ VERİ YOKSA BOŞ GÖRÜNMESİN
        if (empty($data)) {
            $data[] = [$root, 'SİSTEM HAZIR', 1];
        }

        return $data;
    }

    /**
     * 4. SİSTEM HASH
     */
    private function getSystemDataHash()
    {
        // Tüm modellerden Scope kaldırarak son güncelleme zamanını alıyoruz
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