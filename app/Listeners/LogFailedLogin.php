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

        activity()
            ->causedBy($event->user)
            ->withProperty('email_denemesi', $email)
            ->withProperty('ip_adresi', $ip)
            ->log('BAŞARISIZ kullanıcı girişi denemesi');
    }
}
