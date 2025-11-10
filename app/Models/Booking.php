<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Booking extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'travel_id',
        'user_id',
        'type',
        'provider_name',
        'confirmation_code',
        'cost',
        'start_datetime',
        'end_datetime',
        'notes',
    ];

    // Bu rezervasyonun ait olduğu seyahat
    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    // Bu rezervasyonu ekleyen kullanıcı
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
