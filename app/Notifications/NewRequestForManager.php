<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\VehicleAssignment;

class NewRequestForManager extends Notification
{
    use Queueable;

    protected $assignment;

    public function __construct(VehicleAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function via($notifiable)
    {
        return ['database']; // Sadece veritabanına (Navbar için)
    }

    /**
     * NAVBAR İÇİN ÖZEL VERİ YAPISI
     */
    public function toArray($notifiable)
    {
        // İlişkileri kontrol et, hata almamak için varsayılan değer ata
        $requester = $this->assignment->createdBy ? $this->assignment->createdBy->name : 'Bir Kullanıcı';
        $desc = $this->assignment->task_description ?? 'Detay belirtilmedi';

        return [
            // Benzersiz ID (okundu işaretlemek için gerekebilir)
            'assignment_id' => $this->assignment->id,

            // Blade dosyasında (navbar) kullanılan alanlar:
            'title' => 'Yeni Araç Talebi',
            'message' => "{$requester}: {$desc}",
            'icon' => 'fa-car-side', // FontAwesome ikonu
            'color' => 'warning',     // Bootstrap rengi (text-warning)
            'link' => route('service.assignments.edit', $this->assignment->id),// Tıklayınca gideceği yer
        ];
    }
}