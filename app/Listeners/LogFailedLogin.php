<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogFailedLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'bilinmiyor';
        $ip = request()->ip();

        // Activity başlat
        $activity = activity()
            ->withProperty('email_denemesi', $email)
            ->withProperty('ip_adresi', $ip);

        // Eğer kullanıcı veritabanında varsa (sadece şifre yanlışsa) 'causedBy' ekle
        if ($event->user) {
            $activity->causedBy($event->user);
        }

        $activity->log('BAŞARISIZ kullanıcı girişi denemesi');
    }
}
