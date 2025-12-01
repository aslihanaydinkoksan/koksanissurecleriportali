<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

/**
 * App\Models\VehicleAssignment
 *
 * @property int $id
 * @property string $assignment_type (company_vehicle, logistics, personal)
 * @property string $responsible_type (user, team)
 * @property int $responsible_id
 * @property int|null $vehicle_id Araç gerekiyorsa
 * @property string $task_description
 * @property string|null $destination
 * //@property decimal|null $start_km Sadece logistics için
 * @property string|null $start_fuel_level Sadece logistics için
 * //@property decimal|null $end_km Sadece logistics için - iş bitince
 * @property string|null $end_fuel_level Sadece logistics için - iş bitince
 * //@property decimal|null $fuel_cost Sadece logistics için - iş bitince
 * @property string|null $notes
 * @property string $status (pending, in_progress, completed, cancelled)
 * @property int $user_id Görevi oluşturan kişi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class VehicleAssignment extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        'assignment_type',
        'responsible_type',
        'responsible_id',
        'vehicle_type',
        'vehicle_id',
        'task_description',
        'destination',
        'start_km',
        'start_fuel_level',
        'end_km',
        'end_fuel_level',
        'fuel_cost',
        'notes',
        'status',
        'start_time',
        'end_time',
        'is_important',
        'created_by_user_id',
        'customer_id',
    ];

    protected $casts = [
        'start_km' => 'float',
        'end_km' => 'float',
        'fuel_cost' => 'float',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_important' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Görevin sorumlusu (Polymorphic)
     * User veya Team olabilir
     */
    public function responsible(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Kullanılan araç (eğer araç gerekliyse)
     */
    public function vehicle(): MorphTo
    {
        return $this->MorphTo();
    }


    /**
     * Görevi oluşturan kullanıcı
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }

    /**
     * Scope: Sadece benim görevlerim
     * (Bana atanan veya takımımın görevleri)
     */
    public function scopeMyAssignments($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            // Direkt bana atanan görevler
            $q->where(function ($subQ) use ($userId) {
                $subQ->where('responsible_type', User::class)
                    ->where('responsible_id', $userId);
            })
                // Veya takımıma atanan görevler
                ->orWhere(function ($subQ) use ($userId) {
                    $subQ->where('responsible_type', Team::class)
                        ->whereHas('responsible.users', function ($teamQ) use ($userId) {
                            $teamQ->where('users.id', $userId);
                        });
                });
        });
    }

    /**
     * Scope: Araç gerektiren görevler
     */
    public function scopeRequiresVehicle($query)
    {
        return $query->whereIn('assignment_type', ['company_vehicle', 'logistics']);
    }

    /**
     * Scope: Nakliye görevleri
     */
    public function scopeLogistics($query)
    {
        return $query->where('assignment_type', 'logistics');
    }

    /**
     * Accessor: Görev tipi ismini döndür
     */
    public function getAssignmentTypeNameAttribute(): string
    {
        return match ($this->assignment_type) {
            'company_vehicle' => 'Şirket Aracı',
            'logistics' => 'Nakliye',
            'personal' => 'Kişisel Görev',
            default => 'Bilinmeyen'
        };
    }

    /**
     * Accessor: Durum ismini döndür
     */
    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Bekliyor',
            'in_progress' => 'Devam Ediyor',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal Edildi',
            default => 'Bilinmeyen'
        };
    }

    /**
     * Accessor: Durum badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Check: Nakliye görevi mi?
     */
    public function isLogistics(): bool
    {
        return $this->vehicle_type === \App\Models\LogisticsVehicle::class
            || $this->assignment_type === 'logistics';

    }

    /**
     * Check: Araç gerektiriyor mu?
     */
    public function requiresVehicle(): bool
    {
        return in_array($this->assignment_type, ['company_vehicle', 'logistics']);
    }

    /**
     * Check: Tamamlanabilir mi? (end_km, fuel_cost girilmeli)
     */
    public function canBeCompleted(): bool
    {
        if ($this->isLogistics()) {
            return !is_null($this->end_km) && !is_null($this->fuel_cost);
        }
        return true; // Diğer görevler direkt tamamlanabilir
    }
    // Yeni İlişki: Bu görev hangi müşteriye gidiyor?
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}