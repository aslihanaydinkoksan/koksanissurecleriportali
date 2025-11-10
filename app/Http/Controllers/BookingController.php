<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Travel;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Travel $travel) // $travel'ı al
    {
        $validated = $request->validate([
            'type' => 'required|string|in:flight,hotel,car_rental,train,other',
            'provider_name' => 'required|string|max:255',
            'confirmation_code' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'booking_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,msg,eml|max:10240' // 10MB limit
        ]);

        // Seyahate bağlı rezervasyonu oluştur
        $booking = $travel->bookings()->create([
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

        return redirect()->route('travels.show', $travel)
            ->with('success', 'Yeni rezervasyon kaydı başarıyla eklendi!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $travel = $booking->travel; // Geri dönmek için seyahati al

        // Yetki kontrolü (sadece admin veya ekleyen silebilir)
        if (Auth::id() !== $booking->user_id && !Auth::user()->can('is-global-manager')) {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        $booking->delete();

        return redirect()->route('travels.show', $travel)
            ->with('success', 'Rezervasyon kaydı silindi.');
    }
}
