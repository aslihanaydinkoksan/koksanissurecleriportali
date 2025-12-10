<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Resident extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    // KİLİT NOKTA: Tüm sütunlara veri girişine izin veriyoruz
    protected $guarded = [];

    // İlişkiler: Bir kişinin konaklama geçmişi olabilir
    public function stays()
    {
        return $this->hasMany(Stay::class);
    }
}