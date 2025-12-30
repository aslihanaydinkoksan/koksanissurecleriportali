<?php

namespace App\Console\Commands;

use App\Models\MaintenancePlan;
use App\Models\User;
use App\Notifications\UpcomingMaintenanceNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class CheckUpcomingMaintenances extends Command
{
    protected $signature = 'maintenance:check-upcoming';
    protected $description = 'Yaklaşan bakım planlarını kontrol eder ve bildirim gönderir.';

    public function handle()
    {
        $tomorrow = \Carbon\Carbon::tomorrow();
        $this->info('📅 Hedef Tarih: ' . $tomorrow->format('d.m.Y'));

        // İlişkileri çekiyoruz
        $plans = \App\Models\MaintenancePlan::whereDate('planned_start_date', $tomorrow)
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->with(['asset', 'type', 'user', 'businessUnit'])
            ->get();

        $this->info("🔍 Bulunan bakım planı sayısı: " . $plans->count());

        foreach ($plans as $plan) {
            $this->info("--------------------------------------------------");
            $this->info("🔧 Bakım: {$plan->asset->name} (ID: {$plan->id})");

            // A. TEKNİSYEN / SORUMLU (SAHİBİ)
            if ($plan->user) {
                try {
                    $plan->user->notify(new \App\Notifications\UpcomingMaintenanceNotification($plan));
                    $this->info("   👤 [Sorumlu] Bildirildi: " . $plan->user->email);
                } catch (\Exception $e) {
                    $this->error("   ❌ Mail hatası: " . $e->getMessage());
                }
            }

            // B. İLGİLİ DEPARTMAN ÇALIŞANLARI (Rol Fark Etmeksizin)
            if ($plan->business_unit_id) {

                // Hedef: Bakım ve Üretim Departmanındaki HERKES
                $targetDepartments = ['Bakım', 'Üretim'];

                $users = \App\Models\User::whereHas('businessUnits', function ($q) use ($plan) {
                    $q->where('business_units.id', $plan->business_unit_id);
                })
                    ->whereHas('department', function ($q) use ($targetDepartments) {
                        $q->whereIn('name', $targetDepartments);
                    })
                    ->get();

                if ($users->count() > 0) {
                    \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\UpcomingMaintenanceNotification($plan));
                    $this->info("   🏢 [Genel] " . $users->count() . " kişiye (Bakım/Üretim) bildirildi.");

                    // Kimlere gittiğini loglayalım
                    foreach ($users as $u) {
                        $this->line("      - " . $u->email);
                    }
                } else {
                    $this->warn("   ⚠️ İlgili departmanlarda çalışan bulunamadı.");
                }
            } else {
                $this->warn("   ℹ️ Bu plan bir fabrikaya bağlı değil.");
            }
        }

        return 0;
    }
}