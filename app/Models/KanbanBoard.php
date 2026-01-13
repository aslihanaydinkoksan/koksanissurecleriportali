<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanBoard extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function columns()
    {
        return $this->hasMany(KanbanColumn::class, 'board_id')->orderBy('order_index');
    }
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }
}
