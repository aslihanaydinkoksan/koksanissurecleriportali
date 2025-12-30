<?php

namespace App\Console\Commands;

use App\Models\ProductionPlan;
use App\Models\User; // Yöneticiye de atmak istersen lazım olabilir
use App\Notifications\UpcomingProductionPlanNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUpcomingProductionPlans extends Command
{
    protected $signature = 'production:check-upcoming';
    protected $description = 'Yarın başlayacak üretim planlarını kontrol eder.';

    public function handle()
    {
        $tomorrow = \Carbon\Carbon::tomorrow();
        $this->info('📅 Hedef Tarih: ' . $tomorrow->format('d.m.Y'));

        // İlişkileri çekiyoruz
        $plans = \App\Models\ProductionPlan::whereDate('week_start_date', $tomorrow)
            ->with(['user', 'businessUnit'])
            ->get();

        $this->info("🔍 Bulunan üretim planı sayısı: " . $plans->count());

        foreach ($plans as $plan) {
            $this->info("--------------------------------------------------");
            $this->info("🏭 Plan: {$plan->plan_title} (ID: {$plan->id})");

            // A. PLANLAYICI (SAHİBİ)
            if ($plan->user) {
                try {
                    $plan->user->notify(new \App\Notifications\UpcomingProductionPlanNotification($plan));
                    $this->info("   👤 [Planlayıcı] Bildirildi: " . $plan->user->email);
                } catch (\Exception $e) {
                    $this->error("   ❌ Mail hatası: " . $e->getMessage());
                }
            }

            // B. İLGİLİ DEPARTMAN ÇALIŞANLARI (Rol Fark Etmeksizin)
            if ($plan->business_unit_id) {

                // Hedef: Sadece Üretim Departmanındaki HERKES
                $targetDepartments = ['Üretim'];

                $users = \App\Models\User::whereHas('businessUnits', function ($q) use ($plan) {
                    $q->where('business_units.id', $plan->business_unit_id);
                })
                    ->whereHas('department', function ($q) use ($targetDepartments) {
                        $q->whereIn('name', $targetDepartments);
                    })
                    ->get();

                if ($users->count() > 0) {
                    \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\UpcomingProductionPlanNotification($plan));
                    $this->info("   🏢 [Genel] " . $users->count() . " kişiye (Üretim) bildirildi.");

                    foreach ($users as $u) {
                        $this->line("      - " . $u->email);
                    }
                } else {
                    $this->warn("   ⚠️ Üretim departmanında çalışan bulunamadı.");
                }
            }
        }

        return 0;
    }
}