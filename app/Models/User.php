<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Loggable, HasRoles;

    /**
     * Toplu atama yapılabilecek alanlar.
     * Artık 'role' ve 'department_id' burada yok!
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // --- İLİŞKİLER (RELATIONS) ---

    /**
     * Kullanıcının dahil olduğu departmanlar (Çoklu).
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_user');
    }

    /**
     * Kullanıcının yetkili olduğu iş birimleri (Fabrikalar).
     */
    public function businessUnits(): BelongsToMany
    {
        return $this->belongsToMany(BusinessUnit::class, 'business_unit_user')
            ->withPivot('role_in_unit')
            ->withTimestamps();
    }

    public function travels(): HasMany
    {
        return $this->hasMany(Travel::class);
    }

    public function assignments(): MorphMany
    {
        return $this->morphMany(VehicleAssignment::class, 'responsible');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')->withTimestamps();
    }

    public function createdTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'created_by_user_id');
    }

    // --- YETKİ KONTROLLERİ (AUTHORIZATION) ---

    /**
     * Kullanıcı Admin mi? (Sadece Spatie Rolüne Bakar)
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Kullanıcı Yönetici mi?
     */
    public function isManager(): bool
    {
        return $this->hasAnyRole(['yonetici', 'manager', 'müdür']);
    }

    /**
     * KÖKSAN ÖZEL: Departman Bazlı Yetki Kontrolü (SLUG TABANLI)
     * Bu metod, veritabanı ID'lerinden bağımsız çalışır, daha güvenlidir.
     */
    public function hasDepartmentPermission(string $permission): bool
    {
        if ($this->isAdmin())
            return true;

        // Yetki - Departman Slug Eşleşmesi
        $permissionMap = [
            'view_logistics' => 'lojistik',
            'view_production' => 'uretim',
            'view_administrative' => 'idari-isler',
            'view_maintenance' => 'bakim',
        ];

        if (isset($permissionMap[$permission])) {
            $requiredSlug = $permissionMap[$permission];

            // Kullanıcının departmanları arasında bu slug var mı?
            return $this->departments()->where('slug', $requiredSlug)->exists();
        }

        // Eğer haritada yoksa, Spatie'nin standart izin kontrolünü yap
        return $this->can($permission);
    }

    /**
     * Kullanıcının bekleyen bireysel veya takım görevlerini sayar.
     */
    public function getPendingAssignmentsCountAttribute(): int
    {
        return VehicleAssignment::whereIn('status', ['pending', 'in_progress'])
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('responsible_type', self::class)
                        ->where('responsible_id', $this->id);
                })
                    ->orWhere(function ($q) {
                        $teamIds = $this->teams()->pluck('teams.id');
                        $q->where('responsible_type', Team::class)
                            ->whereIn('responsible_id', $teamIds);
                    });
            })
            ->count();
    }

    // --- YARDIMCI METODLAR ---

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Admin ise tüm birimleri, değilse sadece yetkili olduklarını döndürür.
     */
    public function getAuthorizedBusinessUnits()
    {
        if ($this->isAdmin() || $this->can('view_all_business_units')) {
            return BusinessUnit::where('is_active', true)->get();
        }
        return $this->businessUnits;
    }
}