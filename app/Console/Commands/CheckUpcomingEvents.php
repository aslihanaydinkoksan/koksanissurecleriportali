<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Notifications\UpcomingEventNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class CheckUpcomingEvents extends Command
{
    protected $signature = 'event:check-upcoming';
    protected $description = 'Yarınki takvim etkinliklerini kontrol eder ve bildirir.';

    public function handle()
    {
        $tomorrow = \Carbon\Carbon::tomorrow();
        $this->info('📅 Hedef Tarih: ' . $tomorrow->format('d.m.Y') . ' (Yarın)');

        // 1. Verileri Çekme
        $events = \App\Models\Event::whereDate('start_datetime', $tomorrow)
            ->with(['user', 'customerVisit.customer', 'businessUnit'])
            ->get();

        $count = $events->count();
        $this->info("🔍 Bulunan etkinlik sayısı: {$count}");

        if ($count === 0) {
            $this->warn('   Veritabanında yarın tarihli bir etkinlik bulunamadı.');
            return 0;
        }

        // 2. Döngü
        foreach ($events as $event) {
            $this->info("--------------------------------------------------");
            $this->info("📌 Etkinlik: {$event->title} (ID: {$event->id})");
            $this->line("   Tür: " . ($event->event_type ?? 'Belirtilmemiş'));

            // --- A. ETKİNLİK SAHİBİNİ BİLGİLENDİR ---
            if ($event->user) {
                try {
                    $event->user->notify(new \App\Notifications\UpcomingEventNotification($event));
                    $this->info("   👤 [Sahibi] Mail gönderildi: " . $event->user->email);
                } catch (\Exception $e) {
                    $this->error("   ❌ [Sahibi] Mail hatası: " . $e->getMessage());
                }
            }

            // --- B. İLGİLİ DEPARTMAN ÇALIŞANLARI (Rol Fark Etmeksizin) ---
            if ($event->business_unit_id) {

                // 1. DEPARTMAN EŞLEŞTİRMESİ
                // Departments tablosundaki MEVCUT isimler: Lojistik, Üretim, İdari İşler, Bakım, Ulaştırma
                $targetDepartments = match ($event->event_type) {
                    'customer_visit' => ['İdari İşler'], // Satış/Pazarlama tablosunda yoksa İdari İşler en yakınıdır
                    'meeting' => ['İdari İşler'],
                    'maintenance' => ['Bakım', 'Üretim'],
                    'production' => ['Üretim'],
                    'logistics' => ['Lojistik', 'Ulaştırma'],
                    default => [],
                };

                // 2. KULLANICI SORGUSU (Yönetici şartı KALKTI)
                // O fabrikada ve o departmanda çalışan herkesi getir.
                $users = \App\Models\User::whereHas('businessUnits', function ($q) use ($event) {
                    $q->where('business_units.id', $event->business_unit_id);
                })
                    ->whereHas('department', function ($q) use ($targetDepartments) {
                        $q->whereIn('name', $targetDepartments);
                    })
                    ->get();

                // 3. GÖNDERİM
                if ($users->count() > 0) {
                    \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\UpcomingEventNotification($event));

                    $this->info("   🏢 [Genel] " . $users->count() . " kişiye (" . implode(',', $targetDepartments) . ") bildirildi.");
                    foreach ($users as $u) {
                        $this->line("      - " . $u->email);
                    }
                } else {
                    // Eğer hedef departman listesi boşsa veya kullanıcı yoksa uyar
                    if (!empty($targetDepartments)) {
                        $this->warn("   ⚠️ Hedeflenen departmanlarda (" . implode(',', $targetDepartments) . ") çalışan bulunamadı.");
                    } else {
                        $this->line("   ℹ️ Bu etkinlik türü için özel bir departman eşleşmesi yok.");
                    }
                }

            } else {
                $this->line("   ℹ️ Bu etkinlik bir fabrikaya bağlı değil.");
            }
        }

        $this->info("--------------------------------------------------");
        $this->info("✅ İşlem tamamlandı.");

        return 0;
    }
}