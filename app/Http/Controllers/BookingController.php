<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Travel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Services\CsvExporter;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::with(['bookable', 'user'])
            ->orderBy('start_datetime', 'desc')
            ->paginate(20); // Sayfa başına 20 kayıt

        return response()->view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('bookings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $modelId) // $travel'ı al
    {
        // 1. Model Sınıfını Rotadan Al (routes/web.php'deki 'defaults' kısmından)
        $modelClass = $request->route('model_type');

        // 2. Modeli Bul (Travel mı Event mi olduğu fark etmez)
        $model = $modelClass::findOrFail($modelId);
        $validated = $request->validate([
            'type' => 'required|string|in:flight,bus,hotel,car_rental,train,other',
            'provider_name' => 'required|string|max:255',
            'confirmation_code' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'booking_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,msg,eml|max:10240', // 10MB limit
            'status' => 'nullable|string|in:planned,completed,cancelled,postponed',
        ]);

        // Seyahate bağlı rezervasyonu oluştur
        $booking = $model->bookings()->create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'provider_name' => $validated['provider_name'],
            'confirmation_code' => $validated['confirmation_code'],
            'cost' => $validated['cost'] ?? null,
            'start_datetime' => $validated['start_datetime'],
            'end_datetime' => $validated['end_datetime'],
            'notes' => $validated['notes'],
        ]);

        // Spatie MediaLibrary ile Dosyaları Ekle
        if ($request->hasFile('booking_files')) {
            foreach ($request->file('booking_files') as $file) {
                // 'attachments' koleksiyonuna ekle
                $booking->addMedia($file)->toMediaCollection('attachments');
            }
        }
        $routePrefix = ($model instanceof \App\Models\Travel) ? 'travels' : 'service.events';

        return redirect()->route($routePrefix . '.show', $model)
            ->with('success', 'Yeni rezervasyon kaydı başarıyla eklendi!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(Booking $booking)
    {

        if (!$booking->is_editable && !Auth::user()->can('is-global-manager')) {
            return redirect()->back()
                ->with('error', 'Bu rezervasyona 24 saatten az kaldığı (veya tarihi geçtiği) için düzenlenemez!');
        }
        $isOwner = auth()->id() == $booking->user_id;

        // 2. Kullanıcının özel yetkisi var mı? (Özgül Hanım vb.)
        $canManage = auth()->user()->can('manage all bookings') || auth()->user()->can('is-global-manager');

        // Eğer ne sahip ne de yönetici ise -> HATA VER
        if (!$isOwner && !$canManage) {
            abort(403, 'Bu rezervasyonu düzenleme yetkiniz yok.');
        }

        // Eğer sadece sahipse (yönetici değilse), 24 saat kuralına takılıyor mu?
        // Yöneticiyse ($canManage) süreye takılmadan geçebilir.
        if (!$canManage && !$booking->is_editable) {
            abort(403, 'Düzenleme süresi (24 saat) dolmuştur.');
        }
        if (Auth::id() !== $booking->user_id && !Auth::user()->can('is-global-manager')) {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        return response()->view('bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Booking $booking)
    {
        // 24 Saat Kontrolü
        if (!$booking->is_editable && !Auth::user()->can('is-global-manager')) {
            return redirect()->back()
                ->with('error', 'Süre dolduğu için güncelleme yapılamaz!');
        }
        $isOwner = auth()->id() == $booking->user_id;

        // 2. Kullanıcının özel yetkisi var mı? (Özgül Hanım vb.)
        $canManage = auth()->user()->can('manage all bookings') || auth()->user()->can('is-global-manager');

        // Eğer ne sahip ne de yönetici ise -> HATA VER
        if (!$isOwner && !$canManage) {
            abort(403, 'Bu rezervasyonu düzenleme yetkiniz yok.');
        }

        // Eğer sadece sahipse (yönetici değilse), 24 saat kuralına takılıyor mu?
        // Yöneticiyse ($canManage) süreye takılmadan geçebilir.
        if (!$canManage && !$booking->is_editable) {
            abort(403, 'Düzenleme süresi (24 saat) dolmuştur.');
        }

        if (Auth::id() !== $booking->user_id && !Auth::user()->can('is-global-manager')) {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        // Validasyon (store ile aynı)
        $validated = $request->validate([
            'type' => 'required|string|in:flight,hotel,car_rental,train,other',
            'provider_name' => 'required|string|max:255',
            'confirmation_code' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'booking_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,msg,eml|max:10240',
            'status' => 'nullable|string|in:planned,completed,cancelled,postponed',
        ]);

        // Rezervasyonu güncelle
        $booking->update($validated);

        // YENİ dosyalar varsa, mevcutların üzerine ekle (eskileri silme)
        if ($request->hasFile('booking_files')) {
            foreach ($request->file('booking_files') as $file) {
                $booking->addMedia($file)->toMediaCollection('attachments');
            }
        }

        $model = $booking->bookable; // Travel veya Event modelini al

        // Model tipine göre doğru rotayı belirle
        $routePrefix = ($model instanceof \App\Models\Travel) ? 'travels' : 'service.events';

        return redirect()->route($routePrefix . '.show', $model)
            ->with('success', 'Rezervasyon durumu güncellendi!');
    }

    /**
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // 24 Saat Kontrolü
        if (!$booking->is_editable && !Auth::user()->can('is-global-manager')) {
            return redirect()->back()->with('error', 'Bu rezervasyonu güncellemek için süre doldu!');
        }
        $isOwner = auth()->id() == $booking->user_id;
        $canManage = auth()->user()->can('manage all bookings') || auth()->user()->can('is-global-manager');

        if (!$isOwner && !$canManage) {
            abort(403, 'Bu rezervasyonu silme yetkiniz yok.');
        }

        if (!$canManage && !$booking->is_editable) {
            abort(403, 'Silme süresi (24 saat) dolmuştur.');
        }
        if (Auth::id() !== $booking->user_id && !Auth::user()->can('is-global-manager')) {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        $booking->delete();

        return redirect()->back()->with('success', 'Rezervasyon kaydı silindi.');
    }
    /**
     * Rezervasyonları CSV olarak dışa aktar
     */
    public function export()
    {
        $fileName = 'rezervasyonlar-' . date('d-m-Y') . '.csv';

        // 1. SORGULAMA
        // 'bookable' ilişkisini çekiyoruz. Laravel otomatik olarak
        // Travel ise Travel tablosuna, Event ise Event tablosuna gidip veriyi alacak.
        $query = Booking::with(['user', 'bookable'])->latest();

        // 2. BAŞLIKLAR
        $headers = [
            'ID',
            'Tür',
            'Sağlayıcı',
            'Onay Kodu',
            'Bağlı Olduğu Kayıt', // Örn: Seyahat: Ankara Gezisi
            'Başlangıç',
            'Bitiş',
            'Maliyet',
            'Durum',
            'Notlar',
            'Oluşturan',
            'Kayıt Tarihi'
        ];

        // 3. SERVİSİ ÇAĞIR
        return CsvExporter::streamDownload(
            query: $query,
            headers: $headers,
            fileName: $fileName,
            rowMapper: function ($booking) {

                // -- POLİMORFİK VERİ ÇÖZÜMLEME --
                // Rezervasyon neye bağlı? (Event mi Travel mı?)
                $bagliKayit = 'Bağımsız / Silinmiş';

                if ($booking->bookable) {
                    // bookable_type genellikle "App\Models\Travel" şeklinde tam path döner.
                    // Sondaki sınıf ismini almak için class_basename kullanabiliriz veya string kontrolü yaparız.
    
                    if ($booking->bookable_type === 'App\Models\Travel') {
                        // Travel modelinde isim sütunu: 'name'
                        $bagliKayit = '[Seyahat] ' . $booking->bookable->name;
                    } elseif ($booking->bookable_type === 'App\Models\Event') {
                        // Event modelinde isim sütunu: 'title'
                        $bagliKayit = '[Etkinlik] ' . $booking->bookable->title;
                    } else {
                        // Bilinmeyen bir tür eklenirse
                        $bagliKayit = '[' . class_basename($booking->bookable_type) . '] ID:' . $booking->bookable_id;
                    }
                }

                // -- TÜR TÜRKÇELEŞTİRME --
                $tur = match ($booking->type) {
                    'flight' => 'Uçak Bileti',
                    'hotel' => 'Otel',
                    'car' => 'Araç Kiralama',
                    'bus' => 'Otobüs',
                    'transfer' => 'Transfer',
                    default => ucfirst($booking->type ?? '-'),
                };

                // -- DURUM TÜRKÇELEŞTİRME --
                $durum = match ($booking->status) {
                    'planned' => 'Planlandı',
                    'confirmed' => 'Onaylandı',
                    'cancelled' => 'İptal',
                    'completed' => 'Tamamlandı',
                    default => ucfirst($booking->status ?? '-'),
                };

                // -- MALİYET FORMATLAMA --
                // 10000.00 -> 10.000,00 TL
                $maliyet = $booking->cost
                    ? number_format($booking->cost, 2, ',', '.') . ' TL'
                    : '-';

                // -- SATIR VERİSİ --
                return [
                    $booking->id,
                    $tur,
                    $booking->provider_name ?? '-',
                    $booking->confirmation_code ?? '-',
                    $bagliKayit, // Hazırladığımız dinamik veri
                    $booking->start_datetime ? $booking->start_datetime->format('d.m.Y H:i') : '-',
                    $booking->end_datetime ? $booking->end_datetime->format('d.m.Y H:i') : '-',
                    $maliyet,
                    $durum,
                    $booking->notes ?? '',
                    $booking->user ? $booking->user->name : 'Bilinmiyor',
                    $booking->created_at->format('d.m.Y H:i')
                ];
            }
        );
    }
}
