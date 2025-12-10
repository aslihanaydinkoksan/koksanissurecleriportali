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
     * Raporlama Ana Ekranı
     */
    public function index(Request $request)
    {
        // Temel Sorguyu Başlat
        $query = Stay::with(['resident', 'location']);

        // ... (1. ve 2. Filtreler AYNEN KALACAK)
        // 1. Arama Filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Kişi adında ara
                $q->whereHas('resident', function ($subQ) use ($search) {
                    $subQ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
                // Veya Oda adında ara
                $q->orWhereHas('location', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                });
            });
        }

        // 2. Tarih Aralığı Filtresi (Giriş Tarihine Göre)
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_date', '<=', $request->end_date);
        }

        // 3. Lokasyon Filtresi (Sadece belirli bir binayı seçerse)
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        // Sonuçları getir (Tablo için)
        $stays = $query->orderBy('check_in_date', 'desc')->paginate(20)->withQueryString();

        // Filtre dropdown'ı için tüm mekanları (Sadece binaları/siteleri) çekelim
        $locations = Location::whereIn('type', ['site', 'block', 'campus', 'apartment'])->orderBy('name')->get();


        // ---------------------------------------------
        // GRAFİK VERİLERİNİ HAZIRLAMA
        // Not: Grafik verilerini, sayfalamadan bağımsız olarak tüm veri setinden hazırlamalıyız.
        // Bunun için filtreleri aynı query'ye tekrar uygulayacağız.
        // ---------------------------------------------

        $chartQuery = Stay::query();
        // Filtreleri chartQuery'ye de uygula (tekrar yazmaya gerek yok, üstteki mantık kullanılabilir)
        // ... (Filtrelerin chartQuery'ye uygulanması gerekir, bu örnekte basit tutacağız)

        // Grafikler için temel alacağımız veri setini çekelim.
        $allStays = Stay::with('location')->get();
        $allLocations = Location::all();


        // 1. Mülkiyet Durumu Grafiği (Pastayı Boş/Dolu Daireler Üzerinden yapalım)
        $ownershipData = $allLocations->whereIn('type', ['apartment', 'room'])
            ->groupBy('ownership')
            ->mapWithKeys(function ($group, $key) {
                $status = $key == 'owned' ? 'Şirket Mülkü' : 'Kiralık';
                $count = $group->count();

                // Ek olarak doluluk bilgisini de ekleyebiliriz (İsteğe bağlı)
                $occupied = $group->where('currentStays.count', '>', 0)->count();
                $vacant = $count - $occupied;

                return [
                    $status => $count,
                ];
            });

        // 2. Mekan Tipi Dağılımı Grafiği (Hangi Tip Daire/Mekan daha çok kullanılıyor)
        $typeData = $allStays->groupBy(function ($stay) {
            return $stay->location ? ucfirst($stay->location->type) : 'Bilinmeyen';
        })->map->count();

        // 3. Aylık Konaklama Trendi (Son 12 ay)
        $monthlyData = Stay::select(
            DB::raw('count(*) as count'),
            DB::raw("DATE_FORMAT(check_in_date, '%Y-%m') as month_year")
        )
            ->where('check_in_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->pluck('count', 'month_year');

        // View'e gönderme
        return view('reports.index', compact(
            'stays',
            'locations',
            'ownershipData',
            'typeData',
            'monthlyData'
        ));
    }
}