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
        $chartData = [];
        $user = Auth::user();

        // Filtreleme eklendi
        $shipmentQuery = Shipment::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereNotNull('cikis_tarihi')
            ->whereBetween('cikis_tarihi', [$startDate, $endDate]);

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

        // 3. Stratejik Veriler
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

        // 5. Filtreleme Listesi
        $shipmentsForFiltering = Shipment::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId)) // Filtre Eklendi
            ->select(['arac_tipi', 'kargo_icerigi', 'shipment_type'])
            ->whereNotNull('cikis_tarihi')
            ->whereBetween('cikis_tarihi', [$startDate, $endDate])
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
        $chartData = [];
        $user = Auth::user();

        // Filtre Eklendi
        $productionQuery = ProductionPlan::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('week_start_date', [$startDate, $endDate])
            ->whereNotNull('week_start_date');

        // 1. Haftalık Plan Sayısı
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

        // 2. Stratejik Veriler
        if ($viewLevel === 'full') {
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
            $chartData['monthly_prod'] = ['labels' => $monthlyLabels, 'data' => $monthlyData, 'title' => '🗓️ Aylık Üretim Planı Trendi'];
        } else {
            $chartData['monthly_prod'] = null;
        }

        // 3. Filtreleme Listesi
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

        return new StatisticsData(
            chartData: $chartData,
            productionPlansForFiltering: $flatDetails
        );
    }

    /**
     * HİZMET (İDARİ İŞLER) VERİLERİ
     */
    public function getHizmetStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $eventTypesList = $this->getEventTypes();

        // Helper fonksiyonlara $unitId gönderiyoruz
        $pieChartData = $this->getHizmetPieChartData($startDate, $endDate, $eventTypesList, $unitId);

        $chartData = [
            'event_type_pie' => $pieChartData,
        ];

        $eventsForFiltering = $this->getHizmetEventFilterData($startDate, $endDate, $eventTypesList, $unitId);

        return new StatisticsData(
            chartData: $chartData,
            eventsForFiltering: $eventsForFiltering,
            assignmentsForFiltering: [],
            vehiclesForFiltering: [],
            monthlyLabels: []
        );
    }

    /**
     * BAKIM VERİLERİ
     */
    public function getBakimStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();

        // Filtre Eklendi
        $maintenancePlans = MaintenancePlan::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('planned_start_date', [$startDate, $endDate])->get();

        $maintenanceTypes = MaintenanceType::select('id', 'name')->orderBy('name')->get()->toArray();
        $assets = MaintenanceAsset::select('id', 'name')->orderBy('name')->get()->toArray();

        // 1. Tür Dağılımı
        $typeCounts = $maintenancePlans->groupBy('maintenance_type_id')->map->count();
        $typeLabels = [];
        $typeData = [];
        foreach ($typeCounts as $typeId => $count) {
            $typeName = collect($maintenanceTypes)->firstWhere('id', $typeId)['name'] ?? 'Bilinmiyor';
            $typeLabels[] = $typeName;
            $typeData[] = $count;
        }

        // 2. Stratejik Veriler
        $assetLabels = [];
        $assetData = [];
        $monthlyLabels = [];
        $monthlyData = [];

        if ($viewLevel === 'full') {
            // En Çok Bakım Gören Varlıklar
            $assetCounts = $maintenancePlans->groupBy('maintenance_asset_id')->map->count()->sortDesc()->take(5);
            foreach ($assetCounts as $assetId => $count) {
                $assetName = collect($assets)->firstWhere('id', $assetId)['name'] ?? 'Bilinmiyor';
                $assetLabels[] = Str::limit($assetName, 20);
                $assetData[] = $count;
            }

            // Aylık Bakım Yükü
            $monthlyCounts = $maintenancePlans->groupBy(fn($d) => $d->planned_start_date->format('Y-m'))->map->count();
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

        // Filtre Eklendi
        $assignments = VehicleAssignment::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->with('vehicle')
            ->whereBetween('start_time', [$startDate, $endDate])
            ->get();

        // 2. Operasyonel Grafikler
        // A. Görev Durum Dağılımı (Pie Chart)
        $statusCounts = $assignments->groupBy('status')->map(function ($group) {
            return $group->count();
        });

        $statusLabels = $statusCounts->keys()->map(fn($s) => match ($s) {
            'pending' => 'Bekleyen',
            'approved' => 'Onaylı',
            'in_progress' => 'Sürüyor',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal',
            default => ucfirst($s)
        });

        $statusData = $statusCounts->values();
        $chartData = [
            'status_pie' => ['labels' => $statusLabels->all(), 'data' => $statusData->all(), 'title' => 'Görev Durumları'],
        ];

        // 3. Stratejik Grafikler
        if ($viewLevel === 'full') {
            // B. En Çok Kullanılan Araçlar
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

            // C. Aylık Görev Yoğunluğu
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

        // 4. Filtreleme Verileri
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
            vehiclesForFiltering: \App\Models\Vehicle::select('id', 'plate_number')->get()->toArray()
        );
    }

    // --- YARDIMCI METODLAR ---

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

        // Filtre Eklendi
        $eventsForFiltering = Event::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('start_datetime', [$startDate, $endDate])
            ->get(['event_type', 'location'])
            ->map(function ($event) use ($eventTypesList) {
                return [
                    'type_name' => $eventTypesList[$event->event_type] ?? ucfirst($event->event_type),
                    'type_slug' => $event->event_type,
                    'group' => 'Etkinlikler',
                ];
            });

        // Filtre Eklendi
        $travelsForFiltering = Travel::forUser($user)
            ->when($unitId, fn($q) => $q->where('business_unit_id', $unitId))
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get(['name'])
            ->map(function ($travel) {
                return [
                    'type_name' => 'Seyahat Planı',
                    'type_slug' => 'travel',
                    'group' => 'Seyahatler',
                ];
            });

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
}