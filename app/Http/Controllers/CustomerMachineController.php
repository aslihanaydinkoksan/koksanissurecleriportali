<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerMachine;
use App\Models\CustomerProduct;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CustomerMachineController extends Controller
{
    public function store(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255', // Seçilen veya yazılan isim
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:customer_machines,serial_number',
            'installation_date' => 'nullable|date',
        ]);

        // AKILLI EŞLEŞTİRME: İsim doluysa ürünü bul ya da YENİ oluştur!
        if (!empty($validated['product_name'])) {
            $product = $customer->products()->firstOrCreate(['name' => $validated['product_name']]);
            $validated['customer_product_id'] = $product->id;
        } else {
            $validated['customer_product_id'] = null;
        }
        unset($validated['product_name']); // Tabloda böyle bir sütun olmadığı için diziden çıkarıyoruz

        $customer->machines()->create($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Yeni makine başarıyla eklendi!');
    }

    public function update(Request $request, CustomerMachine $machine): RedirectResponse
    {
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => ['nullable', 'string', 'max:255', Rule::unique('customer_machines')->ignore($machine->id)],
            'installation_date' => 'nullable|date',
        ]);

        if (!empty($validated['product_name'])) {
            $product = CustomerProduct::firstOrCreate([
                'customer_id' => $machine->customer_id,
                'name' => $validated['product_name']
            ]);
            $validated['customer_product_id'] = $product->id;
        } else {
            $validated['customer_product_id'] = null;
        }
        unset($validated['product_name']);

        $machine->update($validated);

        return back()->with('success', 'Makine bilgileri başarıyla güncellendi.');
    }

    public function destroy(CustomerMachine $machine): RedirectResponse
    {
        $machine->delete();
        return back()->with('success', 'Makine kaydı silindi.');
    }
}
