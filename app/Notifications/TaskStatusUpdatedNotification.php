<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\VehicleAssignment;

class TaskStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $assignment;
    protected $oldStatus;

    public function __construct(VehicleAssignment $assignment, $oldStatus)
    {
        $this->assignment = $assignment;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Durumu Türkçeleştirip güzelleştirelim
        $statusLabels = [
            'pending' => 'Bekliyor',
            'in_progress' => 'Başlandı / Sürüyor',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal Edildi'
        ];

        $newStatusLabel = $statusLabels[$this->assignment->status] ?? $this->assignment->status;

        // İşlemi yapan kişinin adı
        $updaterName = auth()->user()->name;

        // İkon ve Renk Seçimi
        $icon = 'fa-info-circle';
        $color = 'primary';

        if ($this->assignment->status === 'completed') {
            $icon = 'fa-check-circle';
            $color = 'success';
        } elseif ($this->assignment->status === 'in_progress') {
            $icon = 'fa-spinner';
            $color = 'warning';
        } elseif ($this->assignment->status === 'cancelled') {
            $icon = 'fa-times-circle';
            $color = 'danger';
        }

        return [
            'assignment_id' => $this->assignment->id,
            'title' => 'Görev Durumu Güncellendi',
            'message' => "{$updaterName}, atadığınız görevi '{$newStatusLabel}' olarak güncelledi: {$this->assignment->title}",
            'icon' => $icon,
            'color' => $color,
            // Tıklayınca detay sayfasına gitsin
            'link' => route('service.assignments.show', $this->assignment->id),
        ];
    }
}