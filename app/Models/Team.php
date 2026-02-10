<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasBusinessUnit; // <--- 1. Use ekle

/**
 * App\Models\Team
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $created_by_user_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Team extends Model
{
    use HasFactory, SoftDeletes, Loggable, HasBusinessUnit; // <--- 2. Trait ekle

    protected $fillable = [
        'name',
        'description',
        'created_by_user_id',
        'is_active',
        'business_unit_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Takıma atanan görevler (Polymorphic)
     */
    public function assignments(): MorphMany
    {
        return $this->morphMany(VehicleAssignment::class, 'responsible');
    }

    /**
     * Takım üyeleri (Many-to-Many)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withTimestamps();
    }

    /**
     * Takımı oluşturan kullanıcı
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Accessor: Üye sayısı
     */
    public function getMembersCountAttribute(): int
    {
        return $this->users()->count();
    }

    /**
     * Scope: Sadece aktif takımlar
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Kullanıcının oluşturduğu takımlar
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by_user_id', $userId);
    }

    /**
     * Scope: Kullanıcının üye olduğu takımlar
     */
    public function scopeWhereUserIsMember($query, $userId)
    {
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    /**
     * Check: Kullanıcı bu takımın üyesi mi?
     */
    public function hasMember($userId): bool
    {
        return $this->users()->where('users.id', $userId)->exists();
    }

    /**
     * Üye ekle
     */
    public function addMember($userId): void
    {
        if (!$this->hasMember($userId)) {
            $this->users()->attach($userId);
        }
    }

    /**
     * Üye çıkar
     */
    public function removeMember($userId): void
    {
        $this->users()->detach($userId);
    }

    /**
     * Tüm üyeleri çıkar
     */
    public function removeAllMembers(): void
    {
        $this->users()->detach();
    }
}