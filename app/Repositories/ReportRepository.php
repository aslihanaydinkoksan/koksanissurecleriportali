<?php

namespace App\Repositories;

use App\Models\Stay;
use App\Models\Asset;
use Carbon\Carbon;

class ReportRepository
{
    public function getStayData($startDate)
    {
        return Stay::with(['resident', 'location'])
            ->where('check_in_date', '>=', $startDate)
            ->get();
    }

    public function getAssetData()
    {
        return Asset::with('location')->get();
    }
}