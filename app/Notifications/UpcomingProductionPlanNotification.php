<?php

namespace App\Notifications;

use App\Models\ProductionPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingProductionPlanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $plan;

    public function __construct(ProductionPlan $plan)
    {
        $this->plan = $plan;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->level('success') // Yeşil renk (Üretim/Operasyon)
            ->subject('🏭 Yeni Üretim Haftası Başlıyor: ' . $this->plan->plan_title)
            ->greeting('Sayın ' . $notifiable->name . ',')
            ->line('Yarın yeni bir üretim planı dönemi başlamaktadır.')
            ->line('📌 Plan Adı: ' . $this->plan->plan_title)
            ->line('📅 Hafta Başlangıcı: ' . $this->plan->week_start_date->format('d.m.Y'))
            ->line('Önem Durumu: ' . ($this->plan->is_important ? '⚠️ Yüksek Öncelikli' : 'Normal'))
            ->action('Plan Detaylarını İncele', url('/production-plans/' . $this->plan->id))
            ->line('İyi çalışmalar ve verimli bir hafta dileriz.');
    }

    public function toArray($notifiable)
    {
        return [
            'action' => 'production_plan_starting',
            'plan_id' => $this->plan->id,
            'title' => $this->plan->plan_title,
            'week_date' => $this->plan->week_start_date->toDateString(),
            'message' => 'Üretim planı başlıyor: ' . $this->plan->plan_title,
        ];
    }
}