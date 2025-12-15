<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentStop;
use Illuminate\Http\Request;

class ShipmentStopController extends Controller
{
    public function store(Request $request, $shipmentId)
    {
        // 1. Validasyon
        $request->validate([
            'location_name' => 'required|string',
            'dropped_amount' => 'required|numeric|min:0',
            'stop_date' => 'required|date',
        ]);

        // 2. Ana Sevkiyatı Bul
        $shipment = Shipment::findOrFail($shipmentId);

        // 3. Matematiksel Hesaplama (Kritik Nokta)
        // Eğer daha önce durak varsa son duraktaki kalanı al, yoksa ana yükü al.
        $currentBalance = $shipment->latest_remaining_amount; // Modelde yazdığımız helper'ı kullandık

        $dropped = $request->dropped_amount;
        $newRemaining = $currentBalance - $dropped;

        // 4. Eksiye düşmeyi engelle (Opsiyonel ama güvenli)
        if ($newRemaining < 0) {
            return back()->withErrors(['msg' => 'Hata: İndirilen miktar, araçtaki yükten fazla olamaz!']);
        }

        // 5. Kayıt
        ShipmentStop::create([
            'shipment_id' => $shipment->id,
            'location_name' => $request->location_name,
            'dropped_amount' => $dropped,
            'remaining_amount' => $newRemaining, // Otomatik hesaplanan değer
            'stop_date' => $request->stop_date,
            'receiver_name' => $request->receiver_name,
            'note' => $request->note
        ]);

        return back()->with('success', 'Sevkiyat durağı başarıyla eklendi.');
    }

    // Durak silinirse (Yanlış işlem yapılırsa)
    public function destroy($id)
    {
        ShipmentStop::destroy($id);
        return back()->with('success', 'Durak kaydı silindi.');
    }
}
