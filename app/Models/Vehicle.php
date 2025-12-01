<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany; // [YENİ] HasMany yerine MorphMany
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

/**
 * * Bu model  SADECE "Şirket Araçlarını" (Company Vehicles) temsil eder.
 * Nakliye araçları için "LogisticsVehicle" modeli kullanılır.
 *
 * @property int $id
 * @property string $plate_number
 * @property string $type (sedan, hatchback, suv) -> Artık 'logistics' buraya gelmeyecek.
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
        'type',        // Bunu tutabiliriz ama artık 'logistics' değeri almayacak. (Örn: Sedan, SUV)
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
     * Bu araca ait görevler (Polymorphic İlişki)
     * VehicleAssignment tablosundaki 'vehicle_type' ve 'vehicle_id' ile eşleşir.
     */
    public function assignments(): MorphMany
    {
        return $this->morphMany(VehicleAssignment::class, 'vehicle');
    }

    /**
     * Scope: Sadece aktif araçlar
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor: Tam araç bilgisi
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->plate_number} - {$this->brand_model}";
    }

    /**
     * Check: Müsait mi?
     * Araç aktifse ve şu anda devam eden bir görevi yoksa.
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
        // Polymorphic ilişkide de sorgu mantığı aynıdır
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
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}