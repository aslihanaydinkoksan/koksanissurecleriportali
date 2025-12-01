<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Controller Oluşturucu: Yetki Kontrolü Burada Yapılır
     */
    public function __construct()
    {
        $this->middleware('auth');

        // Bu Controller'a sadece Admin, Hizmet veya Ulaştırma departmanı erişebilir
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            // 1. Admin veya Yönetici ise geç
            if (in_array($user->role, ['admin', 'yönetici'])) {
                return $next($request);
            }

            // 2. Departman Kontrolü (Slug kullanmak her zaman daha güvenlidir)
            // Departman slug'ı 'hizmet' veya 'ulastirma' ise geç
            if ($user->department && in_array($user->department->slug, ['hizmet', 'ulastirma'])) {
                return $next($request);
            }

            // Hiçbiri değilse 403 (Yetkisiz)
            abort(403, 'Bu işlemi gerçekleştirmeye yetkiniz bulunmamaktadır!');
        });
    }

    /**
     * Araçları listeler ve filtreler.
     */
    public function index(Request $request)
    {
        // NOT: __construct içindeki middleware yetkiyi zaten kontrol etti.
        // Buradaki $this->authorize satırını siliyoruz.

        $query = Vehicle::query();

        // --- FİLTRELEME ---
        if ($request->filled('plate_number')) {
            $query->where('plate_number', 'LIKE', '%' . $request->input('plate_number') . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', 'LIKE', '%' . $request->input('type') . '%');
        }
        // Aktif/Pasif filtresi
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
     */
    public function create()
    {
        return view('service.vehicles.create');
    }

    /**
     * Yeni aracı veritabanında saklar.
     */
    public function store(Request $request)
    {
        // VALIDASYON
        $validatedData = $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'type' => 'required|string|max:100',
            'brand_model' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            // 'kvkk_onay' => 'required|accepted', // Genelde araç eklerken KVKK olmaz ama varsa kalsın
        ]);

        // Checkbox kontrolü
        $validatedData['is_active'] = $request->has('is_active');
        if (isset($validatedData['kvkk_onay']))
            unset($validatedData['kvkk_onay']);

        Vehicle::create($validatedData);

        return redirect()->route('service.vehicles.index')
            ->with('success', 'Araç başarıyla eklendi.');
    }

    /**
     * Belirtilen aracı düzenleme formunu gösterir.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('service.vehicles.edit', compact('vehicle'));
    }

    /**
     * Veritabanındaki belirtilen aracı günceller.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validatedData = $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'plate_number')->ignore($vehicle->id),
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
     * Belirtilen aracı siler.
     */
    public function destroy(Vehicle $vehicle)
    {
        // İlişki kontrolü (Opsiyonel: Eğer araç görevdeyse silinmesin)
        /*if ($vehicle->assignments()->exists()) {
            return back()->with('error', 'Bu araca atanmış görevler varken silemezsiniz.');
        }*/

        $vehicle->delete();

        return redirect()->route('service.vehicles.index')
            ->with('success', 'Araç başarıyla silindi.');
    }
}