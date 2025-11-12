<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection; // <-- BU SATIRI EKLEYİN

class StatisticsData implements Arrayable
{
    public function __construct(
        public array $chartData,
        public array $shipmentsForFiltering = [],
        public array $productionPlansForFiltering = [],
        public array $eventsForFiltering = [],
        public array $assignmentsForFiltering = [],
        public array $vehiclesForFiltering = [],
        public array $monthlyLabels = []
    ) {
    }

    /**
     * Nesneyi array_merge'ün anlayacağı dizi formatına çevirir.
     */
    public function toArray(): array
    {
        return [
            'chartData' => $this->chartData,
            'shipmentsForFiltering' => $this->shipmentsForFiltering,
            'productionPlansForFiltering' => $this->productionPlansForFiltering,
            'eventsForFiltering' => $this->eventsForFiltering,
            'assignmentsForFiltering' => $this->assignmentsForFiltering,
            'vehiclesForFiltering' => $this->vehiclesForFiltering,
            'monthlyLabels' => $this->monthlyLabels,
        ];
    }
}