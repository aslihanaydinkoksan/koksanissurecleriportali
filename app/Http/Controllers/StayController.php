<?php

namespace App\Http\Controllers;

use App\Models\Stay;
use App\Models\Location;
use App\Models\Resident;
use Illuminate\Http\Request;

class StayController extends Controller
{
    /**
     * Giriş Yapma Formu (Check-in)
     * Hangi odaya giriş yapılacaksa onun ID'si gelir.
     */
    public function create($locationId)
    {
        $location = Location::findOrFail($locationId);

        // Sadece Daire veya Oda tipindeki yerlere giriş yapılabilir
        if (!in_array($location->type, ['apartment', 'room'])) {
            return back()->with('error', 'Sadece daire veya odaya giriş yapılabilir.');
        }

        // Tüm personeli çekiyoruz (Dropdown için)
        // İlerde burası çok kalabalık olursa AJAX ile arama yaparız.
        $residents = Resident::orderBy('first_name')->get();

        return view('stays.create', compact('location', 'residents'));
    }

    /**
     * Giriş İşlemini Kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'resident_id' => 'required|exists:residents,id',
            'check_in_date' => 'required|date',
            'notes' => 'nullable|string',
            // Checklist elemanlarını array olarak alacağız
            'items' => 'nullable|array'
        ]);

        // 1. Odaya şu an başkası giriş yapmış mı kontrol et? (Opsiyonel ama güvenli)
        $activeStay = Stay::where('location_id', $request->location_id)
            ->whereNull('check_out_date')
            ->exists();

        if ($activeStay) {
            return back()->with('error', 'Bu odada şu an zaten biri kalıyor! Önce çıkış yapmalısınız.');
        }

        // 2. Kaydı Oluştur
        Stay::create([
            'location_id' => $request->location_id,
            'resident_id' => $request->resident_id,
            'check_in_date' => $request->check_in_date,
            'notes' => $request->notes,
            'check_in_items' => $request->items, // JSON olarak kaydolacak
        ]);

        return redirect()->route('locations.show', $request->location_id)
            ->with('success', 'Personel girişi (Check-in) başarıyla yapıldı.');
    }
    /**
     * Çıkış Formunu Göster (Check-out Page)
     * Aktif konaklamayı bulup formda göstereceğiz.
     */
    public function checkout($stayId)
    {
        // Çıkış yapılacak kaydı bul
        $stay = Stay::with(['location', 'resident'])->findOrFail($stayId);

        return view('stays.checkout', compact('stay'));
    }

    /**
     * Çıkış İşlemini Kaydet
     */
    public function processCheckout(Request $request, $stayId)
    {
        $validated = $request->validate([
            'check_out_date' => 'required|date', // Çıkış tarihi
            'notes' => 'nullable|string',
            'items' => 'nullable|array' // İade alınanlar listesi
        ]);

        $stay = Stay::findOrFail($stayId);

        // Kaydı güncelle
        $stay->update([
            'check_out_date' => $request->check_out_date,
            'check_out_items' => $request->items, // JSON olarak kaydolur
            // Çıkış notlarını mevcut notların üzerine eklemiyoruz, ayrı bir alana veya sonuna ekleyebiliriz.
            // Şimdilik notları güncelleyelim:
            'notes' => $stay->notes . "\n[Çıkış Notu]: " . $request->notes,
        ]);

        return redirect()->route('locations.show', $stay->location_id)
            ->with('success', 'Personel çıkışı (Check-out) yapıldı. Oda artık müsait.');
    }
    // Tek bir kaydı siler (SOFT DELETE YAPAR - Geri getirilebilir)
    public function destroy($id)
    {
        $stay = \App\Models\Stay::findOrFail($id);
        $stay->delete(); // Artık veritabanından silmiyor, gizliyor.

        return back()->with('success', 'Hareket kaydı çöpe taşındı.');
    }

    // Tüm geçmişi temizler
    public function clearAll()
    {
        // SEÇENEK A: Hepsini Soft Delete yap (Gizle ama veritabanında kalsın)
        \App\Models\Stay::query()->delete();
        return back()->with('success', 'Tüm geçmiş arşivlendi.');

        // SEÇENEK B: Test verisi olduğu için gerçekten sil (Veritabanı şişmesin)
        // Admin olduğun için bunu kullanman test aşamasında daha temiz olur.
        // \App\Models\Stay::truncate();

        // return back()->with('success', 'Tüm hareket geçmişi kalıcı olarak temizlendi.');
    }
}