<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use App\Models\Travel;
use App\Models\MaintenancePlan;
use App\Models\MaintenanceType;
use App\Models\MaintenanceAsset;
use App\Models\Vehicle;
use App\Models\Department;
use App\Data\StatisticsData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class StatisticsService
{
    /**
     * GENEL BAKIŞ (Sadece Yöneticiler Görür)
     */
    public function getGenelBakisData(Carbon $startDate, Carbon $endDate, Collection $allowedDepartments, $unitId = null): StatisticsData
    {
        $labels = [];
        $data = [];
        $user = Auth::user();

        foreach ($allowedDepartments as $dept) {
            $slug = $dept->slug;
            $count = 0;

            // Her sorguya ->when($unitId...) eklendi
            if ($slug === 'lojistik') {
                $count = Shipment::forUser($user)
                    ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            } elseif ($slug === 'uretim') {
                $count = ProductionPlan::forUser($user)
                    ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
                    ->whereBetween('week_start_date', [$startDate, $endDate])
                    ->count();
            } elseif ($slug === 'hizmet') {
                $events = Event::forUser($user)
                    ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
                    ->whereBetween('start_datetime', [$startDate, $endDate])
                    ->count();

                $travels = Travel::forUser($user)
                    ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->count();

                $count = $events + $travels;
            } elseif ($slug === 'bakim') {
                $count = MaintenancePlan::forUser($user)
                    ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
                    ->whereBetween('planned_start_date', [$startDate, $endDate])
                    ->count();
            } elseif ($slug === 'ulastirma') {
                // Ulaştırma birimi için Araç Atamaları (VehicleAssignment) sayısını alıyoruz
                $count = VehicleAssignment::forUser($user)
                    ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
                    ->whereBetween('start_time', [$startDate, $endDate])
                    ->count();
            }

            $labels[] = $dept->name;
            $data[] = $count;
        }

        $chartData = [
            'departmentSummary' => [
                'title' => 'Departman Bazlı Aktivite Dağılımı',
                'labels' => $labels,
                'data' => $data
            ]
        ];

        return new StatisticsData(
            chartData: $chartData,
            shipmentsForFiltering: [],
            productionPlansForFiltering: [],
            eventsForFiltering: [],
            assignmentsForFiltering: [],
            vehiclesForFiltering: [],
            monthlyLabels: [],
            maintenancePlansForFiltering: [],
            maintenanceTypes: [],
            assets: []
        );
    }

    /**
     * LOJİSTİK VERİLERİ
     */
    public function getLojistikStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();

        // 1. Ana Sorgu (Helper'a gönderilecek)
        $shipmentQuery = Shipment::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereNotNull('cikis_tarihi')
            ->whereBetween('cikis_tarihi', [$startDate, $endDate]);

        // 2. Grafikleri Hazırla (Helper Metod)
        $chartData = $this->prepareLojistikCharts($shipmentQuery, $viewLevel);

        // 3. Filtreleme Listesi (Burada kalabilir)
        $shipmentsForFiltering = (clone $shipmentQuery)
            ->select(['arac_tipi', 'kargo_icerigi', 'shipment_type'])
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

    /**
     * ÜRETİM VERİLERİ
     */
    public function getUretimStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();

        $productionQuery = ProductionPlan::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('week_start_date', [$startDate, $endDate])
            ->whereNotNull('week_start_date');

        // 1. Trend Grafiklerini Hazırla (Haftalık/Aylık)
        $chartData = $this->prepareProductionBaseCharts($productionQuery, $startDate, $endDate, $viewLevel);

        // 2. Detaylı Veri Aggregation (Makine ve Ürün Dağılımı)
        $detailsData = $this->aggregateProductionDetails($productionQuery);

        // Verileri birleştir
        $chartData = array_merge($chartData, $detailsData['charts']);

        return new StatisticsData(
            chartData: $chartData,
            productionPlansForFiltering: $detailsData['flatDetails']
        );
    }

    /**
     * HİZMET (İDARİ İŞLER) VERİLERİ - Geliştirilmiş
     */
    public function getHizmetStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();
        $eventTypesList = $this->getEventTypes();

        // 1. Etkinlik Dağılımı (Mevcut)
        $pieChartData = $this->getHizmetPieChartData($startDate, $endDate, $eventTypesList, $unitId);

        // 2. Masraf Analizi (YENİ)
        // Masrafları, bağlı oldukları modelin business_unit_id'sine göre filtreleyerek çekiyoruz
        $expenses = \App\Models\Expense::whereBetween('receipt_date', [$startDate, $endDate])
            ->where(function ($query) use ($unitId) {
                $query->whereHasMorph('expensable', [\App\Models\Travel::class, \App\Models\Event::class], function ($q) use ($unitId) {
                    $q->when($unitId, fn($sq) => $sq->where('business_unit_id', $unitId));
                });
            })->get();

        // Para birimi bazlı toplamlar (Bar Grafiği için)
        $currencySummary = $expenses->groupBy('currency')->map(fn($group) => $group->sum('amount'));

        // Kategori bazlı toplamlar (Pasta Grafiği için)
        // NOT: Kategorileri daha şık görünmesi için normalize ediyoruz
        $categorySummary = $expenses->groupBy('category')->map(fn($group) => $group->sum('amount'))->sortDesc();

        $chartData = [
            'event_type_pie' => $pieChartData,
            'expense_currency' => [
                'labels' => $currencySummary->keys()->all(),
                'data' => $currencySummary->values()->all(),
                'title' => '💰 Harcama Özeti (Para Birimi)'
            ],
            'expense_categories' => [
                'labels' => $categorySummary->keys()->map(fn($c) => ucfirst($c))->all(),
                'data' => $categorySummary->values()->all(),
                'title' => '📂 Masraf Kategorileri Dağılımı'
            ]
        ];

        $eventsForFiltering = $this->getHizmetEventFilterData($startDate, $endDate, $eventTypesList, $unitId);

        return new StatisticsData(
            chartData: $chartData,
            eventsForFiltering: $eventsForFiltering
        );
    }

    /**
     * BAKIM VERİLERİ
     */
    public function getBakimStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();

        $maintenancePlans = MaintenancePlan::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('planned_start_date', [$startDate, $endDate])->get();

        $maintenanceTypes = MaintenanceType::select('id', 'name')->orderBy('name')->get()->toArray();
        $assets = MaintenanceAsset::select('id', 'name')->orderBy('name')->get()->toArray();

        // DÜZELTME 1: ->map->count() yerine Closure kullanımı
        // VS Code bunu artık bir Collection olarak tanır, integer olarak değil.
        $typeCounts = $maintenancePlans->groupBy('maintenance_type_id')
            ->map(fn($group) => $group->count());

        $typeLabels = [];
        $typeData = [];
        foreach ($typeCounts as $typeId => $count) {
            $foundType = collect($maintenanceTypes)->firstWhere('id', $typeId);
            $typeName = $foundType ? $foundType['name'] : 'Bilinmiyor';
            $typeLabels[] = $typeName;
            $typeData[] = $count;
        }

        // 2. Stratejik Veriler
        $assetLabels = [];
        $assetData = [];
        $monthlyLabels = [];
        $monthlyData = [];

        if ($viewLevel === 'full') {
            // DÜZELTME 2: ->map->count() yine açık hale getirildi
            // sortDesc() hatası burada düzelir.
            $assetCounts = $maintenancePlans->groupBy('maintenance_asset_id')
                ->map(fn($group) => $group->count())
                ->sortDesc()
                ->take(5);

            foreach ($assetCounts as $assetId => $count) {
                $foundAsset = collect($assets)->firstWhere('id', $assetId);
                $assetName = $foundAsset ? $foundAsset['name'] : 'Bilinmiyor';
                $assetLabels[] = Str::limit($assetName, 20);
                $assetData[] = $count;
            }

            // Aylık Bakım Yükü
            $monthlyCounts = $maintenancePlans->groupBy(fn($d) => $d->planned_start_date->format('Y-m'))
                ->map(fn($group) => $group->count()); // Burası zaten map içinde ama garanti olsun diye fn ile yazdım

            $currentMonth = $startDate->copy()->startOfMonth();
            while ($currentMonth->lte($endDate)) {
                $key = $currentMonth->format('Y-m');
                $monthlyLabels[] = $currentMonth->translatedFormat('M Y');
                $monthlyData[] = $monthlyCounts[$key] ?? 0;
                $currentMonth->addMonth();
            }
        }

        $chartData = [
            'type_dist' => ['labels' => $typeLabels, 'data' => $typeData],
            'top_assets' => ($viewLevel === 'full') ? ['labels' => $assetLabels, 'data' => $assetData, 'title' => '⚠️ En Sık Arızalananlar'] : null,
            'monthly_maintenance' => ($viewLevel === 'full') ? ['labels' => $monthlyLabels, 'data' => $monthlyData, 'title' => '📅 Aylık Bakım Yükü'] : null,
        ];

        // 3. Filtreleme Verisi
        $maintenancePlansForFiltering = $maintenancePlans->map(fn($m) => [
            'type_id' => $m->maintenance_type_id,
            'asset_id' => $m->maintenance_asset_id,
            'status' => $m->status
        ])->values()->all();

        return new StatisticsData(
            chartData: $chartData,
            maintenancePlansForFiltering: $maintenancePlansForFiltering,
            maintenanceTypes: $maintenanceTypes,
            assets: $assets
        );
    }

    /**
     * ULAŞTIRMA DEPARTMANI VERİLERİ
     */
    public function getUlastirmaStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();

        // 1. Veriyi Çek
        $assignments = VehicleAssignment::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->with('vehicle')
            ->whereBetween('start_time', [$startDate, $endDate])
            ->get();

        // 2. Grafikleri Hazırla (Yardımcı metoda taşıdık)
        $chartData = $this->prepareUlastirmaCharts($assignments, $startDate, $endDate, $viewLevel);

        // 3. Filtreleme Verilerini Hazırla
        $assignmentsForFiltering = $assignments->map(function ($a) {
            return [
                'vehicle_plate' => $a->vehicle->plate_number ?? 'Bilinmiyor',
                'driver_name' => $a->driver_name ?? 'Atanmadı',
                'status' => $a->status
            ];
        })->values()->all();

        return new StatisticsData(
            chartData: $chartData,
            assignmentsForFiltering: $assignmentsForFiltering,
            vehiclesForFiltering: Vehicle::select('id', 'plate_number')->get()->toArray()
        );
    }

    // --- YARDIMCI METODLAR ---
    /**
     * Üretim Planı Detaylarını (JSON) Kümeleyen Yardımcı Metod
     */
    private function aggregateProductionDetails($query): array
    {
        $allPlansRaw = (clone $query)->whereNotNull('plan_details')->get(['plan_details']);
        $flatDetails = [];

        foreach ($allPlansRaw as $plan) {
            if (is_array($plan->plan_details)) {
                foreach ($plan->plan_details as $detail) {
                    $machine = trim(strval($detail['machine'] ?? 'Bilinmiyor'));
                    $product = is_numeric($detail['product'] ?? 'Bilinmiyor') ? 'Ürün-' . $detail['product'] : trim(strval($detail['product'] ?? 'Bilinmiyor'));
                    // Miktar yoksa 1 kabul et (Adet sayımı için)
                    $qty = isset($detail['quantity']) && is_numeric($detail['quantity']) ? (int) $detail['quantity'] : 1;

                    if ($machine !== 'Bilinmiyor' || $product !== 'Bilinmiyor') {
                        $flatDetails[] = [
                            'machine' => $machine,
                            'product' => $product,
                            'quantity' => $qty
                        ];
                    }
                }
            }
        }

        $col = collect($flatDetails);

        // Makine Kullanımı
        $machineUsage = $col->groupBy('machine')->map(fn($g) => $g->sum('quantity'))->sortDesc()->take(10);

        // Ürün Dağılımı
        $productDist = $col->groupBy('product')->map(fn($g) => $g->sum('quantity'))->sortDesc()->take(10);

        return [
            'flatDetails' => $flatDetails,
            'charts' => [
                'machine_usage' => [
                    'labels' => $machineUsage->keys()->all(),
                    'data' => $machineUsage->values()->all(),
                    'title' => '⚙️ Makine Kullanım Yoğunluğu'
                ],
                'product_dist' => [
                    'labels' => $productDist->keys()->all(),
                    'data' => $productDist->values()->all(),
                    'title' => '📊 Üretilen Ürün Dağılımı'
                ]
            ]
        ];
    }

    /**
     * Haftalık ve Aylık Trend Grafiklerini Hazırlayan Eksik Metod
     */
    private function prepareProductionBaseCharts($query, $startDate, $endDate, $viewLevel): array
    {
        $charts = [];

        // Haftalık Plan Sayısı
        $weeklyCounts = (clone $query)->select([
            DB::raw('YEARWEEK(week_start_date, 1) as year_week'),
            DB::raw('COUNT(*) as count')
        ])->groupBy('year_week')->orderBy('year_week')->pluck('count', 'year_week');

        $wLabels = [];
        $wData = [];
        $curr = $startDate->copy()->startOfWeek();
        while ($curr->lte($endDate)) {
            $key = $curr->format('oW');
            $wLabels[] = $curr->format('W') . '. Hafta';
            $wData[] = $weeklyCounts[$key] ?? 0;
            $curr->addWeek();
        }
        $charts['weekly_prod'] = ['labels' => $wLabels, 'data' => $wData, 'title' => '📅 Haftalık Üretim Planı Sayısı'];

        // Aylık Trend (Full View)
        if ($viewLevel === 'full') {
            $monthlyCounts = (clone $query)->select([
                DB::raw('YEAR(week_start_date) as year'),
                DB::raw('MONTH(week_start_date) as month'),
                DB::raw('COUNT(*) as count')
            ])->groupBy('year', 'month')->get();

            $mLabels = [];
            $mData = [];
            $currM = $startDate->copy()->startOfMonth();
            while ($currM->lte($endDate)) {
                $count = $monthlyCounts->where('year', $currM->year)->where('month', $currM->month)->first()?->count ?? 0;
                $mLabels[] = $currM->translatedFormat('M Y');
                $mData[] = $count;
                $currM->addMonth();
            }
            $charts['monthly_prod'] = ['labels' => $mLabels, 'data' => $mData, 'title' => '🗓️ Aylık Üretim Trendi'];
        }

        return $charts;
    }

    public function getHizmetPieChartData($startDate, $endDate, array $eventTypesList, $unitId = null): array
    {
        $user = Auth::user();

        // Filtre Eklendi
        $eventTypeCounts = Event::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->select(['event_type', DB::raw('COUNT(*) as count')])
            ->whereNotNull('event_type')
            ->whereBetween('start_datetime', [$startDate, $endDate])
            ->groupBy('event_type')->pluck('count', 'event_type')
            ->mapWithKeys(function ($count, $key) use ($eventTypesList) {
                return [$eventTypesList[$key] ?? ucfirst($key) => $count];
            });

        // Filtre Eklendi
        $travelCount = Travel::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('start_date', [$startDate, $endDate])->count();

        if ($travelCount > 0) {
            $eventTypeCounts['Seyahat Planı'] = $travelCount;
        }

        return [
            'labels' => $eventTypeCounts->keys()->all(),
            'data' => $eventTypeCounts->values()->all(),
            'title' => 'Etkinlik ve Seyahat Dağılımı'
        ];
    }

    public function getHizmetEventFilterData($startDate, $endDate, array $eventTypesList, $unitId = null): array
    {
        $user = Auth::user();

        $eventsForFiltering = Event::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('start_datetime', [$startDate, $endDate])
            ->get(['event_type', 'location'])
            ->toBase() // <--- Eloquent Collection'ı temel Collection'a çeviriyoruz
            ->map(function ($event) use ($eventTypesList) {
                return [
                    'type_name' => $eventTypesList[$event->event_type] ?? ucfirst($event->event_type),
                    'type_slug' => $event->event_type,
                    'group' => 'Etkinlikler',
                ];
            });

        $travelsForFiltering = Travel::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get(['name'])
            ->toBase() // <--- Güvenlik için burada da çeviriyoruz
            ->map(function ($travel) {
                return [
                    'type_name' => 'Seyahat Planı',
                    'type_slug' => 'travel',
                    'group' => 'Seyahatler',
                ];
            });

        // Artık iki koleksiyon da standart Support\Collection olduğu için merge sorunsuz çalışacaktır.
        return $eventsForFiltering->merge($travelsForFiltering)->all();
    }

    // Normalizasyon ve Statik Veriler (Değişiklik Yok)
    public function getHizmetVehicleFilterData(): array
    {
        return Vehicle::orderBy('plate_number')->get(['id', 'plate_number'])->all();
    }
    public function normalizeCargoContent($cargo)
    {
        if (empty($cargo))
            return 'Bilinmiyor';
        $normalized = mb_strtoupper(trim($cargo), 'UTF-8');
        $normalized = Str::ascii($normalized);
        $specialCases = ['LEVBA' => 'LEVHA', 'LEVBE' => 'LEVHA', 'PLASTIC' => 'PLASTİK', 'KAPAK' => 'KAPAK', 'PLASTİK' => 'PLASTİK', 'LEVHA' => 'LEVHA'];
        return $specialCases[$normalized] ?? $normalized;
    }
    public function normalizeVehicleType($vehicle)
    {
        if (empty($vehicle))
            return 'Bilinmiyor';
        $normalized = mb_strtoupper(trim($vehicle), 'UTF-8');
        $vehicleMapping = ['TIR' => 'TIR', 'TİR' => 'TIR', 'TRUCK' => 'TIR', 'GEMI' => 'GEMI', 'GEMİ' => 'GEMI', 'SHIP' => 'GEMI', 'KAMYON' => 'KAMYON', 'TRUCK_SMALL' => 'KAMYON', 'KAMYONET' => 'KAMYON'];
        return $vehicleMapping[$normalized] ?? $normalized;
    }
    public function getEventTypes()
    {
        return ['toplanti' => 'Toplantı', 'egitim' => 'Eğitim', 'fuar' => 'Fuar', 'gezi' => 'Gezi', 'musteri_ziyareti' => 'Müşteri Ziyareti', 'misafir_karsilama' => 'Misafir Karşılama', 'diger' => 'Diğer'];
    }
    /**
     * Ulaştırma grafikleri için yardımcı metod
     */
    private function prepareUlastirmaCharts($assignments, $startDate, $endDate, $viewLevel): array
    {
        // A. Görev Durum Dağılımı (Pie Chart)
        $statusCounts = $assignments->groupBy('status')->map(fn($group) => $group->count());

        $statusLabels = $statusCounts->keys()->map(fn($s) => match ($s) {
            'pending' => 'Bekleyen',
            'approved' => 'Onaylı',
            'in_progress' => 'Sürüyor',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal',
            default => ucfirst($s)
        });

        $chartData = [
            'status_pie' => ['labels' => $statusLabels->all(), 'data' => $statusCounts->values()->all(), 'title' => 'Görev Durumları'],
        ];

        // B. Stratejik Grafikler
        if ($viewLevel === 'full') {
            // En Çok Kullanılan Araçlar
            $vehicleUsage = $assignments->groupBy('vehicle_id')
                ->map(fn($group) => $group->count())
                ->sortDesc()
                ->take(5);

            $vehicleLabels = [];
            $vehicleData = [];

            foreach ($vehicleUsage as $vehId => $count) {
                $assign = $assignments->firstWhere('vehicle_id', $vehId);
                $vehicleLabels[] = $assign->vehicle->plate_number ?? 'Bilinmiyor';
                $vehicleData[] = $count;
            }

            $chartData['top_vehicles'] = ['labels' => $vehicleLabels, 'data' => $vehicleData, 'title' => '🚗 En Çok Görev Yapan Araçlar'];

            // Aylık Görev Yoğunluğu
            $monthlyCounts = $assignments->groupBy(fn($d) => $d->start_time->format('Y-m'))
                ->map(fn($group) => $group->count());

            $monthlyLabels = [];
            $monthlyData = [];
            $currentMonth = $startDate->copy()->startOfMonth();

            while ($currentMonth->lte($endDate)) {
                $key = $currentMonth->format('Y-m');
                $monthlyLabels[] = $currentMonth->translatedFormat('M Y');
                $monthlyData[] = $monthlyCounts[$key] ?? 0;
                $currentMonth->addMonth();
            }
            $chartData['monthly_trend'] = ['labels' => $monthlyLabels, 'data' => $monthlyData, 'title' => '📅 Aylık Görev Grafiği'];
        } else {
            $chartData['top_vehicles'] = null;
            $chartData['monthly_trend'] = null;
        }

        return $chartData;
    }
    /**
     * Lojistik grafikleri için hesaplama yapan yardımcı metod
     */
    private function prepareLojistikCharts($shipmentQuery, string $viewLevel): array
    {
        $chartData = [];

        // 1. Saatlik Yoğunluk
        $hourlyLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));
        $hourlyCounts = array_fill_keys(range(0, 23), 0);
        $hourlyDbData = (clone $shipmentQuery)->select([DB::raw('HOUR(cikis_tarihi) as hour'), DB::raw('COUNT(*) as count')])->groupBy('hour')->pluck('count', 'hour');

        foreach ($hourlyDbData as $hour => $count) {
            if (isset($hourlyCounts[$hour]))
                $hourlyCounts[$hour] = $count;
        }
        $chartData['hourly'] = ['labels' => $hourlyLabels, 'data' => array_values($hourlyCounts), 'title' => '⏰ Saatlik Sevkiyat Yoğunluğu'];

        // 2. Haftalık Yoğunluk
        $dayLabels = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
        $dayCounts = array_fill(0, 7, 0);
        $dayMap = [2 => 0, 3 => 1, 4 => 2, 5 => 3, 6 => 4, 7 => 5, 1 => 6];
        $dailyDbData = (clone $shipmentQuery)->select([DB::raw('DAYOFWEEK(cikis_tarihi) as day_of_week'), DB::raw('COUNT(*) as count')])->groupBy('day_of_week')->pluck('count', 'day_of_week');

        foreach ($dailyDbData as $dayNum => $count) {
            if (isset($dayMap[$dayNum]))
                $dayCounts[$dayMap[$dayNum]] = $count;
        }
        $chartData['daily'] = ['labels' => $dayLabels, 'data' => $dayCounts, 'title' => '📅 Haftalık Sevkiyat Yoğunluğu'];

        // 3. Stratejik Veriler (Full View)
        if ($viewLevel === 'full') {
            // Aylık
            $monthLabels = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
            $monthCounts = array_fill(0, 12, 0);
            $monthlyDbData = (clone $shipmentQuery)->select([DB::raw('MONTH(cikis_tarihi) as month'), DB::raw('COUNT(*) as count')])->groupBy('month')->pluck('count', 'month');

            foreach ($monthlyDbData as $monthNum => $count) {
                if ($monthNum >= 1 && $monthNum <= 12)
                    $monthCounts[$monthNum - 1] = $count;
            }
            $chartData['monthly'] = ['labels' => $monthLabels, 'data' => $monthCounts, 'title' => 'Aylık Sevkiyat Dağılımı'];

            // Yıllık
            $yearlyDbData = (clone $shipmentQuery)->select([DB::raw('YEAR(cikis_tarihi) as year'), DB::raw('COUNT(*) as count')])->groupBy('year')->orderBy('year')->pluck('count', 'year');
            $chartData['yearly'] = ['labels' => $yearlyDbData->keys()->map(fn($y) => (string) $y)->all(), 'data' => $yearlyDbData->values()->all(), 'title' => 'Yıllık Toplam Sevkiyat'];
        } else {
            $chartData['monthly'] = null;
            $chartData['yearly'] = null;
        }

        // 4. Araç Tipi Pasta Grafiği
        $vehicleTypeData = (clone $shipmentQuery)->select(['arac_tipi', DB::raw('COUNT(*) as count')])->whereNotNull('arac_tipi')->groupBy('arac_tipi')->get()
            ->groupBy(fn($item) => $this->normalizeVehicleType($item->arac_tipi))
            ->map(fn($group) => $group->sum('count'));

        $chartData['pie'] = ['labels' => $vehicleTypeData->keys()->map(fn($tip) => $tip ?? 'Bilinmiyor')->all(), 'data' => $vehicleTypeData->values()->all(), 'title' => 'Araç Tipi Dağılımı'];
        $cargoData = (clone $shipmentQuery)
            ->select(['kargo_icerigi', DB::raw('COUNT(*) as count')])
            ->whereNotNull('kargo_icerigi')
            ->groupBy('kargo_icerigi')
            ->get()
            // Daha önce tanımladığın normalizasyon metodunu kullanıyoruz
            ->groupBy(fn($item) => $this->normalizeCargoContent($item->kargo_icerigi))
            ->map(fn($group) => $group->sum('count'))
            ->sortDesc()
            ->take(10); // İlk 10 içeriği getir

        $chartData['cargo_pie'] = [
            'labels' => $cargoData->keys()->all(),
            'data' => $cargoData->values()->all(),
            'title' => '📦 Kargo İçeriği Dağılımı'
        ];

        return $chartData;
    }
}