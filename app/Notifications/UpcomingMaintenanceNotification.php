<?php

namespace App\Notifications;

use App\Models\MaintenancePlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingMaintenanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $plan;

    public function __construct(MaintenancePlan $plan)
    {
        $this->plan = $plan;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $assetName = $this->plan->asset ? $this->plan->asset->name : 'Tanımsız Varlık';

        return (new MailMessage)
            ->level('warning') // Sarı uyarı rengi verir
            ->subject('⚠️ Bakım Yaklaşıyor: ' . $assetName)
            ->greeting('Sayın Yetkili,')
            ->line('Aşağıdaki varlık için planlanmış bakım zamanı yaklaşmaktadır.')
            ->line('🔧 Varlık: ' . $assetName)
            ->line('📅 Planlanan Tarih: ' . $this->plan->planned_start_date->format('d.m.Y H:i'))
            ->line('📋 Bakım Türü: ' . ($this->plan->type->name ?? 'Genel Bakım'))
            ->action('Planı İncele', url('/maintenance-plans/' . $this->plan->id))
            ->line('Lütfen gerekli hazırlıkları yapınız.');
    }

    public function toArray($notifiable)
    {
        return [
            'action' => 'maintenance_upcoming',
            'plan_id' => $this->plan->id,
            'asset' => $this->plan->asset->name ?? 'N/A',
            'date' => $this->plan->planned_start_date->toDateTimeString(),
            'message' => 'Bakım zamanı yaklaşıyor: ' . ($this->plan->asset->name ?? ''),
        ];
    }
}