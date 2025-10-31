<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'plate_number',
        'type',
        'brand_model',
        'description',
        'is_active',
    ];

    /**
     * Bu araca ait tüm atamaları (görevleri) getirir.
     */
    public function assignments(): HasMany // YENİ EKLENDİ
    {
        return $this->hasMany(VehicleAssignment::class);
    }
}
