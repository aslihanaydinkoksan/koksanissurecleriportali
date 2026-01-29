<?php

namespace App\Http\Controllers;

use App\Models\Stay;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. TABLO SORGUSU (Aynen Kalıyor) ---
        $query = Stay::with(['resident', 'location']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('resident', function ($subQ) use ($search) {
                    $subQ->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
                $q->orWhereHas('location', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('start_date'))
            $query->whereDate('check_in_date', '>=', $request->start_date);
        if ($request->filled('end_date'))
            $query->whereDate('check_in_date', '<=', $request->end_date);
        if ($request->filled('location_id'))
            $query->where('location_id', $request->location_id);

        $stays = $query->orderBy('check_in_date', 'desc')->paginate(20)->withQueryString();
        $locations = Location::whereIn('type', ['site', 'block', 'campus', 'apartment'])->orderBy('name')->get();


        // --- 2. DASHBOARD VERİLERİ (YENİLENDİ) ---

        // A) Özet Kartlar
        $totalUnits = Location::whereIn('type', ['apartment', 'room'])->count();
        $occupiedCount = Stay::whereNull('check_out_date')->distinct('location_id')->count();
        $activeGuests = Stay::whereNull('check_out_date')->count();

        // B) Mülkiyet Durumu (Şirket Mülkü vs Kiralık)
        $ownershipData = Location::whereIn('type', ['apartment', 'room'])
            ->select('ownership', DB::raw('count(*) as total'))
            ->groupBy('ownership')
            ->pluck('total', 'ownership')
            ->mapWithKeys(fn($val, $key) => [$key == 'owned' ? 'Şirket Mülkü' : 'Kiralık' => $val]);

        // C) GRAFİK 2 (YENİ): Son 7 Günlük Hareketlilik (Giriş vs Çıkış)
        // Son 7 günü tek tek dönüp o günkü giriş ve çıkış sayılarını alıyoruz.
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i));
        }

        $movementLabels = [];
        $checkInCounts = [];
        $checkOutCounts = [];

        foreach ($dates as $date) {
            $dayLabel = $date->format('d/m'); // Örn: 25/05
            $movementLabels[] = $dayLabel;

            // O gün giriş yapanlar
            $checkInCounts[] = Stay::whereDate('check_in_date', $date)->count();

            // O gün çıkış yapanlar
            $checkOutCounts[] = Stay::whereDate('check_out_date', $date)->count();
        }

        $movementData = [
            'labels' => $movementLabels,
            'check_ins' => $checkInCounts,
            'check_outs' => $checkOutCounts
        ];

        // D) Konaklama Süresi Dağılımı (Aktifleri de kapsayan mantık)
        $allStaysForDuration = Stay::select('check_in_date', 'check_out_date')->get();
        $durationBuckets = ['1-7 Gün' => 0, '8-30 Gün' => 0, '1-6 Ay' => 0, '6+ Ay' => 0];

        foreach ($allStaysForDuration as $s) {
            if (!$s->check_in_date)
                continue;
            $endDate = $s->check_out_date ?? Carbon::now(); // Hala kalıyorsa bugünü baz al
            $days = $s->check_in_date->diffInDays($endDate);

            if ($days <= 7)
                $durationBuckets['1-7 Gün']++;
            elseif ($days <= 30)
                $durationBuckets['8-30 Gün']++;
            elseif ($days <= 180)
                $durationBuckets['1-6 Ay']++;
            else
                $durationBuckets['6+ Ay']++;
        }

        // E) Aylık Trend
        $monthlyData = Stay::select(
            DB::raw('count(*) as count'),
            DB::raw("DATE_FORMAT(check_in_date, '%Y-%m') as month_year")
        )
            ->where('check_in_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->pluck('count', 'month_year');


        return view('reports.index', compact(
            'stays',
            'locations',
            'ownershipData',
            'movementData', // residentTypeData yerine movementData geldi
            'durationBuckets',
            'monthlyData',
            'totalUnits',
            'occupiedCount',
            'activeGuests'
        ));
    }
}