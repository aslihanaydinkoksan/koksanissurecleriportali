<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Birim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ShipmentController extends Controller
{
    /**
     * Yeni bir sevkiyat oluşturma formunu gösterir.
     */
    public function create()
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        $birimler = Birim::orderBy('ad')->get();
        return view('shipments.create', compact('birimler'));
    }

    /**
     * Formdan gelen yeni sevkiyat kaydını veritabanında saklar.
     */
    public function store(Request $request)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        $validatedData = $request->validate([
            'arac_tipi' => 'required|string|in:tır,gemi,kamyon',
            'plaka' => 'required_if:arac_tipi,tır,kamyon|nullable|string|max:255',
            'dorse_plakasi' => 'required_if:arac_tipi,tır|nullable|string|max:255',
            'sofor_adi' => 'nullable|string|max:255',
            'imo_numarasi' => 'required_if:arac_tipi,gemi|nullable|string|max:255',
            'gemi_adi' => 'required_if:arac_tipi,gemi|nullable|string|max:255',
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
            'ek_dosya' => [
                'nullable',
                'file',
                'mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        if ($request->hasFile('ek_dosya')) {
            $dosyaYolu = $request->file('ek_dosya')->store('sevkiyat_dosyalari', 'public');
            $validatedData['dosya_yolu'] = $dosyaYolu;
        }

        $validatedData['user_id'] = Auth::id();
        Shipment::create($validatedData);

        return redirect()->route('shipments.create')->with('success', 'Sevkiyat kaydı başarıyla oluşturuldu!');
    }

    /**
     * Belirtilen sevkiyatı düzenleme formunu gösterir.
     */
    public function edit(Shipment $shipment)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        $birimler = Birim::orderBy('ad')->get();
        return view('shipments.edit', compact('shipment', 'birimler'));
    }

    /**
     * Veritabanındaki belirtilen sevkiyatı günceller.
     */
    public function update(Request $request, Shipment $shipment)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        $validatedData = $request->validate([
            'arac_tipi' => 'required|string|in:tır,gemi,kamyon',
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
            'ek_dosya' => ['nullable', 'file', 'mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png', 'max:5120'],
            'dosya_sil' => 'nullable|boolean',
        ]);

        // --- DOSYA YÖNETİMİ ---
        if ($request->hasFile('ek_dosya')) {
            if ($shipment->dosya_yolu) {
                Storage::disk('public')->delete($shipment->dosya_yolu);
            }
            $dosyaYolu = $request->file('ek_dosya')->store('sevkiyat_dosyalari', 'public');
            $validatedData['dosya_yolu'] = $dosyaYolu;
        } elseif ($request->has('dosya_sil') && $request->input('dosya_sil') == '1') {
            if ($shipment->dosya_yolu) {
                Storage::disk('public')->delete($shipment->dosya_yolu);
            }
            $validatedData['dosya_yolu'] = null;
        }
        // --- DOSYA YÖNETİMİ SONU ---

        $shipment->update($validatedData);

        return redirect()->route('home')->with('success', 'Sevkiyat kaydı başarıyla güncellendi!');
    }

    /**
     * Belirtilen sevkiyatı bir CSV dosyası olarak indirir (Dinamik Sütunlar).
     */
    public function export(Shipment $shipment)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        $fileName = 'sevkiyat_detay_' . $shipment->id . '.csv';

        $headers = [
            'Content-type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];
        $sevkiyatTuruTurkce = match ($shipment->shipment_type) {
            'export' => 'İhracat',
            'import' => 'İthalat',
            default => $shipment->shipment_type
        };

        // --- DİNAMİK SÜTUN ve VERİ OLUŞTURMA ---
        $ortakColumns = [
            'ID',
            'Araç Tipi',
            'Kargo Yükü',
            'Kargo Tipi',
            'Kargo Miktarı',
            'Çıkış Tarihi',
            'Tahmini Varış Tarihi',
            'Açıklamalar',
            'İhracat/İthalat'
        ];
        $ortakData = [
            $shipment->id,
            ucfirst($shipment->arac_tipi),
            $shipment->kargo_icerigi,
            $shipment->kargo_tipi,
            $shipment->kargo_miktari,
            Carbon::parse($shipment->cikis_tarihi)->format('d.m.Y H:i'),
            Carbon::parse($shipment->tahmini_varis_tarihi)->format('d.m.Y H:i'),
            $shipment->aciklamalar,
            $sevkiyatTuruTurkce
        ];

        $ozelColumns = [];
        $ozelData = [];

        if ($shipment->arac_tipi === 'tır' || $shipment->arac_tipi === 'kamyon') {
            // YENİ SÜTUNLARI EKLE
            $ozelColumns = ['Plaka', 'Dorse Plakası', 'Şoför Adı', 'Kalkış Noktası', 'Varış Noktası'];
            $ozelData = [
                $shipment->plaka,
                $shipment->dorse_plakasi,
                $shipment->sofor_adi,
                $shipment->kalkis_noktasi, // <-- YENİ
                $shipment->varis_noktasi   // <-- YENİ
            ];

            if ($shipment->arac_tipi === 'kamyon' && isset($ozelData[1])) {
                $ozelData[1] = '';
            }
        } elseif ($shipment->arac_tipi === 'gemi') {
            $ozelColumns = ['IMO Numarası', 'Gemi Adı', 'Kalkış Limanı', 'Varış Limanı'];
            $ozelData = [$shipment->imo_numarasi, $shipment->gemi_adi, $shipment->kalkis_limani, $shipment->varis_limani];
        }

        $finalColumns = array_merge(array_slice($ortakColumns, 0, 2), $ozelColumns, array_slice($ortakColumns, 2));
        $finalData = array_merge(array_slice($ortakData, 0, 2), $ozelData, array_slice($ortakData, 2));
        // --- DİNAMİK SÜTUN ve VERİ OLUŞTURMA SONU ---

        $callback = function () use ($finalColumns, $finalData) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $finalColumns, ';');
            fputcsv($file, $finalData, ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Shipment $shipment)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        // Güvenlik: Sadece 'admin' rolündeki kullanıcının silme işlemi yapabildiğinden emin oluyoruz.
        /*if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Bu işlemi yapma yetkiniz bulunmamaktadır.');
        } */
        // Soft delete işlemini gerçekleştir.
        $shipment->delete();

        return redirect()->route('home')->with('success', 'Sevkiyat kaydı başarıyla silindi.');
    }

    public function onayla(Request $request, Shipment $shipment)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
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

    // --- YENİ EKLENEN FONKSİYON ---
    /**
     * Sevkiyat onayını geri alır.
     */
    public function onayiGeriAl(Request $request, Shipment $shipment)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        // Sevkiyatı yeniden yükle (başka kullanıcı değiştirmiş olabilir)
        $shipment->refresh();

        // Eğer sevkiyat zaten onaylanmamışsa hata döndür
        if (is_null($shipment->onaylanma_tarihi)) {
            return redirect()->route('home', ['open_modal' => $shipment->id])
                ->with('error', 'Bu sevkiyat zaten onaylanmamış durumdadır.');
        }

        // Onayı geri al
        $shipment->onaylanma_tarihi = null;
        $shipment->onaylayan_user_id = null;
        $shipment->save();

        // Modalı açık tutarak geri dön
        return redirect()->route('home', ['open_modal' => $shipment->id])
            ->with('success', 'Sevkiyat onayı başarıyla geri alındı.');
    }
    // --- YENİ FONKSİYON SONU ---
    /**
     * Sadece 'import' (İthalat) tipindeki sevkiyatları listeler.
     */
    public function listAllFiltered(Request $request)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        // Temel sorguyu başlat
        $query = Shipment::query();

        // 1. Sevkiyat Türüne Göre Filtrele
        if ($request->filled('shipment_type') && $request->input('shipment_type') !== 'all') {
            $query->where('shipment_type', $request->input('shipment_type'));
        }

        // 2. Araç Tipine Göre Filtrele
        if ($request->filled('vehicle_type') && $request->input('vehicle_type') !== 'all') {
            $query->where('arac_tipi', $request->input('vehicle_type'));
        }

        // 3. Kargo İçeriğine Göre Filtrele (Benzerlik araması için LIKE)
        if ($request->filled('cargo_content')) {
            $query->where('kargo_icerigi', 'LIKE', '%' . $request->input('cargo_content') . '%');
        }

        // 4. Tarih Aralığına Göre Filtrele (Çıkış Tarihi baz alınmıştır, değiştirilebilir)
        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where('cikis_tarihi', '>=', $dateFrom);
            } catch (\Exception $e) {
                // Geçersiz tarih formatı girilirse görmezden gel veya hata mesajı ver
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

        // Sonuçları en yeniden eskiye sırala ve al
        $shipments = $query->orderBy('cikis_tarihi', 'desc')->get(); // Veya paginate(15)

        // Filtre dropdownları için seçenekleri al
        // distinct() null değerleri de getirebilir, filter() ile temizle
        $vehicleTypes = Shipment::distinct()->pluck('arac_tipi')->filter()->sort()->values();
        $cargoContents = Shipment::distinct()->pluck('kargo_icerigi')->filter()->sort()->values();

        // İsteği de view'a gönderelim ki form tekrar doldurulabilsin
        $filters = $request->only(['shipment_type', 'vehicle_type', 'cargo_content', 'date_from', 'date_to']);

        return view('shipments.list', compact('shipments', 'vehicleTypes', 'cargoContents', 'filters'));
    }
}
