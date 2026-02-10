<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingObserver
{
    public function created(Booking $booking)
    {
        // Tutar girilmemişse veya 0 ise masraf oluşturma
        if (!$booking->cost || $booking->cost <= 0) {
            return;
        }

        // Rezervasyon tipini masraf kategorisine map edelim
        $categories = [
            'flight' => 'Ulaşım',
            'hotel' => 'Konaklama',
            'bus' => 'Ulaşım',
            'train' => 'Ulaşım',
        ];

        // Rezervasyonun bağlı olduğu ana modele (Travel veya Event) polimorfik masraf ekle
        $parent = $booking->bookable;

        if ($parent) {
            $parent->expenses()->create([
                'business_unit_id' => $parent->business_unit_id,
                'category' => $categories[$booking->type] ?? 'Diğer',
                'amount' => $booking->cost,
                'currency' => $booking->currency, // Yeni eklediğimiz döviz alanı
                'description' => "Otomatik: {$booking->provider_name} Rezervasyonu (" . ($booking->confirmation_code ?? 'PNR yok') . ")",
                'receipt_date' => $booking->start_datetime ?? now(),
                'created_by' => Auth::id() ?? $booking->user_id,
            ]);
        }
    }
}