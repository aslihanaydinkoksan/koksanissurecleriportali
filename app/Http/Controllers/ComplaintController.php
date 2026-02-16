<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ComplaintController extends Controller
{
    /**
     * Yeni Şikayet Kaydet
     */
    public function store(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,resolved',
            'complaint_files.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:10240' // 10MB limit
        ]);

        // Müşteriye bağlı şikayeti oluştur
        $complaint = $customer->complaints()->create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        // Spatie MediaLibrary ile Dosyaları Ekle
        if ($request->hasFile('complaint_files')) {
            foreach ($request->file('complaint_files') as $file) {
                $complaint->addMedia($file)->toMediaCollection('complaint_attachments');
            }
        }

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Yeni şikayet kaydı başarıyla oluşturuldu!');
    }

    /**
     * Mevcut Şikayeti Güncelle
     */
    public function update(Request $request, Complaint $complaint): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:open,in_progress,resolved',
            'description' => 'required|string',
        ]);

        $complaint->update($validated);

        return back()->with('success', 'Şikayet başarıyla güncellendi.');
    }

    /**
     * Şikayeti Sil
     */
    public function destroy(Complaint $complaint): RedirectResponse
    {
        $complaint->delete();

        return back()->with('success', 'Şikayet kaydı silindi.');
    }
}
