<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class TestResultController extends Controller
{
    public function store(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'test_name' => 'required|string|max:255',
            'test_date' => 'required|date',
            'summary' => 'nullable|string',
            'test_files.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240'
        ]);

        $productId = null;
        if (!empty($validated['product_name'])) {
            $product = $customer->products()->firstOrCreate(['name' => $validated['product_name']]);
            $productId = $product->id;
        }

        $testResult = $customer->testResults()->create([
            'user_id' => Auth::id(),
            'customer_product_id' => $productId,
            'test_name' => $validated['test_name'],
            'test_date' => $validated['test_date'],
            'summary' => $validated['summary'],
        ]);

        if ($request->hasFile('test_files')) {
            foreach ($request->file('test_files') as $file) {
                $testResult->addMedia($file)->toMediaCollection('test_reports');
            }
        }

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Yeni test sonucu başarıyla eklendi!');
    }
    public function update(Request $request, TestResult $testResult)
    {
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'test_name' => 'required|string|max:255',
            'test_date' => 'required|date',
            'summary' => 'nullable|string',
        ]);

        $productId = null;
        if (!empty($validated['product_name'])) {
            $product = \App\Models\CustomerProduct::firstOrCreate([
                'customer_id' => $testResult->customer_id,
                'name' => $validated['product_name']
            ]);
            $productId = $product->id;
        }

        $testResult->update([
            'customer_product_id' => $productId,
            'test_name' => $validated['test_name'],
            'test_date' => $validated['test_date'],
            'summary' => $validated['summary'],
        ]);

        return back()->with('success', 'Test sonucu güncellendi.');
    }

    public function destroy(TestResult $testResult): RedirectResponse
    {
        $testResult->delete();
        return back()->with('success', 'Test sonucu silindi.');
    }
}