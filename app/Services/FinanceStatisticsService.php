<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class FinanceStatisticsService
{
    public function getGeneralOverview($filters = [])
    {
        $activeUnitId = session('active_unit_id');
        $query = Expense::query();
        if ($activeUnitId) {
            $query->where('business_unit_id', $activeUnitId);
        }

        if (!empty($filters['date_from']))
            $query->where('receipt_date', '>=', $filters['date_from']);
        if (!empty($filters['date_to']))
            $query->where('receipt_date', '<=', $filters['date_to']);

        // 1. Toplam Harcamalar (Döviz Bazlı)
        $totals = (clone $query)->select('currency', DB::raw('SUM(amount) as total'))
            ->groupBy('currency')
            ->get();

        // 2. Kategori Bazlı Dağılım
        $byCategory = (clone $query)->select('category', 'currency', DB::raw('SUM(amount) as total'))
            ->groupBy('category', 'currency')
            ->get();

        // 3. Modül Bazlı Dağılım (Seyahat mi? Fuar mı?)
        $byModule = (clone $query)->select('expensable_type', 'currency', DB::raw('SUM(amount) as total'))
            ->groupBy('expensable_type', 'currency')
            ->get();

        return [
            'totals' => $totals,
            'byCategory' => $byCategory,
            'byModule' => $byModule
        ];
    }
}