<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Birim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\CsvExporter;

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
                'mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png,txt',
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
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');
        if (Auth::id() !== $shipment->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu sevkiyatı sadece oluşturan kişi düzenleyebilir.');
        }
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
            'ek_dosya' => ['nullable', 'file', 'mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png,txt', 'max:5120'],
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
        // 1. Yetki Kontrolü (İsteğe bağlı, kodunda yorum satırıydı)
        // $this->authorize('access-department', 'lojistik');

        $fileName = 'sevkiyat_detay_' . $shipment->id . '.csv';

        // 2. Dinamik Başlık Mantığını Hazırla
        // Tüm senaryolardaki ortak başlık havuzu
        $ortakBasliklar = [
            'ID',
            'Araç Tipi', // Buradan kesip araya özel sütunları ekleyeceğiz
            'Kargo Yükü',
            'Kargo Tipi',
            'Kargo Miktarı',
            'Çıkış Tarihi',
            'Tahmini Varış Tarihi',
            'Açıklamalar',
            'İhracat/İthalat'
        ];

        $ozelBasliklar = [];

        // Araç tipine göre araya girecek başlıkları seç
        if (in_array($shipment->arac_tipi, ['tır', 'kamyon'])) {
            $ozelBasliklar = ['Plaka', 'Dorse Plakası', 'Şoför Adı', 'Kalkış Noktası', 'Varış Noktası'];
        } elseif ($shipment->arac_tipi === 'gemi') {
            $ozelBasliklar = ['IMO Numarası', 'Gemi Adı', 'Kalkış Limanı', 'Varış Limanı'];
        }

        // Başlıkları Birleştir: [İlk 2 Sütun] + [Özel Sütunlar] + [Kalan Sütunlar]
        $finalHeaders = array_merge(
            array_slice($ortakBasliklar, 0, 2),
            $ozelBasliklar,
            array_slice($ortakBasliklar, 2)
        );

        // 3. Servisi Çağır
        // Tek bir kayıt olsa bile standart yapı bozulmasın diye 'where' ile sorgu gibi gönderiyoruz.
        return CsvExporter::streamDownload(
            query: Shipment::where('id', $shipment->id),
            headers: $finalHeaders,
            fileName: $fileName,
            rowMapper: function ($record) {
                // Not: $record, burada $shipment'in veritabanından gelen kopyasıdır.
    
                $sevkiyatTuru = match ($record->shipment_type) {
                    'export' => 'İhracat',
                    'import' => 'İthalat',
                    default => $record->shipment_type
                };

                // Ortak Veriler
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

                // Özel Veriler (Başlık sırasına sadık kalarak)
                $ozelData = [];
                if (in_array($record->arac_tipi, ['tır', 'kamyon'])) {
                    $ozelData = [
                        $record->plaka,
                        // Kamyon ise Dorse Plakası boş string olsun
                        $record->arac_tipi === 'kamyon' ? '' : $record->dorse_plakasi,
                        $record->sofor_adi,
                        $record->kalkis_noktasi,
                        $record->varis_noktasi
                    ];
                } elseif ($record->arac_tipi === 'gemi') {
                    $ozelData = [
                        $record->imo_numarasi,
                        $record->gemi_adi,
                        $record->kalkis_limani,
                        $record->varis_limani
                    ];
                }

                // Verileri aynı mantıkla birleştirip döndür
                return array_merge(
                    array_slice($ortakData, 0, 2),
                    $ozelData,
                    array_slice($ortakData, 2)
                );
            }
        );
    }

    public function destroy(Shipment $shipment)
    {
        // YENİ EKLENDİ: Kullanıcının 'lojistik' birimine erişimi var mı?
        $this->authorize('access-department', 'lojistik');

        if (Auth::id() !== $shipment->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu sevkiyatı sadece oluşturan kişi silebilir.');
        }
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
        $user = Auth::user();
        $isImportantFilter = $request->input('is_important', 'all');

        // Bu filtreyi sadece admin veya yönetici ise uygula
        if ($isImportantFilter !== 'all' && $user && in_array($user->role, ['admin', 'yönetici'])) {


            if ($isImportantFilter === 'yes') {
                $query->where('is_important', true);
            } elseif ($isImportantFilter === 'no') {
                $query->where('is_important', false);
            }
            // 'all' ise hiçbir şey yapma, tümünü getir.
        }

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
        $filters = $request->only(['shipment_type', 'vehicle_type', 'cargo_content', 'date_from', 'date_to', 'is_important']);
        return view('shipments.list', compact('shipments', 'vehicleTypes', 'cargoContents', 'filters'));
    }
}
