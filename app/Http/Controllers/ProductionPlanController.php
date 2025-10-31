<?php

namespace App\Http\Controllers;

use App\Models\ProductionPlan; // Modelimizi kullanıyoruz
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // YENİ: Tarih işlemleri için

class ProductionPlanController extends Controller
{
    /**
     * Üretim planlarını listeler. (GÜNCELLENDİ)
     */
    public function index(Request $request) // YENİ: Request $request eklendi
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'uretim');

        // Temel sorguyu başlat
        $query = ProductionPlan::with('user');

        // --- FİLTRELEME MANTIĞI ---
        if ($request->filled('plan_title')) {
            $query->where('plan_title', 'LIKE', '%' . $request->input('plan_title') . '%');
        }

        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where('week_start_date', '>=', $dateFrom);
            } catch (\Exception $e) { /* Geçersiz tarihi yoksay */
            }
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                $query->where('week_start_date', '<=', $dateTo);
            } catch (\Exception $e) { /* Geçersiz tarihi yoksay */
            }
        }
        // --- FİLTRELEME SONU ---

        // Planları, en yeniden eskiye doğru getir.
        $plans = $query->orderBy('week_start_date', 'desc')
            ->paginate(15);

        // Filtre değerlerini view'a geri yolla
        $filters = $request->only(['plan_title', 'date_from', 'date_to']);

        return view('production.plans.index', compact('plans', 'filters'));
    }

    /**
     * Yeni bir üretim planı oluşturma formunu gösterir.
     * (Sıradaki adımda bu view'ı oluşturacağız)
     */
    public function create()
    {
        // YETKİ KONTROLÜ: Sadece 'uretim' birimi erişebilir
        $this->authorize('access-department', 'uretim');

        return view('production.plans.create');
    }

    /**
     * Yeni üretim planını veritabanında saklar.
     */
    public function store(Request $request)
    {
        // YETKİ KONTROLÜ: Sadece 'uretim' birimi erişebilir
        $this->authorize('access-department', 'uretim');

        // VALIDASYON: 
        // plan_details'ı bir array olarak doğruluyoruz, çünkü JSON'a kaydedeceğiz.
        // Bu, formda "Makine 1", "Ürün A", "Adet 100" gibi dinamik satırlarımız
        // olacağı varsayımına dayanır.
        $validatedData = $request->validate([
            'plan_title' => 'required|string|max:255',
            'week_start_date' => 'required|date',
            'plan_details' => 'nullable|array', // plan_details bir dizi olmalı

            // Eğer plan_details gönderildiyse, içindeki her elemanın bu kurallara uyması gerekir:
            'plan_details.*.machine' => 'required_with:plan_details|string|max:255',
            'plan_details.*.product' => 'required_with:plan_details|string|max:255',
            'plan_details.*.quantity' => 'required_with:plan_details|numeric|min:1',
        ]);

        // ShipmentController'dan kopyalanan mantık: user_id'yi ekle
        $validatedData['user_id'] = Auth::id();

        // Not: Dosya yüklemeyi (ek_dosya) şimdilik atladım. 
        // Üretim planları için de gerekirse, ShipmentController'daki
        // 'store' metodundan o kısmı kopyalayabiliriz.

        ProductionPlan::create($validatedData);

        // ShipmentController'daki gibi 'create' sayfasına geri yönlendirme
        return redirect()->route('production.plans.create')
            ->with('success', 'Haftalık üretim planı başarıyla oluşturuldu!');
    }


    /**
     * Belirtilen üretim planını düzenleme formunu gösterir.
     */
    public function edit(ProductionPlan $productionPlan)
    {
        // YETKİ KONTROLÜ: Sadece 'uretim' birimi erişebilir
        $this->authorize('access-department', 'uretim');

        return view('production.plans.edit', compact('productionPlan'));
    }

    /**
     * Veritabanındaki belirtilen üretim planını günceller.
     */
    public function update(Request $request, ProductionPlan $productionPlan)
    {
        // YETKİ KONTROLÜ: Sadece 'uretim' birimi erişebilir
        $this->authorize('access-department', 'uretim');

        // Validasyon (store ile aynı)
        $validatedData = $request->validate([
            'plan_title' => 'required|string|max:255',
            'week_start_date' => 'required|date',
            'plan_details' => 'nullable|array',
            'plan_details.*.machine' => 'required_with:plan_details|string|max:255',
            'plan_details.*.product' => 'required_with:plan_details|string|max:255',
            'plan_details.*.quantity' => 'required_with:plan_details|numeric|min:1',
        ]);

        $productionPlan->update($validatedData);

        // Güncellemeden sonra liste sayfasına yönlendirmek daha mantıklıdır
        return redirect()->route('production.plans.index')
            ->with('success', 'Üretim planı başarıyla güncellendi.');
    }

    /**
     * Belirtilen üretim planını veritabanından siler.
     */
    public function destroy(ProductionPlan $productionPlan)
    {
        // YETKİ KONTROLÜ (Adım 1): Sadece 'uretim' birimi erişebilir
        $this->authorize('access-department', 'uretim');

        // YETKİ KONTROLÜ (Adım 2): ShipmentController'daki 'admin' kuralı
        /*if (Auth::user()->role !== 'admin') {
            return redirect()->route('production.plans.index')
                ->with('error', 'Bu işlemi yapma yetkiniz bulunmamaktadır.');
        }*/

        $productionPlan->delete();

        return redirect()->route('production.plans.index')
            ->with('success', 'Üretim planı başarıyla silindi.');
    }
}
