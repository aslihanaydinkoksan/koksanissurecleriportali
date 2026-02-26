<?php

namespace App\Observers;

use App\Models\VehicleAssignment;
use Illuminate\Support\Facades\Auth;

class VehicleAssignmentObserver
{
    public function created(VehicleAssignment $assignment): void
    {
        $assignment->histories()->create([
            'user_id' => Auth::id(),
            'old_status' => null,
            'new_status' => $assignment->status,
            'note' => 'Görev sisteme eklendi ve planlandı.'
        ]);
    }

    public function updated(VehicleAssignment $assignment): void
    {
        // Sadece 'status' (durum) sütunu değiştiyse tarihçeye yaz!
        if ($assignment->wasChanged('status')) {
            $assignment->histories()->create([
                'user_id' => Auth::id(),
                'old_status' => $assignment->getOriginal('status'),
                'new_status' => $assignment->status,
                'note' => 'Görev durumu güncellendi.'
            ]);
        }
    }
}
