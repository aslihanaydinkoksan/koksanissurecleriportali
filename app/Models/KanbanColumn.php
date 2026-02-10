<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanColumn extends Model
{
    use HasFactory;
    protected $guarded = [];

    // İlişki: Sütunun ait olduğu Pano
    public function board()
    {
        return $this->belongsTo(KanbanBoard::class);
    }

    // İlişki: Sütundaki Kartlar (İşler)
    // Sürükle bırak için 'sort_order'a göre sıralı gelmesi çok kritik.
    public function cards()
    {
        return $this->hasMany(KanbanCard::class, 'column_id')->orderBy('sort_order');
    }
}
