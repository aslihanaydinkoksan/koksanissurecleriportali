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
        $shipmentGroups = Shipment::whereDate('tahmini_varis_tarihi', $today)
            ->select(['arac_tipi', DB::raw('count(*) as count')])
            ->groupBy('arac_tipi')
            ->get();

        $totalShipment = $shipmentGroups->sum('count');

        // Örn: "3 Gemi, 2 Kamyon" formatında string 
        $shipmentDetails = $shipmentGroups->map(function ($item) {
            $type = $item->arac_tipi ? Str::title($item->arac_tipi) : 'Diğer';
            return "{$item->count} {$type}";
        })->implode(', ');

        $eventGroups = Event::whereDate('start_datetime', $today)
            ->join('event_types', 'events.event_type_id', '=', 'event_types.id') // Tabloları birleştir
            ->select(['event_types.name as type_name', DB::raw('count(*) as count')])
            ->groupBy('event_types.name')
            ->get();

        $eventDetails = $eventGroups->map(function ($item) {
            // Artık direkt veritabanındaki ismi alıyoruz
            return "{$item->count} {$item->type_name}";
        })->implode(', ');

        return [
            'sevkiyat_sayisi' => $totalShipment,
            'sevkiyat_detay' => $shipmentDetails,
            'plan_sayisi' => ProductionPlan::whereDate('week_start_date', $today)->count(),
            'etkinlik_sayisi' => Event::whereDate('start_datetime', $today)->count(),
            'etkinlik_detay' => $eventDetails,
            'arac_gorevi_sayisi' => VehicleAssignment::whereDate('start_time', $today)
                ->whereIn('status', ['pending', 'in_progress'])->count(),
            'bakim_sayisi' => MaintenancePlan::whereDate('planned_start_date', $today)->count(),
            'kullanici_sayisi' => User::count(),
            'seyahat_sayisi' => Travel::whereDate('start_date', $today)->count()
        ];
    }

    /**
     * 2. ÖNEMLİ BİLDİRİMLERİ TOPLA (GÜNCELLENDİ)
     */
    private function getImportantItems(Carbon $today): Collection
    {
        $items = collect();

        // fetchImportant fonksiyonuna artık başlığı 'category_title' olarak hazırlatacağız.
        $items = $items->merge($this->fetchImportant(Shipment::class, 'tahmini_varis_tarihi', $today, 'kargo_icerigi', ' SEVKİYAT'));
        $items = $items->merge($this->fetchImportant(ProductionPlan::class, 'week_start_date', $today, 'plan_title', ' ÜRETİM'));
        $items = $items->merge($this->fetchImportant(Event::class, 'start_datetime', $today, 'title', ' ETKİNLİK'));
        $items = $items->merge($this->fetchImportant(VehicleAssignment::class, 'start_time', $today, 'task_description', ' ARAÇ GÖREVİ'));
        $items = $items->merge($this->fetchImportant(Travel::class, 'start_date', $today, 'name', ' SEYAHAT'));

        // Bakım Planı (Manuel ekleme devam ediyor)
        $maintenance = MaintenancePlan::whereIn('priority', ['high', 'critical'])
            ->where('planned_start_date', '>=', $today)
            ->get()
            ->map(fn($i) => (object) [
                'category' => 'BAKIM', // Üst Başlık
                'content' => $i->title, // Asıl İçerik
                'date' => $i->planned_start_date,
                'type' => 'maintenance'
            ]);

        $items = $items->merge($maintenance);

        return $items->sortBy('date')->take(6);
    }

    /**
     * YARDIMCI: Modellerden standart veri çekme (GÜNCELLENDİ)
     */
    private function fetchImportant($modelClass, $dateCol, $today, $titleCol, $categoryLabel)
    {
        return $modelClass::where('is_important', true)
            ->where($dateCol, '>=', $today)
            ->get()
            ->map(function ($i) use ($dateCol, $titleCol, $categoryLabel, $modelClass) {

                $finalCategory = $categoryLabel;

                // ÖZEL DURUM: Eğer bu bir SEVKİYAT ise, türünü başlığa ekle (Örn: SEVKİYAT (GEMİ))
                if ($modelClass === Shipment::class && !empty($i->arac_tipi)) {
                    $finalCategory .= ' (' . Str::upper($i->arac_tipi) . ')';
                }

                // ÖZEL DURUM: Eğer bu bir ETKİNLİK ise, türünü ekleyebiliriz (İsteğe bağlı, şu an kapalı)
                // if ($modelClass === Event::class && !empty($i->event_type)) { ... }
    
                return (object) [
                    'category' => $finalCategory,       // Örn: "SEVKİYAT (GEMİ)"
                    'content' => Str::limit($i->$titleCol, 30), // Örn: "Hammadde Transferi"
                    'date' => $i->$dateCol,
                    'type' => strtolower(class_basename($modelClass))
                ];
            });
    }

    /**
     * 3. SANKEY GRAFİK VERİSİ 
     */
    private function getSankeyData(): array
    {
        $data = [];
        // Kök düğümde toplam sayıya gerek yok, sadece isim kalsın veya istersen ekle
        $root = 'KÖKSAN';

        // 1. LOJİSTİK AKIŞI
        $logistics = Shipment::select(['arac_tipi', DB::raw('count(*) as count')])
            ->groupBy('arac_tipi')->get();

        if ($logistics->count() > 0) {
            $total = $logistics->sum('count');
            // İSMİ BURADA OLUŞTURUYORUZ: "LOJİSTİK (5)"
            $logisticsLabel = 'LOJİSTİK (' . $total . ')';

            $data[] = [$root, $logisticsLabel, (int) $total];

            foreach ($logistics as $item) {
                $subLabel = ($item->arac_tipi ? Str::upper($item->arac_tipi) : 'DİĞER') . ' (' . $item->count . ')';
                // Kaynak olarak yukarıdaki $logisticsLabel değişkenini kullanıyoruz
                $data[] = [$logisticsLabel, $subLabel, (int) $item->count];
            }
        }

        // 2. ÜRETİM AKIŞI
        $productions = ProductionPlan::latest()->take(5)->get();
        if ($productions->count() > 0) {
            $prodLabel = 'ÜRETİM (' . $productions->count() . ')';
            $data[] = [$root, $prodLabel, $productions->count()];

            foreach ($productions as $plan) {
                // Üretim detayında sayı genelde 1 olduğu için sayı yazmaya gerek yok, sadece isim
                $name = Str::limit($plan->plan_title, 15);
                $data[] = [$prodLabel, $name, 1];
            }
        }

        // 3. ETKİNLİK AKIŞI
        $events = Event::join('event_types', 'events.event_type_id', '=', 'event_types.id')
            ->select(['event_types.name', DB::raw('count(*) as count')])
            ->groupBy('event_types.name')->get();

        if ($events->count() > 0) {
            $totalEvent = $events->sum('count');
            $eventLabel = 'ETKİNLİK (' . $totalEvent . ')';
            $data[] = [$root, $eventLabel, (int) $totalEvent];

            foreach ($events as $event) {
                $subLabel = $event->name . ' (' . $event->count . ')';
                $data[] = [$eventLabel, $subLabel, (int) $event->count];
            }
        }

        // 4. SEYAHAT AKIŞI
        $travels = Travel::whereDate('start_date', Carbon::today())->get();
        if ($travels->count() > 0) {
            $travelLabel = 'SEYAHAT (' . $travels->count() . ')';
            $data[] = [$root, $travelLabel, $travels->count()];

            foreach ($travels as $travel) {
                $data[] = [$travelLabel, Str::limit($travel->name, 12), 1];
            }
        }

        // 5. TEKNİK AKIŞI
        $maintenances = MaintenancePlan::where('status', '!=', 'completed')->take(3)->get();
        if ($maintenances->count() > 0) {
            $techLabel = 'TEKNİK (' . $maintenances->count() . ')';
            $data[] = [$root, $techLabel, $maintenances->count()];
            foreach ($maintenances as $m) {
                $data[] = [$techLabel, Str::limit($m->title, 15), 1];
            }
        }

        if (empty($data)) {
            $data[] = [$root, 'Sistem Hazır', 1];
        }

        return $data;
    }

    /**
     * 4. SİSTEM HASH (Değişiklik Kontrolü)
     * Veritabanında bir hareket olup olmadığını kontrol eder.
     */
    private function getSystemDataHash()
    {
        // Takip edilecek tüm tabloların en son güncellenme zamanını alıyoruz
        $timestamps = [
            Shipment::max('updated_at'),
            ProductionPlan::max('updated_at'),
            Event::max('updated_at'),
            VehicleAssignment::max('updated_at'),
            MaintenancePlan::max('updated_at'),
            Travel::max('updated_at'),
            User::max('updated_at'),
            \App\Models\EventType::max('updated_at'),
        ];
        return md5(json_encode($timestamps));
    }
}