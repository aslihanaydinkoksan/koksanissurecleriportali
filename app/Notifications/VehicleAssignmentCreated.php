<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VehicleAssignment;

class VehicleAssignmentCreated extends Notification implements ShouldQueue
{
    use Queueable;
    public $assignment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(VehicleAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    /**
     * Bildirimin gönderileceği kanalları belirle.
     * Web arayüzünde görünmesi için 'database' kanalını kullanıyoruz.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    /**
     * Bildirimin veritabanı için dizi temsilini al.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'assignment_id' => $this->assignment->id,
            'title' => $this->assignment->title,
            'vehicle_name' => $this->assignment->vehicle ? $this->assignment->vehicle->name : 'Bilinmiyor',
            'starts_at' => $this->assignment->start_time->format('d.m.Y H:i'),
            'type' => $this->assignment->assignment_type,
            'message' => 'Size yeni bir araç görevi (' . $this->assignment->title . ') atanmıştır.',
        ];
    }
}
