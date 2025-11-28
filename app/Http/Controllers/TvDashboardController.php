<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection; // Collection sınıfını ekledik
use App\Models\Shipment;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use App\Models\MaintenancePlan;
use App\Models\Travel;
use App\Models\User;

class TvDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today = Carbon::today();

        // İşlemleri küçük parçalara böldük, IDE artık yorulmayacak
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
     * 1. KPI VERİLERİNİ HESAPLA
     */
    private function getKpiData(Carbon $today): array
    {
        return [
            'sevkiyat_sayisi' => Shipment::whereDate('tahmini_varis_tarihi', $today)->count(),
            'plan_sayisi' => ProductionPlan::whereDate('week_start_date', $today)->count(),
            'etkinlik_sayisi' => Event::whereDate('start_datetime', $today)->count(),
            'arac_gorevi_sayisi' => VehicleAssignment::whereDate('start_time', $today)
                ->whereIn('status', ['pending', 'in_progress'])->count(),
            'bakim_sayisi' => MaintenancePlan::whereDate('planned_start_date', $today)->count(),
            'kullanici_sayisi' => User::count()
        ];
    }

    /**
     * 2. ÖNEMLİ BİLDİRİMLERİ TOPLA
     */
    private function getImportantItems(Carbon $today): Collection
    {
        $items = collect();

        // Helper fonksiyon kullanarak kodu sadeleştirdik
        $items = $items->merge($this->fetchImportant(Shipment::class, 'tahmini_varis_tarihi', $today, 'kargo_icerigi', '🚚 Sevkiyat'));
        $items = $items->merge($this->fetchImportant(ProductionPlan::class, 'week_start_date', $today, 'plan_title', '🏭 Üretim'));
        $items = $items->merge($this->fetchImportant(Event::class, 'start_datetime', $today, 'title', '🎉 Etkinlik'));
        $items = $items->merge($this->fetchImportant(VehicleAssignment::class, 'start_time', $today, 'task_description', '🚗 Görev'));
        $items = $items->merge($this->fetchImportant(Travel::class, 'start_date', $today, 'name', '✈️ Seyahat'));

        // Bakım Planı (Priority mantığı farklı olduğu için manuel ekliyoruz)
        $maintenance = MaintenancePlan::whereIn('priority', ['high', 'critical'])
            ->where('planned_start_date', '>=', $today)
            ->get()
            ->map(fn($i) => (object) [
                'title' => '🔧 Bakım: ' . Str::limit($i->title, 25),
                'date' => $i->planned_start_date,
                'type' => 'maintenance'
            ]);

        $items = $items->merge($maintenance);

        return $items->sortBy('date')->take(6);
    }

    /**
     * YARDIMCI: Modellerden standart veri çekme
     */
    private function fetchImportant($modelClass, $dateCol, $today, $titleCol, $prefix)
    {
        return $modelClass::where('is_important', true)
            ->where($dateCol, '>=', $today)
            ->get()
            ->map(fn($i) => (object) [
                'title' => $prefix . ': ' . Str::limit($i->$titleCol, 25),
                'date' => $i->$dateCol,
                'type' => strtolower(class_basename($modelClass))
            ]);
    }

    /**
     * 3. SANKEY GRAFİK VERİSİ
     */
    private function getSankeyData(): array
    {
        $chartData = [];

        // A) Lojistik
        $logistics = Shipment::select(['kargo_icerigi', 'arac_tipi', DB::raw('COUNT(*) as weight')])
            ->whereNotNull('kargo_icerigi')->whereNotNull('arac_tipi')
            ->groupBy('kargo_icerigi', 'arac_tipi')->orderByDesc('weight')->limit(5)->get();
        foreach ($logistics as $flow) {
            $chartData[] = [Str::title(Str::limit($flow->kargo_icerigi, 15)), Str::upper($flow->arac_tipi), (int) $flow->weight];
        }

        // B) Üretim
        $prodPlans = ProductionPlan::whereNotNull('plan_details')->latest()->take(10)->get();
        $prodFlows = [];
        foreach ($prodPlans as $plan) {
            if (is_array($plan->plan_details)) {
                foreach ($plan->plan_details as $detail) {
                    $key = ($detail['machine'] ?? 'Bilinmiyor') . '|' . ($detail['product'] ?? 'Ürün');
                    if (!isset($prodFlows[$key]))
                        $prodFlows[$key] = 0;
                    $prodFlows[$key] += (int) ($detail['quantity'] ?? 1);
                }
            }
        }
        foreach (collect($prodFlows)->sortDesc()->take(5) as $key => $weight) {
            [$machine, $product] = explode('|', $key);
            $chartData[] = [Str::limit($machine, 15), Str::limit($product, 15), $weight];
        }

        // C) Bakım
        $maintenance = MaintenancePlan::with(['type', 'asset'])
            ->select('maintenance_type_id', 'maintenance_asset_id', DB::raw('count(*) as total'))
            ->groupBy('maintenance_type_id', 'maintenance_asset_id')->orderByDesc('total')->limit(5)->get();
        foreach ($maintenance as $flow) {
            if ($flow->type && $flow->asset)
                $chartData[] = [$flow->type->name, $flow->asset->name, (int) $flow->total];
        }

        // D) Araç
        $vehicles = VehicleAssignment::with('vehicle')
            ->select('vehicle_id', 'destination', DB::raw('count(*) as total'))
            ->whereNotNull('destination')->groupBy('vehicle_id', 'destination')->orderByDesc('total')->limit(5)->get();
        foreach ($vehicles as $flow) {
            if ($flow->vehicle)
                $chartData[] = [$flow->vehicle->plate_number, Str::limit($flow->destination, 15), (int) $flow->total];
        }

        if (empty($chartData))
            $chartData[] = ['Sistem', 'Veri Bekleniyor', 1];

        return $chartData;
    }

    /**
     * 4. SİSTEM HASH (Değişiklik Kontrolü)
     */
    private function getSystemDataHash()
    {
        $timestamps = [
            Shipment::max('updated_at'),
            ProductionPlan::max('updated_at'),
            Event::max('updated_at'),
            VehicleAssignment::max('updated_at'),
            MaintenancePlan::max('updated_at'),
            Travel::max('updated_at'),
            User::max('updated_at'),
        ];
        return md5(json_encode($timestamps));
    }
}