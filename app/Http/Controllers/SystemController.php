<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use App\Models\MaintenancePlan;
use App\Models\Travel;
use App\Models\User;
// Eklediğin yeni EventType vs. varsa onları da ekle
use Illuminate\Support\Facades\Cache;

class SystemController extends Controller
{
    /**
     * AJAX isteği buraya gelir, güncel hash'i döndürür.
     */
    public function checkUpdates()
    {
        return response()->json([
            'hash' => self::calculateSystemHash()
        ]);
    }

    /**
     * MERKEZİ HASH HESAPLAMA FONKSİYONU
     * Bu fonksiyonu static yaptık ki ServiceProvider'dan da çağırabilelim.
     */
    public static function calculateSystemHash()
    {
        // Tüm kritik tabloların güncellenme zamanlarını alıyoruz
        $timestamps = [
            Shipment::max('updated_at'),
            ProductionPlan::max('updated_at'),
            Event::max('updated_at'),
            VehicleAssignment::max('updated_at'),
            MaintenancePlan::max('updated_at'),
            Travel::max('updated_at'),
            User::max('updated_at'),
        ];

        // Bu diziye göre benzersiz bir parmak izi oluşturuyoruz
        return md5(json_encode($timestamps));
    }
}