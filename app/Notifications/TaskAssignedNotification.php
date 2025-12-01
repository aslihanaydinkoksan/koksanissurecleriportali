<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\VehicleAssignment;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $assignment;

    public function __construct(VehicleAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function via($notifiable)
    {
        return ['database']; // Navbar için veritabanı
    }

    public function toArray($notifiable)
    {
        // Görevi atayan kişinin adı
        $assigner = $this->assignment->createdBy ? $this->assignment->createdBy->name : 'Yönetici';

        return [
            'assignment_id' => $this->assignment->id,

            // Navbar alanları
            'title' => 'Yeni Görev Atandı',
            'message' => "{$assigner} size yeni bir görev atadı: {$this->assignment->title}",
            'icon' => 'fa-clipboard-check', // Görev ikonu
            'color' => 'success',            // Yeşil renk (text-success)

            // Tıklayınca gideceği link (Görevlerim sayfası)
            'link' => route('service.general-tasks.index'),
        ];
    }
}