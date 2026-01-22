<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Birim;
use App\Models\ShipmentsVehicleType; // Araç tipleri modeli
use App\Services\CsvExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Transaction için gerekli
use Carbon\Carbon;

class ShipmentController extends Controller
{
    /**
     * Yeni bir sevkiyat oluşturma formunu gösterir.
     */
    public function create()
    {
        $this->authorize('access-department', 'lojistik');
        $birimler = Birim::orderBy('ad')->get();
        return view('shipments.create', compact('birimler'));
    }

    /**
     * Formdan gelen yeni sevkiyat kaydını veritabanında saklar.
     */
    public function store(Request $request)
    {
        $this->authorize('access-department', 'lojistik');

        // Validasyon: Araç tiplerini veritabanından kontrol ediyoruz (Dinamik Validasyon)
        $rules = [
            'arac_tipi' => 'required|string|exists:shipments_vehicle_types,name',
            'plaka' => 'required_if:arac_tipi,tır,kamyon|nullable|string|max:255',
            'dorse_plakasi' => 'required_if:arac_tipi,tır|nullable|string|max:255',
            'sofor_adi' => 'nullable|string|max:255',
            'imo_numarasi' => 'required_if:arac_tipi,gemi|nullable|string|max:255',
            'gemi_adi' => 'required_if:arac_tipi,gemi|nullable|string|max:255',
            'is_important' => 'nullable|boolean',
            'kalkis_limani' => 'required_if:arac_tipi,gemi|nullable|string|max:255',
            'varis_limani' => 'required_if:arac_tipi,gemi|nullable|string|max:255',
            'kalkis_noktasi' => 'required_if:arac_tipi,tır,kamyon|nullable|string|max:255',
            'varis_noktasi' => 'required_if:arac_tipi,tır,kamyon|nullable|string|max:255',
            'shipment_type' => 'required|string|in:import,export',
            'kargo_icerigi' => 'required|string|max:255',
            'kargo_tipi' => 'required|string|max:255',
            'kargo_miktari' => 'required|string|max:255',
            'cikis_tarihi' => 'required|date',
            'tahmini_varis_tarihi' => 'required|date|after_or_equal:cikis_tarihi',
            'aciklamalar' => 'nullable|string',
            'ek_dosya' => ['nullable', 'file', 'mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png,txt', 'max:5120'],
        ];
        $rules = array_merge($rules, Shipment::getDynamicValidationRules());
        $validatedData = $request->validate($rules);
        $validatedData['user_id'] = Auth::id();
        $validatedData['business_unit_id'] = session('active_unit_id') ?? Auth::user()->businessUnits->first()?->id;
        $validatedData['is_important'] = $request->has('is_important');

        // Veri bütünlüğü için Transaction başlatıyoruz
        DB::transaction(function () use ($request, $validatedData) {

            $dosyaYolu = null;
            $uploadedFile = null;

            if ($request->hasFile('ek_dosya')) {
                $uploadedFile = $request->file('ek_dosya');
                $dosyaYolu = $uploadedFile->store('sevkiyat_dosyalari', 'public');
                $validatedData['dosya_yolu'] = $dosyaYolu;
            }

            // 1. ADIM: Sevkiyatı oluştur
            $shipment = Shipment::create($validatedData);

            // 2. ADIM: Files tablosuna kayıt (Transaction içinde güvenli)
            if ($uploadedFile && $dosyaYolu) {
                DB::table('files')->insert([
                    'fileable_type' => get_class($shipment),
                    'fileable_id' => $shipment->id,
                    'path' => $dosyaYolu,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'mime_type' => $uploadedFile->getMimeType(),
                    'uploaded_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('shipments.create')->with('success', 'Sevkiyat kaydı başarıyla oluşturuldu!');
    }

    /**
     * Belirtilen sevkiyatı düzenleme formunu gösterir.
     */
    public function edit(Shipment $shipment)
    {
        $this->authorize('access-department', 'lojistik');

        $birimler = Birim::orderBy('ad')->get();

        // Yetki kontrolü
        if (Auth::id() !== $shipment->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu sevkiyatı sadece oluşturan kişi düzenleyebilir.');
        }

        return view('shipments.edit', compact('shipment', 'birimler'));
    }

    /**
     * Veritabanındaki belirtilen sevkiyatı günceller.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $this->authorize('access-department', 'lojistik');

        if (Auth::id() !== $shipment->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu sevkiyatı sadece oluşturan kişi düzenleyebilir.');
        }

        $rules = [
            'arac_tipi' => 'required|string|exists:shipments_vehicle_types,name',
            'plaka' => 'nullable|string|max:255',
            'dorse_plakasi' => 'nullable|string|max:255',
            'sofor_adi' => 'nullable|string|max:255',
            'imo_numarasi' => 'nullable|string|max:255',
            'gemi_adi' => 'nullable|string|max:255',
            'kalkis_limani' => 'nullable|string|max:255',
            'varis_limani' => 'nullable|string|max:255',
            'kalkis_noktasi' => 'nullable|string|max:255',
            'varis_noktasi' => 'nullable|string|max:255',
            'shipment_type' => 'required|string|in:import,export',
            'kargo_icerigi' => 'required|string|max:255',
            'kargo_tipi' => 'required|string|max:255',
            'kargo_miktari' => 'required|string|max:255',
            'cikis_tarihi' => 'required|date',
            'tahmini_varis_tarihi' => 'required|date|after_or_equal:cikis_tarihi',
            'aciklamalar' => 'nullable|string',
            'ek_dosya' => ['nullable', 'file', 'mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png,txt', 'max:5120'],
            'dosya_sil' => 'nullable|boolean',
            'shipment_status' => 'nullable|string|in:pending,on_road,delivered',
        ];
        $rules = array_merge($rules, Shipment::getDynamicValidationRules());
        $validatedData = $request->validate($rules);

        DB::transaction(function () use ($request, $shipment, $validatedData) {

            // --- DOSYA YÖNETİMİ ---
            if ($request->hasFile('ek_dosya')) {
                // Eski fiziksel dosyayı sil
                if ($shipment->dosya_yolu && Storage::disk('public')->exists($shipment->dosya_yolu)) {
                    Storage::disk('public')->delete($shipment->dosya_yolu);
                }

                $uploadedFile = $request->file('ek_dosya');
                $dosyaYolu = $uploadedFile->store('sevkiyat_dosyalari', 'public');
                $validatedData['dosya_yolu'] = $dosyaYolu;

                // Files tablosuna yeni kayıt
                DB::table('files')->insert([
                    'fileable_type' => get_class($shipment),
                    'fileable_id' => $shipment->id,
                    'path' => $dosyaYolu,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'mime_type' => $uploadedFile->getMimeType(),
                    'uploaded_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            } elseif ($request->has('dosya_sil') && $request->input('dosya_sil') == '1') {
                if ($shipment->dosya_yolu && Storage::disk('public')->exists($shipment->dosya_yolu)) {
                    Storage::disk('public')->delete($shipment->dosya_yolu);
                }
            }
            // --- DOSYA YÖNETİMİ SONU ---
            if (!isset($validatedData['shipment_status'])) {
                unset($validatedData['shipment_status']); // Null gidip veriyi bozmasın
            }
            $shipment->update($validatedData);
        });

        return redirect()->route('shipments.show', $shipment->id)
            ->with('success', 'Sevkiyat kaydı başarıyla güncellendi!');
    }

    /**
     * Belirtilen sevkiyatı bir CSV dosyası olarak indirir.
     */
    public function export(Shipment $shipment)
    {
        // 1. Yetki Kontrolü AKTİF EDİLDİ
        $this->authorize('access-department', 'lojistik');

        $fileName = 'sevkiyat_detay_' . $shipment->id . '.csv';

        // ... (Header mantığı aynı kaldı) ...
        $ortakBasliklar = ['ID', 'Araç Tipi', 'Kargo Yükü', 'Kargo Tipi', 'Kargo Miktarı', 'Çıkış Tarihi', 'Tahmini Varış Tarihi', 'Açıklamalar', 'İhracat/İthalat'];
        $ozelBasliklar = [];

        if (in_array($shipment->arac_tipi, ['tır', 'kamyon'])) {
            $ozelBasliklar = ['Plaka', 'Dorse Plakası', 'Şoför Adı', 'Kalkış Noktası', 'Varış Noktası'];
        } elseif ($shipment->arac_tipi === 'gemi') {
            $ozelBasliklar = ['IMO Numarası', 'Gemi Adı', 'Kalkış Limanı', 'Varış Limanı'];
        }

        $finalHeaders = array_merge(array_slice($ortakBasliklar, 0, 2), $ozelBasliklar, array_slice($ortakBasliklar, 2));

        return CsvExporter::streamDownload(
            query: Shipment::where('id', $shipment->id),
            headers: $finalHeaders,
            fileName: $fileName,
            rowMapper: function ($record) {
                $sevkiyatTuru = match ($record->shipment_type) {
                    'export' => 'İhracat',
                    'import' => 'İthalat',
                    'default' => $record->shipment_type
                };

                $ortakData = [
                    $record->id,
                    ucfirst($record->arac_tipi),
                    $record->kargo_icerigi,
                    $record->kargo_tipi,
                    $record->kargo_miktari,
                    $record->cikis_tarihi ? Carbon::parse($record->cikis_tarihi)->format('d.m.Y H:i') : '-',
                    $record->tahmini_varis_tarihi ? Carbon::parse($record->tahmini_varis_tarihi)->format('d.m.Y H:i') : '-',
                    $record->aciklamalar,
                    $sevkiyatTuru
                ];

                $ozelData = [];
                if (in_array($record->arac_tipi, ['tır', 'kamyon'])) {
                    $ozelData = [$record->plaka, $record->arac_tipi === 'kamyon' ? '' : $record->dorse_plakasi, $record->sofor_adi, $record->kalkis_noktasi, $record->varis_noktasi];
                } elseif ($record->arac_tipi === 'gemi') {
                    $ozelData = [$record->imo_numarasi, $record->gemi_adi, $record->kalkis_limani, $record->varis_limani];
                }

                return array_merge(array_slice($ortakData, 0, 2), $ozelData, array_slice($ortakData, 2));
            }
        );
    }

    /**
     * TÜM SEVKİYAT LİSTESİNİ DIŞA AKTAR
     */
    public function exportList(Request $request)
    {
        // Yetki Kontrolü EKLENDİ
        $this->authorize('access-department', 'lojistik');

        $fileName = 'tum-sevkiyatlar-' . date('d-m-Y') . '.csv';
        $query = Shipment::latest();

        $headers = ['ID', 'Tip', 'Müşteri/Firma', 'Plaka / Gemi Adı', 'Şoför / IMO', 'Çıkış Noktası', 'Varış Noktası', 'Yük İçeriği', 'Durum', 'Çıkış Tarihi'];

        return CsvExporter::streamDownload(
            query: $query,
            headers: $headers,
            fileName: $fileName,
            rowMapper: function ($shipment) {
                $aracBilgisi = '-';
                $soforBilgisi = '-';

                if (in_array($shipment->arac_tipi, ['tır', 'kamyon'])) {
                    $aracBilgisi = $shipment->plaka . ($shipment->dorse_plakasi ? ' / ' . $shipment->dorse_plakasi : '');
                    $soforBilgisi = $shipment->sofor_adi;
                } elseif ($shipment->arac_tipi === 'gemi') {
                    $aracBilgisi = $shipment->gemi_adi;
                    $soforBilgisi = 'IMO: ' . $shipment->imo_numarasi;
                }

                return [
                    $shipment->id,
                    ucfirst($shipment->arac_tipi),
                    $shipment->musteri_adi ?? '-',
                    $aracBilgisi,
                    $soforBilgisi,
                    $shipment->kalkis_noktasi ?? $shipment->kalkis_limani ?? '-',
                    $shipment->varis_noktasi ?? $shipment->varis_limani ?? '-',
                    $shipment->kargo_icerigi,
                    $shipment->status ?? 'Aktif',
                    $shipment->cikis_tarihi ? \Carbon\Carbon::parse($shipment->cikis_tarihi)->format('d.m.Y') : '-'
                ];
            }
        );
    }

    public function destroy(Shipment $shipment)
    {
        // 1. Yetki Kontrolü
        $this->authorize('access-department', 'lojistik');

        if (Auth::id() !== $shipment->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return response()->json(['error' => 'Bu sevkiyatı sadece oluşturan kişi silebilir.'], 403);
        }

        try {
            DB::transaction(function () use ($shipment) {
                // 2. İlişkili Durakları Sil (Soft Delete ise onlar da işaretlenir)
                $shipment->stops()->delete();

                // 3. Fiziksel Dosyaları ve Polimorfik Kayıtları Sil
                foreach ($shipment->files as $file) {
                    if (Storage::disk('public')->exists($file->path)) {
                        Storage::disk('public')->delete($file->path);
                    }
                    $file->delete(); // İlişki üzerinden güvenli silme
                }

                // 4. Kanban Kartını Sil
                $shipment->kanbanCard()->delete();

                // 5. Sevkiyatı Sil (Soft Delete)
                $shipment->delete();
            });

            // AJAX isteği olduğu için JSON dönmek daha sağlıklıdır
            return response()->json(['success' => 'Sevkiyat ve ilişkili tüm veriler başarıyla silindi.']);

        } catch (\Exception $e) {
            // Hatanın detayını logla ki architect olarak inceleyebileyim
            \Log::error("Shipment Silme Hatası ID {$shipment->id}: " . $e->getMessage());

            return response()->json(['error' => 'Silme işlemi sırasında bir veritabanı hatası oluştu.'], 500);
        }
    }

    public function onayla(Request $request, Shipment $shipment)
    {
        $this->authorize('access-department', 'lojistik');
        $shipment->refresh();

        if ($shipment->onaylanma_tarihi) {
            return redirect()->route('home', ['open_modal' => $shipment->id])
                ->with('error', 'Bu sevkiyat zaten daha önce onaylanmış.');
        }

        $shipment->onaylanma_tarihi = Carbon::now();
        $shipment->onaylayan_user_id = Auth::id();
        $shipment->save();

        return redirect()->route('home', ['open_modal' => $shipment->id])
            ->with('success', 'Sevkiyat başarıyla onaylandı.');
    }

    public function onayiGeriAl(Request $request, Shipment $shipment)
    {
        $this->authorize('access-department', 'lojistik');
        $shipment->refresh();

        if (is_null($shipment->onaylanma_tarihi)) {
            return redirect()->route('home', ['open_modal' => $shipment->id])
                ->with('error', 'Bu sevkiyat zaten onaylanmamış durumdadır.');
        }

        $shipment->onaylanma_tarihi = null;
        $shipment->onaylayan_user_id = null;
        $shipment->save();

        return redirect()->route('home', ['open_modal' => $shipment->id])
            ->with('success', 'Sevkiyat onayı başarıyla geri alındı.');
    }

    /**
     * Filtreli Listeleme (Pagination Eklendi)
     */
    public function listAllFiltered(Request $request)
    {
        $this->authorize('access-department', 'lojistik');


        $query = Shipment::query();
        $user = Auth::user();
        $activeUnitId = session('active_unit_id') ?? $user->businessUnits->first()?->id;
        $isImportantFilter = $request->input('is_important', 'all');

        if ($isImportantFilter !== 'all' && $user && in_array($user->role, ['admin', 'yönetici'])) {
            if ($isImportantFilter === 'yes') {
                $query->where('is_important', true);
            } elseif ($isImportantFilter === 'no') {
                $query->where('is_important', false);
            }
        }

        if ($request->filled('shipment_type') && $request->input('shipment_type') !== 'all') {
            $query->where('shipment_type', $request->input('shipment_type'));
        }

        if ($request->filled('vehicle_type') && $request->input('vehicle_type') !== 'all') {
            $query->where('arac_tipi', $request->input('vehicle_type'));
        }

        if ($request->filled('cargo_content')) {
            $query->where('kargo_icerigi', 'LIKE', '%' . $request->input('cargo_content') . '%');
        }

        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where('cikis_tarihi', '>=', $dateFrom);
            } catch (\Exception $e) {
                return back()->with('error', 'Geçersiz başlangıç tarihi formatı.');
            }
        }
        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                $query->where('cikis_tarihi', '<=', $dateTo);
            } catch (\Exception $e) {
                return back()->with('error', 'Geçersiz bitiş tarihi formatı.');
            }
        }

        $shipments = $query->orderBy('cikis_tarihi', 'desc')
            ->paginate(15)
            ->appends($request->query());

        $vehicleTypes = ShipmentsVehicleType::pluck('name');
        $cargoContents = Shipment::distinct()->pluck('kargo_icerigi')->filter()->sort()->values();
        $filters = $request->only(['shipment_type', 'vehicle_type', 'cargo_content', 'date_from', 'date_to', 'is_important']);
        $logisticsBoards = \App\Models\KanbanBoard::where('user_id', $user->id)
            ->where('business_unit_id', $activeUnitId)
            ->where('module_scope', 'logistics')
            ->orderBy('name', 'asc')
            ->get();

        return view('shipments.list', compact('shipments', 'vehicleTypes', 'cargoContents', 'filters', 'logisticsBoards'));
    }

    public function show($id)
    {
        // Yetki Kontrolü 
        $this->authorize('access-department', 'lojistik');

        $shipment = Shipment::with('stops')->findOrFail($id);

        return view('shipments.show', compact('shipment'));
    }
}