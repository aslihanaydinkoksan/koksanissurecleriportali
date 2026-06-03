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
use App\Traits\HasBusinessUnit;
use App\Notifications\ResetPasswordNotification;
use App\Models\Department;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Loggable,HasBusinessUnit, HasRoles;

    /**
     * Toplu atama yapılabilecek alanlar.
     * Artık 'role' ve 'department_id' burada yok!
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'business_unit_id',
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
     * Kullanıcı Admin veya Superadmin mi?
     */
    public function isAdmin(): bool
    {
        // 1. Spatie Roller Kontrolü (Öncelikli)
        if ($this->hasAnyRole(['admin', 'superadmin', 'Admin', 'Superadmin'])) {
            return true;
        }

        // 2. Veritabanı Sütun Yedeği (Fallback)
        $legacyRole = strtolower($this->getRawOriginal('role') ?? '');
        return in_array($legacyRole, ['admin', 'superadmin']);
    }

    /**
     * Kullanıcı Sadece Superadmin mi? (Tam Yetki)
     */
    public function isSuperAdmin(): bool
    {
        // Spatie rölü veya eski sütun değerine göre kontrol
        return $this->hasRole('superadmin') || strtolower($this->getRawOriginal('role') ?? '') === 'superadmin';
    }

    /**
     * Kullanıcı Yönetici mi? (Departman veya Ünite Yöneticisi)
     */
    public function isManager(): bool
    {
        return $this->hasAnyRole(['yonetici', 'manager', 'müdür', 'Bakım Müdürü', 'bakim_mudur']);
    }
    // 1. '$user->department' çağrıldığında çalışır
    public function getDepartmentAttribute()
    {
        // Kullanıcının ilk departmanını sanki tekil bir özellikmiş gibi döndürür.
        return $this->departments->first();
    }

    // 2. '$user->role' çağrıldığında çalışır
    public function getRoleAttribute()
    {
        // Spatie'deki ilk rol ismini döndürür (admin, personel vb.)
        return $this->roles->first()?->name;
    }

    /**
     * KÖKSAN ÖZEL: Departman/Modül Bazlı Yetki Kontrolü
     * Sadece Spatie İzinlerine (can) ve Admin hiyerarşisine bakar.
     */
    public function hasDepartmentPermission(string $permission): bool
    {
        // 1. Spatie Rolleri/İzinleri Üzerinden Kontrol (Paneldeki TİKLER)
        if ($this->can($permission)) {
            return true;
        }
 
        // 2. Admin/Superadmin her zaman yetkilidir
        if ($this->isAdmin()) {
            return true;
        }
 
        return false;
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
    public function kanbanBoards()
    {
        return $this->hasMany(KanbanBoard::class);
    }
    public function isInDepartment(string $slug): bool
    {
        return $this->departments->contains('slug', $slug);
    }
    /**
     * Kullanıcının ana birim ID'sini pivot tablodaki ilk kayıtla senkronize eder.
     * Bu metod veri tutarlılığı için kritiktir.
     */
    public function syncPrimaryBusinessUnit(): void
    {
        $firstUnitId = $this->businessUnits()->first()?->id;
        if ($this->business_unit_id !== $firstUnitId) {
            $this->update(['business_unit_id' => $firstUnitId]);
        }
    }

}