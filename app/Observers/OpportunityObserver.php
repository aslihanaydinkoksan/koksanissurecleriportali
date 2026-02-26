<?php

namespace App\Observers;

use App\Models\Opportunity;
use Illuminate\Support\Facades\Auth;

class OpportunityObserver
{
    /**
     * Fırsat ilk oluşturulduğunda çalışır.
     */
    public function created(Opportunity $opportunity): void
    {
        $opportunity->histories()->create([
            'user_id' => Auth::id(),
            'old_stage' => null,
            'new_stage' => $opportunity->stage,
            'note' => 'Fırsat sisteme eklendi.'
        ]);
    }

    /**
     * Fırsat güncellendiğinde çalışır.
     */
    public function updated(Opportunity $opportunity): void
    {
        // Sadece 'stage' (aşama) sütunu değiştiyse tarihçeye yaz!
        if ($opportunity->wasChanged('stage')) {
            $opportunity->histories()->create([
                'user_id' => Auth::id(),
                'old_stage' => $opportunity->getOriginal('stage'), // Değişmeden önceki hali
                'new_stage' => $opportunity->stage, // Yeni hali
                'note' => 'Aşama güncellendi.'
            ]);
        }
    }
}
