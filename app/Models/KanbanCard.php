<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanCard extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Kart hangi sütuna ait?
    public function column()
    {
        return $this->belongsTo(KanbanColumn::class);
    }

    // Polimorfik İlişki: Bu kart aslında neyi taşıyor? (Sevkiyat mı, Bakım Planı mı?)
    public function model()
    {
        return $this->morphTo();
    }
}
