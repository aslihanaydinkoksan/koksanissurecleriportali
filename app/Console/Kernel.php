<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 1. Bakım Kontrolleri (Teknik Ekip) - 09:00
        $schedule->command('maintenance:check-upcoming')->dailyAt('09:00');

        // 2. Sevkiyat Kontrolleri (Lojistik) - 08:00
        $schedule->command('shipment:check-upcoming')->everyMinute();

        // 3. Etkinlik ve Ziyaretler (Satış/İdari) - 08:30
        // Mesai başlamadan hatırlatma iyidir.
        $schedule->command('event:check-upcoming')->dailyAt('08:30');

        // 4. Üretim Planları (Planlama) - 07:30
        // Üretim genelde erken başlar.
        $schedule->command('production:check-upcoming')->dailyAt('07:30');

        $schedule->command('reports:send-scheduled')
            ->everyMinute()
            ->withoutOverlapping();
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }


}
