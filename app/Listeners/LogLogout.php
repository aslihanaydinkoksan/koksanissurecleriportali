<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogLogout
{
    public function handle(Logout $event): void
    {
        if ($event->user) {
            activity()
                ->causedBy($event->user)
                ->log('Kullanıcı sistemden çıkış yaptı');
        }
    }
}