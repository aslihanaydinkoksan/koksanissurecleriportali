<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Travel;

class UpcomingTravelReminder extends Notification
{
    use Queueable;

    protected $travel;

    public function __construct(Travel $travel)
    {
        $this->travel = $travel;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'travel_id' => $this->travel->id,
            'title' => '🔔 Yaklaşan Seyahat Hatırlatması',
            'message' => "DİKKAT: Yarın '{$this->travel->name}' seyahati başlıyor. Araç planlamasını kontrol ettiniz mi?",
            'icon' => 'fa-clock',
            'color' => 'warning', // Sarı renk dikkat çeker
            'link' => route('travels.show', $this->travel->id),
        ];
    }
}