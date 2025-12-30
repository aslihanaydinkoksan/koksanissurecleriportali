<?php

namespace App\Notifications;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingShipmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $shipment;

    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Hem Mail at, Hem DB'ye kaydet
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->level('info') // Mavi bilgilendirme rengi
            ->subject('🚛 Yarınki Sevkiyat Hatırlatması: ' . $this->shipment->plaka)
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line('Yarın çıkışı planlanan bir sevkiyatınız var.')
            ->line('📅 Çıkış Tarihi: ' . $this->shipment->cikis_tarihi->format('d.m.Y H:i'))
            ->line('🚛 Araç: ' . $this->shipment->plaka . ' (' . ($this->shipment->arac_tipi ?? 'Belirtilmedi') . ')')
            ->line('👤 Şoför: ' . ($this->shipment->sofor_adi ?? 'Belirtilmedi'))
            ->line('📍 Rota: ' . $this->shipment->kalkis_noktasi . ' ➡️ ' . $this->shipment->varis_noktasi)
            ->line('📦 Yük: ' . $this->shipment->kargo_icerigi . ' - ' . $this->shipment->kargo_miktari)
            ->action('Detayları Gör', url('/shipments/' . $this->shipment->id))
            ->line('İyi çalışmalar dileriz.');
    }

    public function toArray($notifiable)
    {
        return [
            'action' => 'shipment_upcoming',
            'shipment_id' => $this->shipment->id,
            'plaka' => $this->shipment->plaka,
            'departure_date' => $this->shipment->cikis_tarihi->toDateTimeString(),
            'message' => 'Yarın planlanan sevkiyat: ' . $this->shipment->plaka,
        ];
    }
}