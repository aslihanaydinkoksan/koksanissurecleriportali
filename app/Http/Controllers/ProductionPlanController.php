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
     * 1. TOPLU LİSTE İNDİR (Excel)
     * Rota: /production/plans/export-list
     */
    public function exportList()
    {
        return CsvExporter::streamDownload(
            query: ProductionPlan::with(['user'])->latest(),
            headers: ['ID', 'Başlık', 'Başlangıç', 'Önem', 'Oluşturan', 'Oluşturulma'],
            fileName: 'tum-uretim-planlari-' . date('d-m-Y') . '.csv',
            rowMapper: function ($plan) {
                return [
                    $plan->id,
                    $plan->plan_title,
                    $plan->week_start_date ? Carbon::parse($plan->week_start_date)->format('d.m.Y') : '-',
                    $plan->is_important ? 'Önemli' : 'Normal',
                    $plan->user ? $plan->user->name : '-',
                    $plan->created_at->format('d.m.Y H:i')
                ];
            }
        );
    }

    /**
     * 2. TEKİL İŞ EMRİ FİŞİ İNDİR (Excel)
     * Rota: /production/plans/{productionPlan}/export
     */
    public function export(ProductionPlan $productionPlan)
    {
        $fileName = 'is-emri-' . $productionPlan->id . '.csv';

        // Detay fişi olduğu için başlıkları yan yana değil, form gibi alt alta yapıyoruz
        // Bu yüzden callback içinde manuel yazacağız.
        $headers = ['Content-Type' => 'text/csv; charset=utf-8', 'Content-Disposition' => "attachment; filename=$fileName"];

        $callback = function () use ($productionPlan) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM

            // FİŞ BAŞLIĞI
            fputcsv($file, ['ÜRETİM İŞ EMRİ DETAYI'], ';');
            fputcsv($file, [], ';'); // Boş satır

            // TEMEL BİLGİLER (Alt alta)
            fputcsv($file, ['Plan ID', $productionPlan->id], ';');
            fputcsv($file, ['Başlık', $productionPlan->plan_title], ';');
            fputcsv($file, ['Başlangıç Tarihi', $productionPlan->week_start_date], ';');
            fputcsv($file, ['Oluşturan', $productionPlan->user->name ?? '-'], ';');
            fputcsv($file, ['Önem Derecesi', $productionPlan->is_important ? 'YÜKSEK' : 'Normal'], ';');

            fputcsv($file, [], ';');
            fputcsv($file, ['--- ÜRETİM DETAYLARI / REÇETE ---'], ';');

            // JSON VERİSİNİ TABLO GİBİ YAZALIM
            // Başlıklar
            fputcsv($file, ['Makine', 'Ürün', 'Miktar'], ';');

            $details = is_string($productionPlan->plan_details)
                ? json_decode($productionPlan->plan_details, true)
                : $productionPlan->plan_details;

            if ($details && is_array($details)) {
                foreach ($details as $item) {
                    fputcsv($file, [
                        $item['machine'] ?? '-',
                        $item['product'] ?? '-',
                        $item['quantity'] ?? '0'
                    ], ';');
                }
            } else {
                fputcsv($file, ['Detay Yok'], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
