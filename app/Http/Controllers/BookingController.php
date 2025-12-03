<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Travel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'booking_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,msg,eml|max:10240' // 10MB limit
        ]);

        // Seyahate bağlı rezervasyonu oluştur
        $booking = $model->bookings()->create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'provider_name' => $validated['provider_name'],
            'confirmation_code' => $validated['confirmation_code'],
            'cost' => $validated['cost'],
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
        // 24 Saat Kontrolü
        if (!$booking->is_editable && !Auth::user()->can('is-global-manager')) {
            return redirect()->back()->with('error', 'Başlangıç saatine 24 saatten az kaldığı için bu rezervasyon değiştirilemez!');
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
            return redirect()->back()->with('error', 'Bu rezervasyonu güncellemek için süre doldu!');
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
            'booking_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,msg,eml|max:10240'
        ]);

        // Rezervasyonu güncelle
        $booking->update($validated);

        // YENİ dosyalar varsa, mevcutların üzerine ekle (eskileri silme)
        if ($request->hasFile('booking_files')) {
            foreach ($request->file('booking_files') as $file) {
                $booking->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // Ana seyahat planının detay sayfasına geri yönlendir
        return redirect()->route('travels.show', $booking->travel)
            ->with('success', 'Rezervasyon kaydı başarıyla güncellendi!');
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
        if (Auth::id() !== $booking->user_id && !Auth::user()->can('is-global-manager')) {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        $booking->delete();

        return redirect()->back()->with('success', 'Rezervasyon kaydı silindi.');
    }
}
