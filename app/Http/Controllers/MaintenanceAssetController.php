<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceAsset;
use Illuminate\Http\Request;
use App\Services\CsvExporter;

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
    /**
     * Makineler ve Varlıklar Listesini CSV olarak dışa aktar
     */
    public function export()
    {
        $fileName = 'makine-varlik-listesi-' . date('d-m-Y') . '.csv';

        // 1. SORGULAMA
        // Herhangi bir ilişki (relation) tablodan görünmüyor (User veya Category ID yok, direkt yazılmış).
        // Bu yüzden sadece kendi tablosunu çekiyoruz.
        $query = MaintenanceAsset::query()->latest();

        // 2. BAŞLIKLAR
        $headers = [
            'ID',
            'Varlık Adı',
            'Marka',
            'Model',
            'Seri Numarası',
            'Üretim Yılı',
            'Kategori',
            'Varlık Kodu',
            'Konum',
            'Durum',
            'Açıklama',
            'Oluşturulma Tarihi'
        ];

        // 3. EXPORT İŞLEMİ
        return CsvExporter::streamDownload(
            query: $query,
            headers: $headers,
            fileName: $fileName,
            rowMapper: function ($asset) {

                // -- DURUM KONTROLÜ (Aktif/Pasif) --
                // Veritabanında muhtemelen 1/0 veya true/false tutuluyor.
                $durum = $asset->is_active ? 'Aktif' : 'Pasif';

                // -- SATIR VERİSİ --
                return [
                    $asset->id,
                    $asset->name,
                    $asset->brand ?? '-',
                    $asset->model ?? '-',
                    $asset->serial_number ?? '-',
                    $asset->manufacturing_year ?? '-',
                    $asset->category ?? '-',
                    $asset->code ?? '-',
                    $asset->location ?? '-',
                    $durum,
                    $asset->description ?? '-',
                    $asset->created_at ? $asset->created_at->format('d.m.Y H:i') : '-'
                ];
            }
        );
    }
}