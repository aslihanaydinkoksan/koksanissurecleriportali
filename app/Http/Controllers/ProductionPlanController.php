<?php

namespace App\Http\Controllers;

use App\Models\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductionPlanController extends Controller
{
    /**
     * Üretim planlarını listeler. 
     */
    public function index(Request $request)
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'uretim');

        $query = ProductionPlan::with('user');
        $filters = $request->all();
        $user = Auth::user(); // Giriş yapan kullanıcıyı al
        $isImportantFilter = $request->input('is_important', 'all');

        // Bu filtreyi sadece admin veya yönetici ise uygula
        if ($isImportantFilter !== 'all' && $user && in_array($user->role, ['admin', 'yönetici'])) {

            if ($isImportantFilter === 'yes') {
                $query->where('is_important', true);
            } elseif ($isImportantFilter === 'no') {
                $query->where('is_important', false);
            }
            // 'all' ise hiçbir şey yapma
        }

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

        // Planları, en yeniden eskiye doğru getir.
        $plans = $query->orderBy('week_start_date', 'desc')
            ->paginate(15);

        // Filtre değerlerini view'a geri yolla
        $filters = $request->only(['plan_title', 'date_from', 'date_to', 'is_important']);

        return view('production.plans.index', compact('plans', 'filters'));
    }

    /**
     * Yeni bir üretim planı oluşturma formunu gösterir.
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

        $validatedData = $request->validate([
            'plan_title' => 'required|string|max:255',
            'week_start_date' => 'required|date',
            'plan_details' => 'nullable|array',

            'plan_details.*.machine' => 'required_with:plan_details|string|max:255',
            'plan_details.*.product' => 'required_with:plan_details|string|max:255',
            'plan_details.*.quantity' => 'required_with:plan_details|numeric|min:1',
        ]);


        $validatedData['user_id'] = Auth::id();


        ProductionPlan::create($validatedData);

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

        if (Auth::id() !== $productionPlan->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('production.plans.index')
                ->with('error', 'Bu planı sadece oluşturan kişi düzenleyebilir.');
        }

        return view('production.plans.edit', compact('productionPlan'));
    }

    /**
     * Veritabanındaki belirtilen üretim planını günceller.
     */
    public function update(Request $request, ProductionPlan $productionPlan)
    {
        // YETKİ KONTROLÜ: Sadece 'uretim' birimi erişebilir
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
        ]);

        $productionPlan->update($validatedData);


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

        if (Auth::id() !== $productionPlan->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('production.plans.index')
                ->with('error', 'Bu planı sadece oluşturan kişi silebilir.');
        }

        $productionPlan->delete();

        return redirect()->route('production.plans.index')
            ->with('success', 'Üretim planı başarıyla silindi.');
    }
}
