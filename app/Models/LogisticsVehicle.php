<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany; // Polymorphic ilişki için gerekli
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable; // Diğer modellerinde olduğu gibi loglama
use App\Traits\HasBusinessUnit; // <--- 1. Use ekle

/**
 * App\Models\LogisticsVehicle
 * * Nakliye / Lojistik araçlarını temsil eder.
 * Özellikleri: KM takibi, Yük kapasitesi, Yakıt tipi vb.
 *
 * @property int $id
 * @property string $plate_number
 * @property string $brand
 * @property string $model
 * @property float|null $capacity (Ton/Kg cinsinden)
 * @property float $current_km (Sayaçtaki güncel KM)
 * @property string|null $fuel_type (Dizel, Benzin vb.)
 * @property string $status (active, maintenance, out_of_service)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class LogisticsVehicle extends Model
{
    use HasFactory, SoftDeletes, Loggable, HasBusinessUnit; // <--- 2. Trait ekle
    public static $globalPermission = 'manage_fleet';

    protected $fillable = [
        'plate_number',
        'brand',
        'model',
        'capacity',
        'current_km',
        'fuel_type',
        'status', // active, maintenance, inactive
    ];

    protected $casts = [
        'capacity' => 'float',
        'current_km' => 'float',
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
        return $query->where('status', 'active');
    }

    /**
     * Accessor: Tam araç bilgisi (Plaka - Marka Model)
     * Dropdownlarda göstermek için kullanışlıdır.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->plate_number} - {$this->brand} {$this->model}";
    }

    /**
     * Accessor: Kapasite bilgisi formatlı
     */
    public function getFormattedCapacityAttribute(): string
    {
        return $this->capacity ? number_format($this->capacity, 0) . ' kg' : '-';
    }

    /**
     * Helper: KM Güncelle
     * Görev tamamlandığında aracın kilometresini günceller.
     */
    public function updateKm(float $newKm): void
    {
        // Yeni KM eskisinden düşük olamaz (Validation mantığı)
        if ($newKm > $this->current_km) {
            $this->update(['current_km' => $newKm]);
        }
    }

    /**
     * Check: Müsait mi?
     * Araç aktifse ve şu anda devam eden (bitmemiş) bir görevi yoksa.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active' && !$this->hasActiveAssignment();
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
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}