<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Travel;
use App\Models\User;
use App\Notifications\UpcomingTravelReminder;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendTravelReminders extends Command
{
    protected $signature = 'travel:send-reminders';
    protected $description = 'Yaklaşan seyahatler için bildirim gönderir';

    public function handle()
    {
        // 1. Yarın başlayacak ve henüz tamamlanmamış seyahatleri bul
        $upcomingTravels = Travel::whereDate('start_date', Carbon::tomorrow())
            ->where('status', '!=', 'completed')
            ->get();

        if ($upcomingTravels->isEmpty()) {
            return;
        }

        // 2. Bildirimi alacak yöneticileri bul (Admin + Ulaştırma Müdürleri)
        $managers = User::where(function ($query) {
            $query->where('role', 'admin')
                ->orWhere(function ($q) {
                    $q->where('role', 'müdür')
                        ->whereHas('department', fn($d) => $d->where('slug', 'ulastirma'));
                });
        })->get();

        // 3. Gönderim
        if ($managers->isNotEmpty()) {
            foreach ($upcomingTravels as $travel) {
                Notification::send($managers, new UpcomingTravelReminder($travel));
            }
        }
    }
}