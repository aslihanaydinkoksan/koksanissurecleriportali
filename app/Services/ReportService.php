<?php

namespace App\Services;

use App\Repositories\ReportRepository;
use Carbon\Carbon;

class ReportService
{
    protected $repo;

    public function __construct(ReportRepository $repo)
    {
        $this->repo = $repo;
    }

    public function prepareData($type, $scope)
    {
        $startDate = $this->calculateDate($scope);

        return match ($type) {
            'stays' => $this->repo->getStayData($startDate),
            'assets' => $this->repo->getAssetData(),
            default => collect([]),
        };
    }

    private function calculateDate($scope)
    {
        return match ($scope) {
            'daily' => Carbon::yesterday(),
            'weekly' => Carbon::now()->subDays(7),
            'monthly' => Carbon::now()->subMonth(),
            '3_months' => Carbon::now()->subMonths(3),
            '6_months' => Carbon::now()->subMonths(6),
            'yearly' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(7),
        };
    }
}