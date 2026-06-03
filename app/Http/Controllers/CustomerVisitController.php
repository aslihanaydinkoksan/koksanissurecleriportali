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
            
            'findings' => 'nullable|string', // Technical fields might be null from IAA
            'result' => 'nullable|string',
            'visitor_id' => 'nullable|exists:users,id',
            'visitor_name' => 'nullable|string|max:255',
            'visit_files.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        // Kişi listesi birleştirme mantığı (Aktivitedeki gibi)
        $contacts = $validated['contact_persons'] ?? [];
        if (!empty($validated['other_contact_persons'] ?? null)) {
            $others = array_filter(array_map('trim', explode(',', $validated['other_contact_persons'])));
            $contacts = array_merge($contacts, $others);
        }
        $finalContacts = array_values(array_unique($contacts));

        $visit = $customer->visits()->create([
            'user_id' => Auth::id(), // Servis Veren
            'visit_date' => $validated['visit_date'],
            'visit_reason' => $validated['visit_reason'],
            'visit_notes' => $validated['visit_notes'] ?? null,
            'contact_persons' => $finalContacts,
            'customer_product_id' => $validated['customer_product_id'] ?? null,
            'barcode' => $validated['barcode'] ?? null,
            'lot_no' => $validated['lot_no'] ?? null,
            'complaint_id' => $validated['complaint_id'] ?? null,
            'findings' => $validated['findings'] ?? null,
            'result' => $validated['result'] ?? null,
            'visitor_id' => $validated['visitor_id'] ?? Auth::id(),
            'visitor_name' => $validated['visitor_name'] ?? null,
        ]);
        if ($request->hasFile('visit_files')) {
            foreach ($request->file('visit_files') as $file) {
                $visit->addMedia($file)->toMediaCollection('visit_attachments');
            }
        }

        return back()->with('success', 'Ziyaret formu başarıyla kaydedildi.');
    }

    public function update(Request $request, Customer $customer, CustomerVisit $visit)
    {
        if ($visit->is_locked) {
            return back()->with('error', 'Bu ziyaret onay sürecinde olduğu için düzenlenemez.');
        }

        $validated = $request->validate([
            'visit_date' => 'required|date',
            'visit_reason' => 'required|string',
            'visit_notes' => 'nullable|string',
            'contact_persons' => 'nullable|array',
            'other_contact_persons' => 'nullable|string',
            
            'customer_product_id' => 'nullable|exists:customer_products,id',
            'barcode' => 'nullable|string|max:100',
            'lot_no' => 'nullable|string|max:100',
            'complaint_id' => 'nullable|exists:complaints,id',
            
            'findings' => 'nullable|string', // Technical fields might be null from IAA
            'result' => 'nullable|string',
            'visitor_id' => 'nullable|exists:users,id',
            'visitor_name' => 'nullable|string|max:255',
            'visit_files.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        $contacts = $validated['contact_persons'] ?? [];
        if (!empty($validated['other_contact_persons'] ?? null)) {
            $others = array_filter(array_map('trim', explode(',', $validated['other_contact_persons'])));
            $contacts = array_merge($contacts, $others);
        }
        $finalContacts = array_values(array_unique($contacts));

        $visit->update([
            'visit_date' => $validated['visit_date'],
            'visit_reason' => $validated['visit_reason'],
            'visit_notes' => $validated['visit_notes'] ?? null,
            'contact_persons' => $finalContacts,
            'customer_product_id' => $validated['customer_product_id'] ?? null,
            'barcode' => $validated['barcode'] ?? null,
            'lot_no' => $validated['lot_no'] ?? null,
            'complaint_id' => $validated['complaint_id'] ?? null,
            'findings' => $validated['findings'] ?? null,
            'result' => $validated['result'] ?? null,
            'visitor_id' => $validated['visitor_id'] ?? $visit->visitor_id,
            'visitor_name' => $validated['visitor_name'] ?? null,
        ]);

        if ($request->hasFile('visit_files')) {
            foreach ($request->file('visit_files') as $file) {
                $visit->addMedia($file)->toMediaCollection('visit_attachments');
            }
        }

        return back()->with('success', 'Ziyaret formu başarıyla güncellendi.');
    }

    public function destroy(CustomerVisit $visit)
    {
        if ($visit->is_locked) {
            return back()->with('error', 'Bu ziyaret onay sürecinde olduğu için silinemez.');
        }

        $visit->delete();
        return back()->with('success', 'Ziyaret formu silindi.');
    }

    // Detay Görüntüleme Sayfası
    public function show(CustomerVisit $visit)
    {
        $visit->load(['customer', 'product', 'visitor', 'user']);
        return view('customers.visits.show', compact('visit'));
    }

    // YENİ: Yazdırma Ekranı
    public function print(CustomerVisit $visit)
    {
        return view('customers.visits.print', compact('visit'));
    }
}