<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceAsset;
use Illuminate\Http\Request;

class MaintenanceAssetController extends Controller
{
    public function index()
    {
        // Makineleri listele
        $assets = MaintenanceAsset::orderBy('category')->orderBy('name')->get();
        return view('maintenance.assets.index', compact('assets'));
    }

    public function create()
    {
        return view('maintenance.assets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:machine,zone,facility',
            'code' => 'nullable|string|unique:maintenance_assets,code',
        ]);

        MaintenanceAsset::create($request->all());

        return redirect()->route('maintenance.assets.index')
            ->with('success', 'Yeni makine/varlık başarıyla eklendi.');
    }

    public function edit($id)
    {
        $asset = MaintenanceAsset::findOrFail($id);
        return view('maintenance.assets.edit', compact('asset'));
    }

    public function update(Request $request, $id)
    {
        $asset = MaintenanceAsset::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:maintenance_assets,code,' . $asset->id,
        ]);

        $asset->update($request->all());

        return redirect()->route('maintenance.assets.index')
            ->with('success', 'Makine bilgileri güncellendi.');
    }

    public function destroy($id)
    {
        $asset = MaintenanceAsset::findOrFail($id);
        $asset->delete(); // Soft delete çalışır

        return redirect()->route('maintenance.assets.index')
            ->with('success', 'Varlık silindi.');
    }
}