<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerVisitController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'visit_date' => 'required|date',
            'visit_reason' => 'required|string', // Checkbox/Radio seçimi
            'visit_notes' => 'nullable|string',
            'contact_persons' => 'nullable|array', // Çoklu seçim
            'other_contact_persons' => 'nullable|string', // Elle girilen
            
            'customer_product_id' => 'nullable|exists:customer_products,id',
            'barcode' => 'nullable|string|max:100',
            'lot_no' => 'nullable|string|max:100',
            'complaint_id' => 'nullable|exists:complaints,id',
            
            'findings' => 'required|string', // Tespitler
            'result' => 'required|string',   // Sonuç
            'visit_files.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        // Kişi listesi birleştirme mantığı (Aktivitedeki gibi)
        $contacts = $validated['contact_persons'] ?? [];
        if (!empty($validated['other_contact_persons'])) {
            $others = array_filter(array_map('trim', explode(',', $validated['other_contact_persons'])));
            $contacts = array_merge($contacts, $others);
        }
        $finalContacts = array_values(array_unique($contacts));

        $visit = $customer->visits()->create([
            'user_id' => Auth::id(), // Servis Veren
            'visit_date' => $validated['visit_date'],
            'visit_reason' => $validated['visit_reason'],
            'visit_notes' => $validated['visit_notes'],
            'contact_persons' => $finalContacts,
            'customer_product_id' => $validated['customer_product_id'],
            'barcode' => $validated['barcode'],
            'lot_no' => $validated['lot_no'],
            'complaint_id' => $validated['complaint_id'],
            'findings' => $validated['findings'],
            'result' => $validated['result'],
        ]);
        if ($request->hasFile('visit_files')) {
            foreach ($request->file('visit_files') as $file) {
                $visit->addMedia($file)->toMediaCollection('visit_attachments');
            }
        }

        return back()->with('success', 'Ziyaret formu başarıyla kaydedildi.');
    }

    public function destroy(CustomerVisit $visit)
    {
        $visit->delete();
        return back()->with('success', 'Ziyaret formu silindi.');
    }

    // YENİ: Yazdırma Ekranı
    public function print(CustomerVisit $visit)
    {
        return view('customers.visits.print', compact('visit'));
    }
}