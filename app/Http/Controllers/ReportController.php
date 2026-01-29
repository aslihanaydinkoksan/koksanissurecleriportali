<?php

namespace App\Http\Controllers;

use App\Models\Stay;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Kurumsal Dashboard ve Raporlama
     */
    public function index(Request $request)
    {
        // 1. TABLO SORGUSU VE GELİŞMİŞ ARAMA
        $query = Stay::with(['resident', 'location']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Ad + Soyad kombinasyonu ile ara (Ercan EREN yazınca çıkması için)
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

        // Filtreler
        if ($request->filled('start_date'))
            $query->whereDate('check_in_date', '>=', $request->start_date);
        if ($request->filled('end_date'))
            $query->whereDate('check_in_date', '<=', $request->end_date);
        if ($request->filled('location_id'))
            $query->where('location_id', $request->location_id);

        $stays = $query->orderBy('check_in_date', 'desc')->paginate(20)->withQueryString();
        $locations = Location::whereIn('type', ['site', 'block', 'campus', 'apartment'])->orderBy('name')->get();

        // ---------------------------------------------
        // 2. DASHBOARD VERİLERİ (KURUMSAL ANALİZ)
        // ---------------------------------------------

        // Özet Kartlar
        $totalUnits = Location::whereIn('type', ['apartment', 'room'])->count();
        $occupiedCount = Stay::whereNull('check_out_date')->distinct('location_id')->count();
        $activeGuests = Stay::whereNull('check_out_date')->count();

        // GRAFİK 1: Mülkiyet Durumu (Dairesel)
        $ownershipData = Location::whereIn('type', ['apartment', 'room'])
            ->select('ownership', DB::raw('count(*) as total'))
            ->groupBy('ownership')
            ->pluck('total', 'ownership')
            ->mapWithKeys(fn($val, $key) => [$key == 'owned' ? 'Şirket Mülkü' : 'Kiralık' => $val]);

        // GRAFİK 2: Departman Bazlı Kullanım (Hangi departman tesisleri ne kadar kullanıyor?)
        $deptData = Stay::join('residents', 'stays.resident_id', '=', 'residents.id')
            ->select('residents.department', DB::raw('count(*) as count'))
            ->groupBy('residents.department')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'department');

        // GRAFİK 3: Konaklama Süresi Dağılımı (Segmentasyon)
        $staysForDuration = Stay::whereNotNull('check_out_date')->get();
        $durationBuckets = [
            '1-3 Gün' => 0,
            '4-7 Gün' => 0,
            '8-15 Gün' => 0,
            '15+ Gün' => 0
        ];
        foreach ($staysForDuration as $s) {
            $days = $s->check_in_date->diffInDays($s->check_out_date);
            if ($days <= 3)
                $durationBuckets['1-3 Gün']++;
            elseif ($days <= 7)
                $durationBuckets['4-7 Gün']++;
            elseif ($days <= 15)
                $durationBuckets['8-15 Gün']++;
            else
                $durationBuckets['15+ Gün']++;
        }

        // GRAFİK 4: Aylık Giriş Yoğunluğu
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
            'deptData',
            'durationBuckets',
            'monthlyData',
            'totalUnits',
            'occupiedCount',
            'activeGuests'
        ));
    }
}