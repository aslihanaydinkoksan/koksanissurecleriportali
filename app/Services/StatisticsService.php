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
use App\Models\Expense; // Eklendi
use App\Data\StatisticsData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\Opportunity;
use App\Models\CustomerProduct;
use App\Models\Complaint;
use App\Models\CustomerActivity;

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

            // PHP6613 Hatasını önlemek için when() closure'ları if bloklarına dönüştürüldü
            if ($slug === 'lojistik') {
                $query = Shipment::forUser($user)->whereBetween('created_at', [$startDate, $endDate]);
                if ($unitId) {
                    $query->where('business_unit_id', $unitId);
                }
                $count = $query->count();
            } elseif ($slug === 'uretim') {
                $query = ProductionPlan::forUser($user)->whereBetween('week_start_date', [$startDate, $endDate]);
                if ($unitId) {
                    $query->where('business_unit_id', $unitId);
                }
                $count = $query->count();
            } elseif ($slug === 'hizmet') {
                $q1 = Event::forUser($user)->whereBetween('start_datetime', [$startDate, $endDate]);
                if ($unitId) {
                    $q1->where('business_unit_id', $unitId);
                }
                $events = $q1->count();

                $q2 = Travel::forUser($user)->whereBetween('start_date', [$startDate, $endDate]);
                if ($unitId) {
                    $q2->where('business_unit_id', $unitId);
                }
                $travels = $q2->count();

                $q3 = CustomerActivity::whereBetween('activity_date', [$startDate, $endDate]);
                if ($unitId) {
                    $q3->where('business_unit_id', $unitId);
                }
                $crmActivities = $q3->count();

                $count = $events + $travels + $crmActivities;
            } elseif ($slug === 'bakim') {
                $query = MaintenancePlan::forUser($user)->whereBetween('planned_start_date', [$startDate, $endDate]);
                if ($unitId) {
                    $query->where('business_unit_id', $unitId);
                }
                $count = $query->count();
            } elseif ($slug === 'ulastirma') {
                $query = VehicleAssignment::forUser($user)->whereBetween('start_time', [$startDate, $endDate]);
                if ($unitId) {
                    $query->where('business_unit_id', $unitId);
                }
                $count = $query->count();
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

        $shipmentQuery = Shipment::forUser($user)
            ->whereNotNull('cikis_tarihi')
            ->whereBetween('cikis_tarihi', [$startDate, $endDate]);

        if ($unitId) {
            $shipmentQuery->where('business_unit_id', $unitId);
        }

        $chartData = $this->prepareLojistikCharts($shipmentQuery, $viewLevel);

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
            ->whereBetween('week_start_date', [$startDate, $endDate])
            ->whereNotNull('week_start_date');

        if ($unitId) {
            $productionQuery->where('business_unit_id', $unitId);
        }

        $chartData = $this->prepareProductionBaseCharts($productionQuery, $startDate, $endDate, $viewLevel);
        $detailsData = $this->aggregateProductionDetails($productionQuery);

        $chartData = array_merge($chartData, $detailsData['charts']);

        return new StatisticsData(
            chartData: $chartData,
            productionPlansForFiltering: $detailsData['flatDetails']
        );
    }

    /**
     * HİZMET (İDARİ İŞLER & CRM) VERİLERİ 
     */
    public function getHizmetStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();
        $eventTypesList = $this->getEventTypes();

        // 1. Etkinlik Dağılımı
        $pieChartData = $this->getHizmetPieChartData($startDate, $endDate, $eventTypesList, $unitId);

        // 2. Masraf Analizi - PHP6601 Hatası için Event::class ve Travel::class kısaltıldı
        $expensesQuery = Expense::whereBetween('receipt_date', [$startDate, $endDate]);

        $expensesQuery->where(function ($query) use ($unitId) {
            $query->whereHasMorph('expensable', [Travel::class, Event::class], function ($q) use ($unitId) {
                if ($unitId) {
                    $q->where('business_unit_id', $unitId);
                }
            });
        });

        $expenses = $expensesQuery->get();

        $currencySummary = $expenses->groupBy('currency')->map(fn($group) => $group->sum('amount'));
        $categorySummary = $expenses->groupBy('category')->map(fn($group) => $group->sum('amount'))->sortDesc();

        // 3. CRM & MÜŞTERİ YÖNETİMİ VERİLERİ
        $crmCharts = $this->prepareCrmCharts($startDate, $endDate, $unitId);

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
            ],

            // CRM Grafikleri
            'crm_funnel' => $crmCharts['funnel'],
            'crm_competitors' => $crmCharts['competitors'],
            'crm_complaints' => $crmCharts['complaints'],
            'crm_personnel' => $crmCharts['personnel'],
        ];

        $eventsForFiltering = $this->getHizmetEventFilterData($startDate, $endDate, $eventTypesList, $unitId);

        return new StatisticsData(
            chartData: $chartData,
            eventsForFiltering: $eventsForFiltering
        );
    }

    /**
     * CRM İstatistiklerini Hazırlayan Yardımcı Metod
     */
    private function prepareCrmCharts($startDate, $endDate, $unitId): array
    {
        // 1. Fırsat Hunisi
        $oppQuery = Opportunity::whereBetween('created_at', [$startDate, $endDate]);
        if ($unitId) {
            $oppQuery->whereHas('customer', function ($cq) use ($unitId) {
                $cq->where('business_unit_id', $unitId);
            });
        }

        $opportunities = $oppQuery->select('stage', DB::raw('count(*) as count'))
            ->groupBy('stage')
            ->pluck('count', 'stage')
            ->toArray();

        $funnelKeys = ['duyum', 'teklif', 'gorusme', 'kazanildi', 'kaybedildi'];
        $funnelData = [];
        foreach ($funnelKeys as $key) {
            $funnelData[] = $opportunities[$key] ?? 0;
        }

        // 2. Rakip Pazar Payı Analizi
        $compQuery = CustomerProduct::with('competitor')
            ->where('supplier_type', 'competitor')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($unitId) {
            $compQuery->where('business_unit_id', $unitId);
        }

        $competitorShare = $compQuery->select('competitor_id', DB::raw('count(*) as count'))
            ->groupBy('competitor_id')
            ->get();

        $compLabels = [];
        $compData = [];
        foreach ($competitorShare as $comp) {
            $compLabels[] = $comp->competitor->name ?? 'Bilinmeyen Rakip';
            $compData[] = $comp->count;
        }

        // 3. Aylık Şikayet Trendi
        $months = collect(range(5, 0))->map(fn($i) => Carbon::now()->subMonths($i)->format('Y-m'))->toArray();

        $compTrendQuery = Complaint::where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth());
        if ($unitId) {
            $compTrendQuery->where('business_unit_id', $unitId);
        }

        $complaints = $compTrendQuery->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), 'status', DB::raw('count(*) as count'))
            ->groupBy('month', 'status')
            ->get();

        $complaintLabels = [];
        $openComplaints = [];
        $resolvedComplaints = [];
        foreach ($months as $month) {
            $complaintLabels[] = Carbon::parse($month . '-01')->translatedFormat('M Y');
            $openComplaints[] = $complaints->where('month', $month)->where('status', 'open')->sum('count') +
                $complaints->where('month', $month)->where('status', 'in_progress')->sum('count');
            $resolvedComplaints[] = $complaints->where('month', $month)->where('status', 'resolved')->sum('count');
        }

        // 4. Personel Performansı
        $actQuery = CustomerActivity::with('user')->whereBetween('activity_date', [$startDate, $endDate]);
        if ($unitId) {
            $actQuery->where('business_unit_id', $unitId);
        }

        $activities = $actQuery->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->take(8)
            ->get();

        $userLabels = [];
        $userData = [];
        foreach ($activities as $act) {
            $userLabels[] = $act->user->name ?? 'Bilinmiyor';
            $userData[] = $act->count;
        }

        return [
            'funnel' => ['labels' => ['Duyum', 'Teklif', 'Görüşme', 'Kazanıldı', 'Kaybedildi'], 'data' => $funnelData],
            'competitors' => ['labels' => $compLabels, 'data' => $compData],
            'complaints' => ['labels' => $complaintLabels, 'open' => $openComplaints, 'resolved' => $resolvedComplaints],
            'personnel' => ['labels' => $userLabels, 'data' => $userData]
        ];
    }

    /**
     * BAKIM VERİLERİ
     */
    public function getBakimStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();

        $maintQuery = MaintenancePlan::forUser($user)->whereBetween('planned_start_date', [$startDate, $endDate]);

        if ($unitId) {
            $maintQuery->where('business_unit_id', $unitId);
        }

        $maintenancePlans = $maintQuery->get();

        $maintenanceTypes = MaintenanceType::select('id', 'name')->orderBy('name')->get()->toArray();
        $assets = MaintenanceAsset::select('id', 'name')->orderBy('name')->get()->toArray();

        // ÇÖZÜM (PHP6613): Grafik verilerini hazırlama işlemini ayrı bir metoda taşıdık.
        $chartData = $this->prepareBakimCharts($maintenancePlans, $maintenanceTypes, $assets, $startDate, $endDate, $viewLevel);

        // 3. Filtreleme Verisi
        $maintenancePlansForFiltering = $maintenancePlans->map(function ($m) {
            return [
                'type_id' => $m->maintenance_type_id,
                'asset_id' => $m->maintenance_asset_id,
                'status' => $m->status
            ];
        })->values()->all();

        return new StatisticsData(
            chartData: $chartData,
            maintenancePlansForFiltering: $maintenancePlansForFiltering,
            maintenanceTypes: $maintenanceTypes,
            assets: $assets
        );
    }

    /**
     * Bakım grafikleri için karmaşıklığı azaltan yardımcı metod
     */
    private function prepareBakimCharts($maintenancePlans, $maintenanceTypes, $assets, $startDate, $endDate, $viewLevel): array
    {
        $typeCounts = $maintenancePlans->groupBy('maintenance_type_id')->map(function ($group) {
            return $group->count();
        });

        $typeLabels = [];
        $typeData = [];
        foreach ($typeCounts as $typeId => $count) {
            $foundType = collect($maintenanceTypes)->firstWhere('id', $typeId);
            $typeLabels[] = $foundType ? $foundType['name'] : 'Bilinmiyor';
            $typeData[] = $count;
        }

        $chartData = [
            'type_dist' => ['labels' => $typeLabels, 'data' => $typeData],
            'top_assets' => null,
            'monthly_maintenance' => null,
        ];

        // 2. Stratejik Veriler
        if ($viewLevel === 'full') {
            $assetCounts = $maintenancePlans->groupBy('maintenance_asset_id')
                ->map(function ($group) {
                    return $group->count();
                })
                ->sortDesc()
                ->take(5);

            $assetLabels = [];
            $assetData = [];
            foreach ($assetCounts as $assetId => $count) {
                $foundAsset = collect($assets)->firstWhere('id', $assetId);
                $assetName = $foundAsset ? $foundAsset['name'] : 'Bilinmiyor';
                $assetLabels[] = Str::limit($assetName, 20);
                $assetData[] = $count;
            }

            // Aylık Bakım Yükü
            $monthlyCounts = $maintenancePlans->groupBy(function ($d) {
                return $d->planned_start_date->format('Y-m');
            })->map(function ($group) {
                return $group->count();
            });

            $monthlyLabels = [];
            $monthlyData = [];
            $currentMonth = $startDate->copy()->startOfMonth();
            while ($currentMonth->lte($endDate)) {
                $key = $currentMonth->format('Y-m');
                $monthlyLabels[] = $currentMonth->translatedFormat('M Y');
                $monthlyData[] = $monthlyCounts[$key] ?? 0;
                $currentMonth->addMonth();
            }

            $chartData['top_assets'] = ['labels' => $assetLabels, 'data' => $assetData, 'title' => '⚠️ En Sık Arızalananlar'];
            $chartData['monthly_maintenance'] = ['labels' => $monthlyLabels, 'data' => $monthlyData, 'title' => '📅 Aylık Bakım Yükü'];
        }

        return $chartData;
    }

    /**
     * ULAŞTIRMA DEPARTMANI VERİLERİ
     */
    public function getUlastirmaStatsData(Carbon $startDate, Carbon $endDate, string $viewLevel = 'basic', $unitId = null): StatisticsData
    {
        $user = Auth::user();

        $assignmentQuery = VehicleAssignment::forUser($user)
            ->with('vehicle')
            ->whereBetween('start_time', [$startDate, $endDate]);

        if ($unitId) {
            $assignmentQuery->where('business_unit_id', $unitId);
        }

        $assignments = $assignmentQuery->get();

        $chartData = $this->prepareUlastirmaCharts($assignments, $startDate, $endDate, $viewLevel);

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
    private function aggregateProductionDetails($query): array
    {
        $allPlansRaw = (clone $query)->whereNotNull('plan_details')->get(['plan_details']);
        $flatDetails = [];

        foreach ($allPlansRaw as $plan) {
            if (is_array($plan->plan_details)) {
                foreach ($plan->plan_details as $detail) {
                    $machine = trim(strval($detail['machine'] ?? 'Bilinmiyor'));
                    $product = is_numeric($detail['product'] ?? 'Bilinmiyor') ? 'Ürün-' . $detail['product'] : trim(strval($detail['product'] ?? 'Bilinmiyor'));
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

        $machineUsage = $col->groupBy('machine')->map(fn($g) => $g->sum('quantity'))->sortDesc()->take(10);
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

    private function prepareProductionBaseCharts($query, $startDate, $endDate, $viewLevel): array
    {
        $charts = [];

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

        $eventQuery = Event::forUser($user)
            ->select(['event_type', DB::raw('COUNT(*) as count')])
            ->whereNotNull('event_type')
            ->whereBetween('start_datetime', [$startDate, $endDate]);

        if ($unitId) {
            $eventQuery->where('business_unit_id', $unitId);
        }

        $eventTypeCounts = $eventQuery->groupBy('event_type')->pluck('count', 'event_type')
            ->mapWithKeys(function ($count, $key) use ($eventTypesList) {
                return [$eventTypesList[$key] ?? ucfirst($key) => $count];
            });

        $travelQuery = Travel::forUser($user)->whereBetween('start_date', [$startDate, $endDate]);
        if ($unitId) {
            $travelQuery->where('business_unit_id', $unitId);
        }
        $travelCount = $travelQuery->count();

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

        $eventQuery = Event::forUser($user)->whereBetween('start_datetime', [$startDate, $endDate]);
        if ($unitId) {
            $eventQuery->where('business_unit_id', $unitId);
        }

        $eventsForFiltering = $eventQuery->get(['event_type', 'location'])
            ->toBase()
            ->map(function ($event) use ($eventTypesList) {
                return [
                    'type_name' => $eventTypesList[$event->event_type] ?? ucfirst($event->event_type),
                    'type_slug' => $event->event_type,
                    'group' => 'Etkinlikler',
                ];
            });

        $travelQuery = Travel::forUser($user)->whereBetween('start_date', [$startDate, $endDate]);
        if ($unitId) {
            $travelQuery->where('business_unit_id', $unitId);
        }

        $travelsForFiltering = $travelQuery->get(['name'])
            ->toBase()
            ->map(function ($travel) {
                return [
                    'type_name' => 'Seyahat Planı',
                    'type_slug' => 'travel',
                    'group' => 'Seyahatler',
                ];
            });

        return $eventsForFiltering->merge($travelsForFiltering)->all();
    }

    public function getHizmetVehicleFilterData(): array
    {
        return Vehicle::orderBy('plate_number')->get(['id', 'plate_number'])->all();
    }

    public function normalizeCargoContent($cargo)
    {
        if (empty($cargo)) return 'Bilinmiyor';
        $normalized = mb_strtoupper(trim($cargo), 'UTF-8');
        $normalized = Str::ascii($normalized);
        $specialCases = ['LEVBA' => 'LEVHA', 'LEVBE' => 'LEVHA', 'PLASTIC' => 'PLASTİK', 'KAPAK' => 'KAPAK', 'PLASTİK' => 'PLASTİK', 'LEVHA' => 'LEVHA'];
        return $specialCases[$normalized] ?? $normalized;
    }

    public function normalizeVehicleType($vehicle)
    {
        if (empty($vehicle)) return 'Bilinmiyor';
        $normalized = mb_strtoupper(trim($vehicle), 'UTF-8');
        $vehicleMapping = ['TIR' => 'TIR', 'TİR' => 'TIR', 'TRUCK' => 'TIR', 'GEMI' => 'GEMI', 'GEMİ' => 'GEMI', 'SHIP' => 'GEMI', 'KAMYON' => 'KAMYON', 'TRUCK_SMALL' => 'KAMYON', 'KAMYONET' => 'KAMYON'];
        return $vehicleMapping[$normalized] ?? $normalized;
    }

    public function getEventTypes()
    {
        return ['toplanti' => 'Toplantı', 'egitim' => 'Eğitim', 'fuar' => 'Fuar', 'gezi' => 'Gezi', 'musteri_ziyareti' => 'Müşteri Ziyareti', 'misafir_karsilama' => 'Misafir Karşılama', 'diger' => 'Diğer'];
    }

    private function prepareUlastirmaCharts($assignments, $startDate, $endDate, $viewLevel): array
    {
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

        if ($viewLevel === 'full') {
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

    private function prepareLojistikCharts($shipmentQuery, string $viewLevel): array
    {
        $chartData = [];

        $hourlyLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));
        $hourlyCounts = array_fill_keys(range(0, 23), 0);
        $hourlyDbData = (clone $shipmentQuery)->select([DB::raw('HOUR(cikis_tarihi) as hour'), DB::raw('COUNT(*) as count')])->groupBy('hour')->pluck('count', 'hour');

        foreach ($hourlyDbData as $hour => $count) {
            if (isset($hourlyCounts[$hour])) $hourlyCounts[$hour] = $count;
        }
        $chartData['hourly'] = ['labels' => $hourlyLabels, 'data' => array_values($hourlyCounts), 'title' => '⏰ Saatlik Sevkiyat Yoğunluğu'];

        $dayLabels = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
        $dayCounts = array_fill(0, 7, 0);
        $dayMap = [2 => 0, 3 => 1, 4 => 2, 5 => 3, 6 => 4, 7 => 5, 1 => 6];
        $dailyDbData = (clone $shipmentQuery)->select([DB::raw('DAYOFWEEK(cikis_tarihi) as day_of_week'), DB::raw('COUNT(*) as count')])->groupBy('day_of_week')->pluck('count', 'day_of_week');

        foreach ($dailyDbData as $dayNum => $count) {
            if (isset($dayMap[$dayNum])) $dayCounts[$dayMap[$dayNum]] = $count;
        }
        $chartData['daily'] = ['labels' => $dayLabels, 'data' => $dayCounts, 'title' => '📅 Haftalık Sevkiyat Yoğunluğu'];

        if ($viewLevel === 'full') {
            $monthLabels = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
            $monthCounts = array_fill(0, 12, 0);
            $monthlyDbData = (clone $shipmentQuery)->select([DB::raw('MONTH(cikis_tarihi) as month'), DB::raw('COUNT(*) as count')])->groupBy('month')->pluck('count', 'month');

            foreach ($monthlyDbData as $monthNum => $count) {
                if ($monthNum >= 1 && $monthNum <= 12) $monthCounts[$monthNum - 1] = $count;
            }
            $chartData['monthly'] = ['labels' => $monthLabels, 'data' => $monthCounts, 'title' => 'Aylık Sevkiyat Dağılımı'];

            $yearlyDbData = (clone $shipmentQuery)->select([DB::raw('YEAR(cikis_tarihi) as year'), DB::raw('COUNT(*) as count')])->groupBy('year')->orderBy('year')->pluck('count', 'year');
            $chartData['yearly'] = ['labels' => $yearlyDbData->keys()->map(fn($y) => (string) $y)->all(), 'data' => $yearlyDbData->values()->all(), 'title' => 'Yıllık Toplam Sevkiyat'];
        } else {
            $chartData['monthly'] = null;
            $chartData['yearly'] = null;
        }

        $vehicleTypeData = (clone $shipmentQuery)->select(['arac_tipi', DB::raw('COUNT(*) as count')])->whereNotNull('arac_tipi')->groupBy('arac_tipi')->get()
            ->groupBy(fn($item) => $this->normalizeVehicleType($item->arac_tipi))
            ->map(fn($group) => $group->sum('count'));

        $chartData['pie'] = ['labels' => $vehicleTypeData->keys()->map(fn($tip) => $tip ?? 'Bilinmiyor')->all(), 'data' => $vehicleTypeData->values()->all(), 'title' => 'Araç Tipi Dağılımı'];

        $cargoData = (clone $shipmentQuery)
            ->select(['kargo_icerigi', DB::raw('COUNT(*) as count')])
            ->whereNotNull('kargo_icerigi')
            ->groupBy('kargo_icerigi')
            ->get()
            ->groupBy(fn($item) => $this->normalizeCargoContent($item->kargo_icerigi))
            ->map(fn($group) => $group->sum('count'))
            ->sortDesc()
            ->take(10);

        $chartData['cargo_pie'] = [
            'labels' => $cargoData->keys()->all(),
            'data' => $cargoData->values()->all(),
            'title' => '📦 Kargo İçeriği Dağılımı'
        ];

        return $chartData;
    }
}
