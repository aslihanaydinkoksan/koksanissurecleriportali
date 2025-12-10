<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    /**
     * Misafir Listesi (Arama özellikli)
     */
    public function index(Request $request)
    {
        $query = Resident::query();

        // Arama yapılmışsa filtrele
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('tc_no', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $residents = $query->orderBy('first_name')->paginate(10);

        return view('residents.index', compact('residents'));
    }

    /**
     * Yeni Misafir Ekleme Formu
     */
    public function create()
    {
        return view('residents.create');
    }

    /**
     * Kaydetme İşlemi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'tc_no' => 'nullable|numeric|digits:11|unique:residents,tc_no', // TC Benzersiz olsun
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50', // Sicil No
            'department' => 'nullable|string|max:100',
        ]);

        Resident::create($validated);

        return redirect()->route('residents.index')
            ->with('success', 'Personel/Misafir sisteme kaydedildi.');
    }

    public function edit($id)
    {
        $resident = Resident::findOrFail($id);
        return view('residents.edit', compact('resident'));
    }

    public function update(Request $request, $id)
    {
        $resident = Resident::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // TC No güncellerken kendisi hariç unique olmalı:
            'tc_no' => 'nullable|numeric|digits:11|unique:residents,tc_no,' . $resident->id,
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:100',
        ]);

        $resident->update($validated);

        return redirect()->route('residents.index')
            ->with('success', 'Personel bilgileri güncellendi.');
    }
    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);

        // Eğer şu an aktif konaklaması varsa silme!
        if ($resident->stays()->whereNull('check_out_date')->exists()) {
            return back()->with('error', 'Bu kişi şu an bir odada konaklıyor. Önce çıkış yapmalısınız.');
        }

        $resident->delete();
        return back()->with('success', 'Personel başarıyla silindi.');
    }
    /**
     * AJAX ile hızlı personel ekleme (Check-in sayfası için)
     */
    public function storeAjax(Request $request)
    {
        // 1. Validasyon
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'tc_no' => 'nullable|numeric|digits:11|unique:residents,tc_no',
            'department' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        // 2. Kayıt (Varsa hata vermesin, null dönsün kontrolünü TC ile yapıyoruz)
        try {
            $resident = Resident::create($request->all());

            return response()->json([
                'success' => true,
                'resident' => $resident,
                'message' => 'Personel başarıyla eklendi ve seçildi.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
    }
}