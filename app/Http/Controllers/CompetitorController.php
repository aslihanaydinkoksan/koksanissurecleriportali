<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class CompetitorController extends Controller
{
    // Rakipleri Listeleme Sayfası
    public function index(): View
    {
        $competitors = Competitor::orderBy('name')->get();
        return view('competitors.index', compact('competitors'));
    }

    // Yeni Rakip Kaydetme (Bunu zaten yazmıştık)
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:competitors,name',
            'notes' => 'nullable|string'
        ]);
        $businessUnitId = session('active_unit_id') ?? auth()->user()->businessUnits->first()?->id ?? 1;

        // 2. ADIM: Bu ID'yi veritabanına kaydedilecek diziye ekliyoruz
        $validated['business_unit_id'] = $businessUnitId;

        // Yeni eklenen rakip varsayılan olarak aktif olsun
        $validated['is_active'] = true;
        Competitor::create($validated);
        return back()->with('success', 'Yeni rakip firma sisteme başarıyla eklendi.');
    }

    // Rakip Bilgilerini Güncelleme
    public function update(Request $request, Competitor $competitor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:competitors,name,' . $competitor->id,
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $competitor->update($validated);
        return back()->with('success', 'Rakip bilgileri başarıyla güncellendi.');
    }

    // Rakibi Silme
    public function destroy(Competitor $competitor): RedirectResponse
    {
        // Eğer bu rakibe bağlı müşteri ürünleri varsa silmeyi engelleme
        if ($competitor->products()->count() > 0) {
            return back()->with('error', 'Bu rakibe bağlı müşteri analizleri olduğu için silemezsiniz!');
        }

        $competitor->delete();
        return back()->with('success', 'Rakip firma sistemden silindi.');
    }
}
