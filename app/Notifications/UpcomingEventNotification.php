<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingEventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        // Başlık belirleme (Ziyaret mi, Toplantı mı?)
        $typeLabel = $this->event->event_type === 'customer_visit' ? 'Müşteri Ziyareti' : 'Etkinlik/Toplantı';
        $customerName = $this->event->customerVisit ? $this->event->customerVisit->customer->name : null;

        $mail = (new MailMessage)
            ->level('info') // Mavi bilgilendirme
            ->subject('📅 Yarınki Programınız: ' . $this->event->title)
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line('Yarın için planlanmış bir ' . mb_strtolower($typeLabel) . ' kaydınız var.')
            ->line('📝 Başlık: ' . $this->event->title)
            ->line('⏰ Başlangıç: ' . $this->event->start_datetime->format('d.m.Y H:i'));

        // Eğer bir müşteri ziyareti ise detay ekle
        if ($customerName) {
            $mail->line('🏢 Müşteri: ' . $customerName)
                ->line('🎯 Ziyaret Amacı: ' . ($this->event->customerVisit->visit_purpose ?? '-'));
        }

        // Lokasyon varsa ekle
        if ($this->event->location) {
            $mail->line('📍 Konum: ' . $this->event->location);
        }

        return $mail->action('Takvimi Görüntüle', url('/calendar'))
            ->line('Toplantı/Ziyaret öncesi hazırlıklarınızı yapmayı unutmayınız.');
    }

    public function toArray($notifiable)
    {
        return [
            'action' => 'event_upcoming',
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'start_date' => $this->event->start_datetime->toDateTimeString(),
            'message' => 'Yarınki etkinlik: ' . $this->event->title,
        ];
    }
}