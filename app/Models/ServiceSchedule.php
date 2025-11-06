<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSchedule extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'departure_time',
        'cutoff_minutes',
        'default_vehicle_id',
        'is_active',
        'is_important',
    ];

    // Tarih/saat alanlarını otomatik Carbon'a çevirme
    protected $casts = [
        // 'departure_time' => 'datetime:H:i', // Sadece time olduğu için Carbon'a gerek yok
        'is_important' => 'boolean',
    ];

    /**
     * Bu sefere atanan varsayılan aracı getirir.
     */
    public function defaultVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'default_vehicle_id');
    }
}
