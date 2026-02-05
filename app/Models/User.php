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
use App\Models\Department;

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
     * KÖKSAN ÖZEL: Departman Bazlı Yetki Kontrolü
     * Veritabanındaki 'kanban_scope' sütunu ile kod tarafındaki yetkiyi eşleştirir.
     */
    public function hasDepartmentPermission(string $permission): bool
    {
        // 1. Admin her zaman yetkilidir
        if ($this->isAdmin()) {
            return true;
        }

        // 2. Yetki isminden 'view_' önekini kaldır ve temizle
        $normalizedPermission = trim($permission);
        $scope = str_replace('view_', '', $normalizedPermission);

        // 3. İsim Haritalaması (Mapping)
        // Kod tarafındaki 'view_administrative' yetkisini, veritabanındaki 'idari' scope'una çevirir.
        // İleride 'view_logistics' -> 'lojistik_depo' gibi özel eşleşmeler gerekirse buraya eklenir.
        $scopeMap = [
            'administrative' => 'idari',
            // 'bakim' => 'maintenance', // Örnek: Gerekirse eklenebilir
        ];

        // Eğer haritada özel bir karşılığı varsa onu kullan, yoksa olduğu gibi arat
        if (array_key_exists($scope, $scopeMap)) {
            $scope = $scopeMap[$scope];
        }

        // 4. Veritabanı Kontrolü
        // Kullanıcının departmanları arasında bu 'scope' değerine sahip biri var mı?
        return $this->departments()
            ->where('kanban_scope', $scope)
            ->exists();
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