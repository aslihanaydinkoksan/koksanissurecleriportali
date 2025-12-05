<?php

namespace App\Http\Controllers;

use App\Models\ProductionPlan;
use App\Models\Birim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\CsvExporter;

class ProductionPlanController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('access-department', 'uretim');

        $query = ProductionPlan::with('user');
        $filters = $request->all();
        $user = Auth::user();
        $isImportantFilter = $request->input('is_important', 'all');

        if ($isImportantFilter !== 'all' && $user && in_array($user->role, ['admin', 'yönetici'])) {

            if ($isImportantFilter === 'yes') {
                $query->where('is_important', true);
            } elseif ($isImportantFilter === 'no') {
                $query->where('is_important', false);
            }
        }

        if ($request->filled('plan_title')) {
            $query->where('plan_title', 'LIKE', '%' . $request->input('plan_title') . '%');
        }

        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where('week_start_date', '>=', $dateFrom);
            } catch (\Exception $e) {
            }
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                $query->where('week_start_date', '<=', $dateTo);
            } catch (\Exception $e) {
            }
        }
        $plans = $query->orderBy('week_start_date', 'desc')
            ->paginate(15);
        $filters = $request->only(['plan_title', 'date_from', 'date_to', 'is_important']);

        return view('production.plans.index', compact('plans', 'filters'));
    }

    public function create()
    {
        $this->authorize('access-department', 'uretim');

        // YENİ EKLENDİ: Birimleri veritabanından çek
        $birimler = Birim::orderBy('ad')->get();

        // View'a birimleri gönder
        return view('production.plans.create', compact('birimler'));
    }
    public function store(Request $request)
    {
        $this->authorize('access-department', 'uretim');

        $validatedData = $request->validate([
            'plan_title' => 'required|string|max:255',
            'week_start_date' => 'required|date',
            'plan_details' => 'nullable|array',

            'plan_details.*.machine' => 'required_with:plan_details|string|max:255',
            'plan_details.*.product' => 'required_with:plan_details|string|max:255',
            'plan_details.*.quantity' => 'required_with:plan_details|numeric|min:1',
            // YENİ VALIDASYON KURALI EKLENDİ
            'plan_details.*.birim_id' => 'required_with:plan_details|integer|exists:birims,id',
        ]);


        $validatedData['user_id'] = Auth::id();


        ProductionPlan::create($validatedData);

        return redirect()->route('production.plans.create')
            ->with('success', 'Haftalık üretim planı başarıyla oluşturuldu!');
    }

    public function edit(ProductionPlan $productionPlan)
    {

        $this->authorize('access-department', 'uretim');

        if (Auth::id() !== $productionPlan->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('production.plans.index')
                ->with('error', 'Bu planı sadece oluşturan kişi düzenleyebilir.');
        }

        // YENİ EKLENDİ: Birimleri düzenleme formu için de çek
        $birimler = Birim::orderBy('ad')->get();

        // View'a birimleri gönder
        return view('production.plans.edit', compact('productionPlan', 'birimler'));
    }


    public function update(Request $request, ProductionPlan $productionPlan)
    {

        $this->authorize('access-department', 'uretim');

        if (Auth::id() !== $productionPlan->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('production.plans.index')
                ->with('error', 'Bu planı sadece oluşturan kişi güncelleyebilir.');
        }


        $validatedData = $request->validate([
            'plan_title' => 'required|string|max:255',
            'week_start_date' => 'required|date',
            'plan_details' => 'nullable|array',
            'plan_details.*.machine' => 'required_with:plan_details|string|max:255',
            'plan_details.*.product' => 'required_with:plan_details|string|max:255',
            'plan_details.*.quantity' => 'required_with:plan_details|numeric|min:1',
            // YENİ VALIDASYON KURALI EKLENDİ
            'plan_details.*.birim_id' => 'required_with:plan_details|integer|exists:birims,id',
        ]);

        $productionPlan->update($validatedData);


        return redirect()->route('production.plans.index')
            ->with('success', 'Üretim planı başarıyla güncellendi.');
    }

    public function destroy(ProductionPlan $productionPlan)
    {
        $this->authorize('access-department', 'uretim');

        if (Auth::id() !== $productionPlan->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('production.plans.index')
                ->with('error', 'Bu planı sadece oluşturan kişi silebilir.');
        }

        $productionPlan->delete();

        return redirect()->route('production.plans.index')
            ->with('success', 'Üretim planı başarıyla silindi.');
    }
    /**
     * Üretim Planlarını CSV olarak dışa aktar
     */
    public function export()
    {
        $fileName = 'uretim-planlari-' . date('d-m-Y') . '.csv';

        // 1. Sorgu
        // 'user' ilişkisi user_id üzerinden veriyi çeker.
        $query = ProductionPlan::with('user')->latest();

        // 2. Başlıklar
        $headers = [
            'ID',
            'Plan Başlığı',
            'Başlangıç Tarihi',
            'Önem durumu',
            'Oluşturan',
            'Detaylar (Makine | Ürün | Miktar)', // JSON verisini buraya işleyeceğiz
            'Oluşturulma Tarihi'
        ];

        // 3. Servisi Çağır
        return CsvExporter::streamDownload(
            query: $query,
            headers: $headers,
            fileName: $fileName,
            rowMapper: function ($plan) {

                // -- JSON PARSE İŞLEMİ --
                // Veritabanındaki [{"machine":"...","product":"...","quantity":"..."}] yapısını okuyoruz
                $detailsRaw = $plan->plan_details;

                // Eğer veri string geliyorsa array'e çevir, zaten array ise öyle kalsın (Laravel cast özelliği varsa)
                $detailsArray = is_string($detailsRaw) ? json_decode($detailsRaw, true) : $detailsRaw;

                $detailsString = '';

                if (is_array($detailsArray)) {
                    foreach ($detailsArray as $item) {
                        // JSON içindeki key'ler görüntüden anlaşıldığı kadarıyla: machine, product, quantity
                        $makine = $item['machine'] ?? '-';
                        $urun = $item['product'] ?? '-'; // Burası muhtemelen ID'dir
                        $adet = $item['quantity'] ?? '0';

                        // Excel hücresinde alt alta görünsün diye araya ayraç koyuyoruz
                        // Örnek çıktı: [M:12345 - Ü:6789 - Adet:100] | [M:....]
                        $detailsString .= "[Makine: $makine - Ürün: $urun - Miktar: $adet] | ";
                    }
                } else {
                    $detailsString = 'Detay Yok';
                }

                // Sondaki fazlalık " | " işaretini temizleyelim
                $detailsString = rtrim($detailsString, " | ");

                // -- Satırı Döndür --
                return [
                    $plan->id,
                    $plan->plan_title,
                    $plan->week_start_date ? Carbon::parse($plan->week_start_date)->format('d.m.Y') : '-',
                    $plan->is_important ? 'Önemli' : 'Normal',
                    $plan->user ? $plan->user->name : 'Bilinmiyor',
                    $detailsString, // Hazırladığımız detay metni
                    $plan->created_at->format('d.m.Y H:i')
                ];
            }
        );
    }
}
