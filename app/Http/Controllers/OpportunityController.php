<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'stage' => 'required|string|in:duyum,teklif,gorusme,kazanildi,kaybedildi',
            'expected_close_date' => 'nullable|date',
            'source' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id(); // Fırsatı ekleyen kullanıcı

        $customer->opportunities()->create($validated);

        return back()->with('success', 'Fırsat / Duyum başarıyla eklendi.');
    }
    public function update(Request $request, Opportunity $opportunity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'stage' => 'required|string|in:duyum,teklif,gorusme,kazanildi,kaybedildi',
            'expected_close_date' => 'nullable|date',
        ]);
        
        $opportunity->update($validated);
        return back()->with('success', 'Fırsat detayları güncellendi.');
    }

    public function updateStage(Request $request, Opportunity $opportunity)
    {
        $validated = $request->validate([
            'stage' => 'required|string|in:duyum,teklif,gorusme,kazanildi,kaybedildi',
        ]);

        $opportunity->update(['stage' => $validated['stage']]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Aşama güncellendi.']);
        }

        return back()->with('success', 'Fırsat aşaması güncellendi.');
    }

    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();
        return back()->with('success', 'Fırsat kaydı silindi.');
    }
}