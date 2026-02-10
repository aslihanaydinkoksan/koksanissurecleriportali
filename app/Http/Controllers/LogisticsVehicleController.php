<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LogisticsVehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LogisticsVehicleController extends Controller
{
    /**
     * Lojistik araçlarını listeler.
     */
    public function index(Request $request)
    {
        // Sorguyu başlat
        $query = LogisticsVehicle::query();

        // 1. Arama Filtresi (Plaka, Marka veya Model)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // 2. Durum Filtresi
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 3. Yakıt Tipi Filtresi
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->input('fuel_type'));
        }

        // Filtrelenmiş sonuçları getir
        $vehicles = $query->orderBy('created_at', 'desc')->paginate(10);

        // İstatistikler (Dashboard için)
        $stats = [
            'total' => LogisticsVehicle::count(),
            'active' => LogisticsVehicle::where('status', 'active')->count(),
            'maintenance' => LogisticsVehicle::where('status', 'maintenance')->count(),
        ];

        return view('logistics_vehicles.index', compact('vehicles', 'stats'));
    }

    /**
     * Yeni araç ekleme formunu gösterir.
     */
    public function create()
    {
        // DÜZELTME: Burası index değil 'create' olmalı
        return view('logistics_vehicles.create');
    }

    /**
     * Yeni aracı veritabanına kaydeder.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'plate_number' => 'required|string|max:20|unique:logistics_vehicles,plate_number',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'capacity' => 'nullable|numeric|min:0',
            'current_km' => 'required|numeric|min:0',
            'fuel_type' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
            'kvkk_onay' => 'accepted',
        ], [
            'plate_number.unique' => 'Bu plaka ile kayıtlı başka bir araç zaten var.',
            'kvkk_onay.accepted' => 'Lütfen KVKK metnini onaylayınız.',
        ]);

        LogisticsVehicle::create($request->except('kvkk_onay'));

        // Rota adı: service prefix'i olduğu için 'service.logistics-vehicles.index'
        return redirect()->route('service.logistics-vehicles.index')
            ->with('success', 'Lojistik aracı başarıyla eklendi.');
    }

    /**
     * Araç düzenleme formunu gösterir.
     */
    public function edit($id)
    {
        $vehicle = LogisticsVehicle::findOrFail($id);
        // DÜZELTME: View yolu
        return view('logistics_vehicles.edit', compact('vehicle'));
    }

    /**
     * Araç bilgilerini günceller.
     */
    public function update(Request $request, $id)
    {
        $vehicle = LogisticsVehicle::findOrFail($id);

        $validatedData = $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('logistics_vehicles')->ignore($vehicle->id)
            ],
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'capacity' => 'nullable|numeric|min:0',
            'current_km' => 'required|numeric|min:0',
            'fuel_type' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
        ]);

        $vehicle->update($validatedData);

        return redirect()->route('service.logistics-vehicles.index')
            ->with('success', 'Araç bilgileri güncellendi.');
    }

    /**
     * Aracı siler.
     */
    public function destroy($id)
    {
        $vehicle = LogisticsVehicle::findOrFail($id);

        if ($vehicle->hasActiveAssignment()) {
            return back()->with('error', 'Bu aracın devam eden bir görevi var, önce görevi tamamlayın.');
        }

        $vehicle->delete();

        return redirect()->route('service.logistics-vehicles.index')
            ->with('success', 'Araç başarıyla silindi.');
    }
}