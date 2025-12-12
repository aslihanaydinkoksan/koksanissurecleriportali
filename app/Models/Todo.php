<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasBusinessUnit; // <--- SİHİRLİ DEĞNEK

class Todo extends Model
{
    use HasFactory, SoftDeletes, HasBusinessUnit;

    protected $fillable = [
        'user_id',
        'business_unit_id',
        'title',
        'description',
        'due_date',
        'is_completed',
        'priority'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_completed' => 'boolean',
    ];

    // İlişki: Görevi oluşturan kişi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}