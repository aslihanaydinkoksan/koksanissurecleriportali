<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Travel;

class NewTravelPlanNotification extends Notification
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
        // Oluşturan kişinin adı
        $creatorName = $this->travel->user ? $this->travel->user->name : 'Biri';

        return [
            'travel_id' => $this->travel->id,

            // Navbar'da görünecekler
            'title' => 'Yeni Seyahat Planlandı',
            'message' => "{$creatorName} yeni bir seyahat oluşturdu: {$this->travel->name}. Lütfen araç planlamasını kontrol ediniz.",
            'icon' => 'fa-plane-departure', // Uçak/Seyahat ikonu
            'color' => 'info', // Mavi renk

            // Tıklayınca seyahat detayına gitsin ki müdür detayları görüp araç atayabilsin
            'link' => route('travels.show', $this->travel->id),
        ];
    }
}