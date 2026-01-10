<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Masraf Ekleme İşlemi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'travel_id' => 'required|exists:travels,id', // Hangi seyahate ekleniyor?
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:TRY,USD,EUR,GBP',
            'receipt_date' => 'nullable|date',
            'description' => 'nullable|string|max:255',
        ]);

        $travel = Travel::findOrFail($validated['travel_id']);

        // Polymorphic Kayıt
        $travel->expenses()->create([
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'receipt_date' => $validated['receipt_date'],
            'description' => $validated['description'],
            'created_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Masraf kalemi başarıyla eklendi.');
    }

    /**
     * Masraf Silme İşlemi
     */
    public function destroy(Expense $expense)
    {
        // Yetki kontrolü eklenebilir (Sadece ekleyen veya admin silebilir)
        $expense->delete();
        return redirect()->back()->with('success', 'Masraf kaydı silindi.');
    }
}