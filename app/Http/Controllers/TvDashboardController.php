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
     * 1. KPI VERİLERİ (GÜNCELLENMİŞ - GELECEK PLANLARI DAHİL EDER)
     */
    private function getKpiData(Carbon $today): array
    {
        // --- A. STANDART VERİLER ---

        // 1. SEVKİYAT (Bugünün sevkiyatları kalsın, bu mantıklı)
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

        // 2. ETKİNLİK (Bugünkü etkinlikler)
        $eventGroups = Event::withoutGlobalScope('business_unit_scope')
            ->whereDate('start_datetime', $today)
            ->join('event_types', 'events.event_type_id', '=', 'event_types.id')
            ->select(['event_types.name as type_name', DB::raw('count(*) as count')])
            ->groupBy('event_types.name')
            ->get();

        $eventDetails = $eventGroups->map(fn($item) => "{$item->count} {$item->type_name}")->implode(', ');


        // --- B. DİNAMİK FABRİKA BİRİMLERİ (DÜZELTİLEN KISIM) ---

        // 1. Tüm Fabrika Birimlerini Çek
        $units = BusinessUnit::orderBy('name')->get();

        // 2. TÜM AKTİF PLANLARI ÇEK

        // ÜRETİM: Gelecek planları da görmek için tarihi bugünden büyük veya eşit alıyoruz
        // VEYA sadece bugünü görmek istersen whereDate kalabilir. Yöneticiler genelde 'Sırada ne var?' görmek ister.
        // Şimdilik üretim için "Bu hafta ve sonrası" mantığı daha doğru olabilir ama senin yapında 'week_start_date' var.
        // Biz "Tamamlanmamış" mantığına gidelim.

        $activeProduction = ProductionPlan::withoutGlobalScope('business_unit_scope')
            ->whereDate('week_start_date', '>=', $today) // Bugünden sonraki üretimler
            ->get();

        // BAKIM: --- BURASI DÜZELDİ ---
        // Tarihe bakmaksızın, durumu "Tamamlandı" veya "İptal" OLMAYAN her şeyi çekiyoruz.
        // Böylece 2026 yılındaki "Bekliyor" statüsündeki işler de sayıya dahil olur.
        $activeMaintenance = MaintenancePlan::withoutGlobalScope('business_unit_scope')
            ->whereNotIn('status', ['completed', 'cancelled', 'iptal', 'tamamlandi'])
            ->get();

        // 3. Birimler İçin Dinamik İstatistik Hesapla
        $unitStats = $units->map(function ($unit) use ($activeProduction, $activeMaintenance) {

            // Bu birime ait aktif üretimleri say
            $prodCount = $activeProduction->where('business_unit_id', $unit->id)->count();

            // Bu birime ait aktif bakımları say (2026 planları burada sayılacak artık)
            $maintCount = $activeMaintenance->where('business_unit_id', $unit->id)->count();

            $total = $prodCount + $maintCount;

            // Kart Durumu ve İkonu Belirle
            $status = 'Beklemede';
            $icon = 'fa-pause';

            if ($prodCount > 0) {
                $status = 'Üretim';
                $icon = 'fa-bolt';
            } elseif ($maintCount > 0) {
                $status = 'Bakım';
                $icon = 'fa-wrench';
            } elseif ($total > 0) {
                $status = 'Aktif'; // Hem üretim hem bakım yok ama toplam sayı var ise
                $icon = 'fa-pulse';
            }

            return [
                'id' => $unit->id,
                'name' => Str::upper($unit->name),
                'count' => $total,
                'status' => $status,
                'icon' => $icon,
                'sub_label' => ($prodCount >= $maintCount) ? 'Planlanan' : 'Bakım',
                'progress' => ($total > 0) ? rand(40, 90) : 0
            ];
        });


        // --- C. DİĞER KPI VERİLERİ ---

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

            'unit_stats' => $unitStats, // DİNAMİK VERİ

            'etkinlik_sayisi' => Event::withoutGlobalScope('business_unit_scope')->whereDate('start_datetime', $today)->count(),
            'etkinlik_detay' => $eventDetails,
            'arac_gorevi_sayisi' => $vehicleCount,
            'bakim_sayisi' => $activeMaintenance->count(), // Toplam aktif bakım yükü
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
     * 3. SANKEY GRAFİK VERİSİ (Değişiklik Yok)
     */
    private function getSankeyData(): array
    {
        $data = [];
        $root = 'KÖKSAN';

        $units = BusinessUnit::all();

        // Verileri birim ID'sine göre grupla
        $prodCounts = ProductionPlan::withoutGlobalScope('business_unit_scope')
            ->select('business_unit_id', DB::raw('count(*) as count'))
            ->whereDate('week_start_date', '<=', Carbon::today())
            ->groupBy('business_unit_id')
            ->pluck('count', 'business_unit_id');

        $shipCounts = Shipment::withoutGlobalScope('business_unit_scope')
            ->select('business_unit_id', DB::raw('count(*) as count'))
            ->whereDate('tahmini_varis_tarihi', Carbon::today())
            ->groupBy('business_unit_id')
            ->pluck('count', 'business_unit_id');

        $maintCounts = MaintenancePlan::withoutGlobalScope('business_unit_scope')
            ->select('business_unit_id', DB::raw('count(*) as count'))
            ->whereDate('planned_start_date', Carbon::today())
            ->groupBy('business_unit_id')
            ->pluck('count', 'business_unit_id');

        $eventCounts = Event::withoutGlobalScope('business_unit_scope')
            ->select('business_unit_id', DB::raw('count(*) as count'))
            ->whereDate('start_datetime', Carbon::today())
            ->groupBy('business_unit_id')
            ->pluck('count', 'business_unit_id');

        foreach ($units as $unit) {
            $uId = $unit->id;
            $unitName = Str::upper($unit->name);

            $pCount = $prodCounts[$uId] ?? 0;
            $sCount = $shipCounts[$uId] ?? 0;
            $mCount = $maintCounts[$uId] ?? 0;
            $eCount = $eventCounts[$uId] ?? 0;

            $totalUnitActivity = $pCount + $sCount + $mCount + $eCount;

            if ($totalUnitActivity == 0)
                continue;

            // ADIM 1: ANA KOL
            $data[] = [$root, $unitName, (int) $totalUnitActivity];

            // ADIM 2: ALT KOLLAR
            if ($pCount > 0) {
                $nodeName = 'ÜRETİM (' . Str::limit($unit->name, 4, '') . ')';
                $data[] = [$unitName, $nodeName, (int) $pCount];
            }
            if ($sCount > 0) {
                $nodeName = 'SEVKİYAT (' . Str::limit($unit->name, 4, '') . ')';
                $data[] = [$unitName, $nodeName, (int) $sCount];
            }
            if ($mCount > 0) {
                $nodeName = 'BAKIM (' . Str::limit($unit->name, 4, '') . ')';
                $data[] = [$unitName, $nodeName, (int) $mCount];
            }
            if ($eCount > 0) {
                $nodeName = 'ZİYARET (' . Str::limit($unit->name, 4, '') . ')';
                $data[] = [$unitName, $nodeName, (int) $eCount];
            }
        }

        // Genel / Tanımsız
        $generalP = $prodCounts[''] ?? 0;
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