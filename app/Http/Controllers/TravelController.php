<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Tüm filtre parametrelerini request'ten al
        // 'all' veya 'null' varsayılan değerleri belirler
        $filters = [
            'name' => $request->input('name'),
            'status' => $request->input('status', 'all'),
            'is_important' => $request->input('is_important', 'all'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'user_id' => $request->input('user_id', 'all'),
        ];

        $travelsQuery = Travel::with('user'); // Seyahati oluşturan kullanıcıyı da al

        // 2. Filtreleri sorguya uygula

        // İsim Filtresi
        if (!empty($filters['name'])) {
            $travelsQuery->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }

        // Durum Filtresi
        if ($filters['status'] !== 'all') {
            $travelsQuery->where('status', $filters['status']);
        }

        // Önemli Filtresi
        if ($filters['is_important'] !== 'all') {
            $travelsQuery->where('is_important', $filters['is_important'] === 'yes');
        }

        // Tarih Aralığı Filtresi (Başlangıç)
        if (!empty($filters['date_from'])) {
            // Bu tarihten SONRA biten tüm seyahatleri bul
            $travelsQuery->where('end_date', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }

        // Tarih Aralığı Filtresi (Bitiş)
        if (!empty($filters['date_to'])) {
            // Bu tarihten ÖNCE başlayan tüm seyahatleri bul
            $travelsQuery->where('start_date', '<=', Carbon::parse($filters['date_to'])->endOfDay());
        }

        // 3. Yetki Filtreleri
        $user = Auth::user();

        // Eğer global yönetici DEĞİLSE, sadece kendi seyahatlerini görsün
        if (!$user->can('is-global-manager')) {
            $travelsQuery->where('user_id', $user->id);

            // Eğer global yöneticiyse VE belirli bir kullanıcıyı filtrelediyse
        } elseif ($filters['user_id'] !== 'all') {
            $travelsQuery->where('user_id', $filters['user_id']);
        }

        // 4. Veriyi al ve sayfala
        $travels = $travelsQuery->orderBy('start_date', 'desc')
            ->paginate(20)
            ->appends($filters); // Filtreleri sayfalama linklerine ekle

        // 5. Admin'in kullanıcıları filtreleyebilmesi için kullanıcı listesini al
        $users = collect(); // Boş koleksiyon
        if ($user->can('is-global-manager')) {
            // Sadece seyahat oluşturan 'hizmet' departmanındakileri veya adminleri listele
            $users = User::whereHas('department', fn($q) => $q->where('slug', 'hizmet'))
                ->orWhere('role', 'admin')
                ->orderBy('name')
                ->get();
        }

        // 6. View'a tüm verileri gönder
        return view('travels.index', compact('travels', 'filters', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('travels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. DÜZELTME: Validasyon güncellendi (eski 'details' alanları kaldırıldı)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,completed',
            // 'transportation_details' ve 'accommodation_details' buradan kaldırıldı.
        ]);

        // user_id'yi giriş yapan kullanıcı olarak ayarla
        $validatedData['user_id'] = Auth::id();

        // 2. DÜZELTME: Oluşturulan seyahati bir $travel değişkenine ata
        $travel = Travel::create($validatedData);

        // 3. DÜZELTME: $travel değişkenini 'route' fonksiyonuna parametre olarak ver
        return redirect()->route('travels.show', $travel)
            ->with('success', 'Seyahat planı başarıyla oluşturuldu. Şimdi rezervasyonlarınızı ekleyebilirsiniz.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Travel $travel)
    {
        // Seyahat planına bağlı TÜM ilişkili verileri tek seferde yükle
        $travel->load([
            'bookings.media',           // Rezervasyonlar VE bu rezervasyonlara bağlı medya dosyaları
            'customerVisits.customer',  // Ziyaretler VE bu ziyaretlerin müşterileri
            'customerVisits.event'      // Ziyaretler VE bu ziyaretlerin etkinlik detayları
        ]);

        // $travel değişkenini tüm bu yüklenmiş verilerle birlikte view'a gönder
        return view('travels.show', compact('travel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Travel $travel)
    {
        // Sadece admin veya oluşturan kişi düzenleyebilir
        if (Auth::id() !== $travel->user_id && !Auth::user()->can('is-global-manager')) {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        return view('travels.edit', compact('travel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Travel $travel)
    {
        // Sadece admin veya oluşturan kişi güncelleyebilir
        if (Auth::id() !== $travel->user_id && !Auth::user()->can('is-global-manager')) {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,completed',
        ]);

        $travel->update($validatedData);

        return redirect()->route('travels.index')
            ->with('success', 'Seyahat planı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Travel $travel)
    {
        // Sadece admin veya oluşturan kişi silebilir
        if (Auth::id() !== $travel->user_id && !Auth::user()->can('is-global-manager')) {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        // Not: Migration'da 'onDelete('set null')' tanımladığımız için,
        // bu seyahati silmek, bağlı 'customer_visits' kayıtlarını silmez,
        // sadece 'travel_id'lerini 'null' yapar (Bağımsız Ziyaret'e dönüştürür).
        // Bu, istediğimiz ve güvenli olan davranıştır.

        $travel->delete();

        return redirect()->route('travels.index')
            ->with('success', 'Seyahat planı başarıyla silindi.');
    }
}
