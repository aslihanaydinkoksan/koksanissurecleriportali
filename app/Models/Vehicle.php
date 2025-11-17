<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

/**
 * App\Models\Vehicle
 *
 * @property int $id
 * @property string $plate_number
 * @property string $type (company, logistics)
 * @property string|null $brand_model
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Vehicle extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        'plate_number',
        'type',
        'brand_model',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Bu araca ait görevler
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class);
    }

    /**
     * Scope: Sadece aktif araçlar
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Şirket araçları
     */
    public function scopeCompany($query)
    {
        return $query->where('type', 'company');
    }

    /**
     * Scope: Nakliye araçları
     */
    public function scopeLogistics($query)
    {
        return $query->where('type', 'logistics');
    }

    /**
     * Accessor: Araç tipi ismi
     */
    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'company' => 'Şirket Aracı',
            'logistics' => 'Nakliye Aracı',
            default => 'Bilinmeyen'
        };
    }

    /**
     * Accessor: Tam araç bilgisi
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->plate_number} - {$this->brand_model}";
    }

    /**
     * Check: Müsait mi? (Şu anda kullanımda değil)
     */
    public function isAvailable(): bool
    {
        return $this->is_active && !$this->hasActiveAssignment();
    }

    /**
     * Check: Şu anda aktif bir görevi var mı?
     */
    public function hasActiveAssignment(): bool
    {
        return $this->assignments()
            ->whereIn('status', ['pending', 'in_progress'])
            ->exists();
    }

    /**
     * Son görevi getir
     */
    public function lastAssignment()
    {
        return $this->assignments()->latest()->first();
    }
}