<?php

namespace App\Services\Dashboard;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerReportService
{
    /**
     * Müşterinin Tüm Rapor Verilerini Hazırlar
     */
    public function getDashboardData(Customer $customer): array
    {
        return [
            'opportunity_conversion' => $this->getOpportunityConversionData($customer),
            'product_distribution' => $this->getProductCompetitorDistribution($customer),
            'activity_density' => $this->getActivityDensity($customer),
            'return_ratio' => $this->getReturnVsShippedRatio($customer),
        ];
    }

    /**
     * 1. Fırsat Dönüşüm Oranı (Kazanılan / Toplam Fırsat)
     */
    private function getOpportunityConversionData(Customer $customer): array
    {
        $opportunities = $customer->opportunities()
            ->select('stage', DB::raw('count(*) as total'))
            ->groupBy('stage')
            ->pluck('total', 'stage')->toArray();

        // DİKKAT: Veritabanındaki "kazanildi" ve "kaybedildi" değerleriyle birebir eşleşmeli!
        $won = $opportunities['kazanildi'] ?? 0;
        $lost = $opportunities['kaybedildi'] ?? 0;

        $total = array_sum($opportunities);
        // Kazanılan ve Kaybedilen dışındaki her şey (duyum, teklif, gorusme) beklemede sayılır
        $pending = $total - $won - $lost;

        // Yüzdelik Oran (Sadece Kazanılanlar)
        $wonRate = $total > 0 ? round(($won / $total) * 100, 1) : 0;

        return [
            'series' => [$wonRate],
            'details' => [
                'won' => $won,
                'lost' => $lost,
                'pending' => $pending,
                'total' => $total
            ]
        ];
    }

    /**
     * 2. Ürün / Rakip Dağılımı (KÖKSAN vs RAKİP)
     */
    private function getProductCompetitorDistribution(Customer $customer): array
    {
        $distribution = $customer->products()
            ->select('supplier_type', DB::raw('count(*) as total'))
            ->groupBy('supplier_type')
            ->pluck('total', 'supplier_type')->toArray();

        $koksan = $distribution['koksan'] ?? 0;
        $competitor = $distribution['competitor'] ?? 0;

        return [
            'series' => [$koksan, $competitor],
            'labels' => ['KÖKSAN Ürünleri', 'Rakip Ürünler']
        ];
    }

    /**
     * 3. Aktivite Yoğunluğu (Son 6 Ay)
     */
    private function getActivityDensity(Customer $customer): array
    {
        // Son 6 ayın etiketlerini (Label) oluşturalım
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->toArray();

        $activities = $customer->activities()
            ->select(DB::raw("DATE_FORMAT(activity_date, '%Y-%m') as month"), DB::raw('count(*) as total'))
            ->where('activity_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $visits = $customer->visits()
            ->select(DB::raw("DATE_FORMAT(visit_date, '%Y-%m') as month"), DB::raw('count(*) as total'))
            ->where('visit_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $activitySeries = [];
        $visitSeries = [];
        $categories = [];

        foreach ($months as $month) {
            $categories[] = Carbon::parse($month . '-01')->translatedFormat('M Y');
            $activitySeries[] = $activities[$month] ?? 0;
            $visitSeries[] = $visits[$month] ?? 0;
        }

        return [
            'categories' => $categories,
            'series' => [
                ['name' => 'İletişim (Telefon/Mail)', 'data' => $activitySeries],
                ['name' => 'Fiziksel Ziyaret', 'data' => $visitSeries]
            ]
        ];
    }

    /**
     * 4. İade vs Sevkiyat Oranı (En çok işlem gören 5 Ürün)
     */
    private function getReturnVsShippedRatio(Customer $customer): array
    {
        $returns = $customer->returns()
            ->select(
                'product_name',
                DB::raw('SUM(shipped_quantity) as total_shipped'),
                DB::raw('SUM(quantity) as total_returned')
            )
            ->groupBy('product_name')
            ->orderBy('total_returned', 'desc')
            ->take(5)
            ->get();

        $categories = [];
        $shipped = [];
        $returned = [];

        foreach ($returns as $ret) {
            $categories[] = \Str::limit($ret->product_name, 15);
            $shipped[] = (float) $ret->total_shipped;
            $returned[] = (float) $ret->total_returned;
        }

        if (empty($categories)) {
            return [
                'categories' => ['Veri Yok'],
                'series' => [
                    ['name' => 'Gönderilen', 'data' => [0]],
                    ['name' => 'İade Gelen', 'data' => [0]]
                ]
            ];
        }

        return [
            'categories' => $categories,
            'series' => [
                ['name' => 'Gönderilen', 'data' => $shipped],
                ['name' => 'İade Gelen', 'data' => $returned]
            ]
        ];
    }
}
