<?php

namespace App\Services;

use App\Models\Customer;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class CustomerHistoryService
{
    /**
     * Ticari Faaliyet Geçmişi (Fırsatlar, Ürünler, Numuneler)
     */
    public function getCommercialHistory(Customer $customer)
    {
        return $this->getActivitiesForModels([
            'App\Models\Opportunity' => $customer->opportunities()->pluck('id'),
            'App\Models\Product' => $customer->products()->pluck('id'), // Product modelin namespace'ine dikkat et
            'App\Models\Sample' => $customer->samples()->pluck('id'),
        ]);
    }

    /**
     * Teknik Operasyon Geçmişi (Ziyaretler, Makineler, Testler)
     */
    public function getTechnicalHistory(Customer $customer)
    {
        return $this->getActivitiesForModels([
            'App\Models\CustomerVisit' => $customer->visits()->pluck('id'),
            'App\Models\CustomerMachine' => $customer->machines()->pluck('id'),
            'App\Models\TestResult' => $customer->testResults()->pluck('id'),
        ]);
    }

    /**
     * Destek ve Lojistik Geçmişi (Şikayetler, İadeler, Lojistik)
     */
    public function getSupportHistory(Customer $customer)
    {
        return $this->getActivitiesForModels([
            'App\Models\Complaint' => $customer->complaints()->pluck('id'),
            'App\Models\Return' => $customer->returns()->pluck('id'),
            'App\Models\VehicleAssignment' => $customer->vehicleAssignments()->pluck('id'),
        ]);
    }

    /**
     * Yardımcı Fonksiyon: Spatie Loglarını Topla
     */
    private function getActivitiesForModels(array $modelMap)
    {
        return Activity::query()
            ->with('causer') // İşlemi yapan kullanıcıyı (User) getir
            ->where(function (Builder $query) use ($modelMap) {
                foreach ($modelMap as $modelClass => $ids) {
                    $query->orWhere(function ($q) use ($modelClass, $ids) {
                        $q->where('subject_type', $modelClass)
                          ->whereIn('subject_id', $ids);
                    });
                }
            })
            ->latest()
            ->limit(20) // Performans için son 20 işlem
            ->get();
    }
}