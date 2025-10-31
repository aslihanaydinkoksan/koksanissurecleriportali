<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Plakanın benzersizliği için

class VehicleController extends Controller
{
    /**
     * Araçları listeler ve filtreler.
     * View: service.vehicles.index (Sonraki adımda oluşturacağız)
     */
    public function index(Request $request)
    {
        // YETKİ KONTROLÜ: Sadece 'hizmet' birimi erişebilir
        $this->authorize('access-department', 'hizmet');

        $query = Vehicle::query();

        // --- FİLTRELEME ---
        if ($request->filled('plate_number')) {
            $query->where('plate_number', 'LIKE', '%' . $request->input('plate_number') . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', 'LIKE', '%' . $request->input('type') . '%');
        }
        // Aktif/Pasif filtresi (varsayılan olarak tüm araçları göster)
        $status = $request->input('status', 'all');
        if ($status !== 'all') {
            $query->where('is_active', $status === 'active');
        }

        // --- FİLTRELEME SONU ---

        $vehicles = $query->orderBy('plate_number')->paginate(15);
        $filters = $request->only(['plate_number', 'type', 'status']);

        return view('service.vehicles.index', compact('vehicles', 'filters'));
    }

    /**
     * Yeni araç ekleme formunu gösterir.
     * View: service.vehicles.create (Sonraki adımda oluşturacağız)
     */
    public function create()
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'hizmet');

        return view('service.vehicles.create');
    }

    /**
     * Yeni aracı veritabanında saklar.
     */
    public function store(Request $request)
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'hizmet');

        // VALIDASYON
        $validatedData = $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number', // Benzersiz olmalı
            'type' => 'required|string|max:100', // Örn: Otomobil, Kamyonet
            'brand_model' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean', // Gönderilirse boolean olmalı
        ]);

        // Checkbox'tan 'is_active' gelmezse 0 (false) olarak ayarla
        $validatedData['is_active'] = $request->has('is_active');

        Vehicle::create($validatedData);

        return redirect()->route('service.vehicles.index') // Liste sayfasına yönlendir
            ->with('success', 'Araç başarıyla eklendi.');
    }

    /**
     * Belirtilen aracı düzenleme formunu gösterir.
     * View: service.vehicles.edit (Sonraki adımda oluşturacağız)
     */
    public function edit(Vehicle $vehicle)
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'hizmet');

        return view('service.vehicles.edit', compact('vehicle'));
    }

    /**
     * Veritabanındaki belirtilen aracı günceller.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'hizmet');

        // VALIDASYON (Plaka güncellenirken kendisi hariç benzersiz olmalı)
        $validatedData = $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'plate_number')->ignore($vehicle->id), // Kendisi hariç
            ],
            'type' => 'required|string|max:100',
            'brand_model' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        $vehicle->update($validatedData);

        return redirect()->route('service.vehicles.index')
            ->with('success', 'Araç bilgileri başarıyla güncellendi.');
    }

    /**
     * Belirtilen aracı siler (veya pasife alır - tercih size kalmış).
     * Şimdilik direkt silme yapıyoruz.
     */
    public function destroy(Vehicle $vehicle)
    {
        // YETKİ KONTROLÜ ('hizmet' ve 'admin')
        $this->authorize('access-department', 'hizmet');
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('service.vehicles.index')
                ->with('error', 'Bu işlemi yapma yetkiniz bulunmamaktadır.');
        }

        // ÖNEMLİ: Eğer araçla ilişkili atamalar varsa silmeyi engellemek
        // veya kullanıcıyı uyarmak daha güvenli olabilir. Şimdilik siliyoruz.
        // if ($vehicle->assignments()->exists()) {
        //     return back()->with('error', 'Bu araca atanmış görevler varken silemezsiniz.');
        // }

        $vehicle->delete();

        return redirect()->route('service.vehicles.index')
            ->with('success', 'Araç başarıyla silindi.');
    }

    /**
     * Show metoduna şimdilik ihtiyacımız yok, genellikle edit yeterli olur.
     * İstenirse eklenebilir.
     */
    // public function show(Vehicle $vehicle)
    // {
    //     $this->authorize('access-department', 'hizmet');
    //     // ...
    // }
}
