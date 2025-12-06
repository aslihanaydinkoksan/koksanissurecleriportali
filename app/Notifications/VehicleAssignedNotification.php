<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VehicleAssignment;

// Kuyruk (Queue) ayarların yapılandırılmadıysa "implements ShouldQueue" kısmını kaldırabilirsin.
// Şimdilik senkron çalışması için sadece "extends Notification" diyoruz.
class VehicleAssignedNotification extends Notification
{
    use Queueable;

    public $assignment;

    /**
     * Bildirim sınıfı başlatılırken assignment modelini al.
     */
    public function __construct(VehicleAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

    /**
     * Hangi kanallardan gönderilecek?
     * Polling sistemi kullandığın için sadece 'database' yeterli.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Veritabanına (notifications tablosuna) kaydedilecek veriler.
     * Buradaki 'message' anahtarı, JS tarafında Toast uyarısı olarak gösterilecek.
     */
    public function toArray($notifiable)
    {
        // Araç plakası kontrolü (Silinmiş araç vs. ihtimaline karşı)
        $plate = $this->assignment->vehicle ? $this->assignment->vehicle->plate_number : 'Bilinmeyen Araç';

        // Araç tipine göre ikon belirleme (Görsel zenginlik için)
        $isLogistics = str_contains($this->assignment->vehicle_type, 'Logistics');
        $iconType = $isLogistics ? '🚚' : '🚗';

        return [
            'assignment_id' => $this->assignment->id,
            'title' => 'Araç Ataması Yapıldı',
            // Toast mesajında çıkacak metin BURASI:
            'message' => "{$iconType} Görevinize {$plate} plakalı araç atanmıştır.",

            // Tıklayınca gidilecek link (Genelde düzenleme sayfasına gider)
            'link' => route('service.assignments.index', $this->assignment->id),

            // Ekstra bilgiler
            'type' => 'vehicle_assignment',
            'icon' => 'fas fa-car-side'
        ];
    }
}