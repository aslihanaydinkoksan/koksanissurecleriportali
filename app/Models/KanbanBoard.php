<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanBoard extends Model
{
    use HasFactory;
    protected $guarded = [];

    // İlişki: Panonun Sütunları (Sıralı gelmesi önemli)
    public function columns()
    {
        return $this->hasMany(KanbanColumn::class, 'board_id')->orderBy('order_index');
    }

    // İlişki: Panonun ait olduğu Fabrika/Birim
    // Not: Senin tablolarında 'business_units' var, model adının 'BusinessUnit' olduğunu varsayıyorum.
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }
}
