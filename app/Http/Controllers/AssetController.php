<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Demirbaş Ekleme Formu
     * Hangi odaya eklenecekse onun ID'si gelir ($locationId)
     */
    public function create($locationId)
    {
        $location = Location::findOrFail($locationId);
        return view('assets.create', compact('location'));
    }

    /**
     * Kaydetme İşlemi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255', // Ürün Adı (Örn: Klima)
            'brand' => 'nullable|string|max:255', // Marka (Örn: Arçelik)
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'status' => 'required', // active, broken, repair
        ]);

        Asset::create($validated);

        return redirect()->route('locations.show', $request->location_id)
            ->with('success', 'Demirbaş başarıyla eklendi.');
    }

    /**
     * Demirbaş Silme
     */
    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        $locationId = $asset->location_id; // Silmeden önce konumunu alalım ki geri dönelim

        $asset->delete();

        return redirect()->route('locations.show', $locationId)
            ->with('success', 'Demirbaş silindi.');
    }
}