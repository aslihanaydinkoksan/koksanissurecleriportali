<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    // KİLİT NOKTA BURASI:
    // Boş bir array vererek "Hiçbir sütunu koruma, hepsine veri girilebilir" diyoruz.
    protected $guarded = [];

    // İlişki: Bu abonelik kime ait?
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}