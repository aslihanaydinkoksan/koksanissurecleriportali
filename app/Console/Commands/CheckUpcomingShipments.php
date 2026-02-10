<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use App\Models\User;
use App\Notifications\UpcomingShipmentNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class CheckUpcomingShipments extends Command
{
    /**
     * Komutun adı (Terminalden çağırmak için)
     */
    protected $signature = 'shipment:check-upcoming';

    protected $description = 'Yarın çıkışı olacak sevkiyatları kontrol eder ve bildirir.';

    public function handle()
    {
        $tomorrow = \Carbon\Carbon::tomorrow();
        $msg = '📅 Sevkiyat Kontrolü Başladı. Hedef Tarih: ' . $tomorrow->format('d.m.Y');
        $this->info($msg);
        Log::info($msg);

        // İlişkileri çekiyoruz (onaylanma_tarihi filtresiyle)
        $shipments = \App\Models\Shipment::whereDate('cikis_tarihi', $tomorrow)
            ->with(['user', 'businessUnit'])
            ->get();

        $countMsg = "🔍 Bulunan sevkiyat sayısı: " . $shipments->count();
        $this->info($countMsg);
        Log::info($countMsg);

        foreach ($shipments as $shipment) {
            $this->info("--------------------------------------------------");
            $this->info("🚛 Sevkiyat: {$shipment->plaka} (ID: {$shipment->id})");

            // A. OLUŞTURAN KİŞİ
            if ($shipment->user) {
                try {
                    $shipment->user->notify(new \App\Notifications\UpcomingShipmentNotification($shipment));
                    $this->info("   👤 [Oluşturan] Bildirildi: " . $shipment->user->email);
                } catch (\Exception $e) {
                    $this->error("   ❌ Mail hatası: " . $e->getMessage());
                }
            }

            // B. İLGİLİ DEPARTMAN ÇALIŞANLARI (Rol Fark Etmeksizin)
            if ($shipment->business_unit_id) {

                // Hedef: Lojistik ve Ulaştırma Departmanındaki HERKES
                $targetDepartments = ['Lojistik', 'Ulaştırma'];

                $users = \App\Models\User::whereHas('businessUnits', function ($q) use ($shipment) {
                    $q->where('business_units.id', $shipment->business_unit_id);
                })
                    ->whereHas('department', function ($q) use ($targetDepartments) {
                        $q->whereIn('name', $targetDepartments);
                    })
                    ->get();

                if ($users->count() > 0) {
                    \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\UpcomingShipmentNotification($shipment));
                    $this->info("   🏢 [Genel] " . $users->count() . " kişiye (Lojistik/Ulaştırma) bildirildi.");

                    foreach ($users as $u) {
                        $this->line("      - " . $u->email);
                    }
                } else {
                    $this->warn("   ⚠️ Lojistik/Ulaştırma departmanlarında çalışan bulunamadı.");
                }
            } else {
                $this->warn("   ℹ️ Bu sevkiyat bir fabrikaya bağlı değil.");
            }
        }

        return 0;
    }
}