<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Traits\Loggable;
use Illuminate\Support\Str;
use App\Models\BusinessUnit;
use Spatie\Permission\Traits\HasRoles; // ✅ BU KALMALI

class User extends Authenticatable
{
    // ✅ HasRoles trait'i buraya eklendi, artık roller buradan yönetiliyor
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Authorizable, Loggable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Eski string rol sütunu (yedek olarak durabilir)
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Kullanıcının ait olduğu birimi getirir.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function travels()
    {
        return $this->hasMany(Travel::class);
    }

    public function assignments()
    {
        return $this->morphMany(VehicleAssignment::class, 'responsible');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withTimestamps();
    }

    /**
     * Kullanıcının oluşturduğu takımlar
     */
    public function createdTeams()
    {
        return $this->hasMany(Team::class, 'created_by_user_id');
    }

    /**
     * Kullanıcının oluşturduğu görevler
     */
    public function createdAssignments()
    {
        return $this->hasMany(VehicleAssignment::class, 'created_by_user_id');
    }

    /**
     * Bana atanan tüm görevleri getir
     * (Direkt bana atananlar + takım görevleri)
     */
    public function allAssignments()
    {
        return VehicleAssignment::query()
            ->where(function ($query) {
                // Direkt bana atanan görevler
                $query->where('responsible_type', User::class)
                    ->where('responsible_id', $this->id);
            })
            ->orWhere(function ($query) {
                // Veya takımlarıma atanan görevler
                $query->where('responsible_type', Team::class)
                    ->whereHas('responsible.users', function ($q) {
                    $q->where('users.id', $this->id);
                });
            });
    }

    /**
     * Check: Bu takımın üyesi miyim?
     */
    public function isMemberOf($teamId): bool
    {
        return $this->teams()->where('teams.id', $teamId)->exists();
    }

    /**
     * Check: Bu takımı ben oluşturdum mu?
     */
    public function isCreatorOf($teamId): bool
    {
        return $this->createdTeams()->where('id', $teamId)->exists();
    }

    public function getPendingAssignmentsCountAttribute(): int
    {
        // Kullanıcının sorumlu olduğu bekleyen görevleri sayar
        $count = VehicleAssignment::whereIn('status', ['pending', 'in_progress'])
            ->where(function ($query) {
                // 1. Direkt bireysel atama
                $query->where(function ($q) {
                    $q->where('responsible_type', self::class) // User::class
                        ->where('responsible_id', $this->id);
                })
                    // 2. Takım ataması
                    ->orWhere(function ($q) {
                    // Kullanıcının takımlarını al
                    $teamIds = $this->teams()->pluck('teams.id');

                    $q->where('responsible_type', \App\Models\Team::class) // Team::class
                        ->whereIn('responsible_id', $teamIds);
                });
            })
            ->count();

        return $count;
    }

    /**
     * Kullanıcı Admin mi? (GÜNCELLENDİ: Spatie Rol Kontrolü)
     */
    public function isAdmin(): bool
    {
        // Hem eski 'role' sütununa bak (yedek) hem de yeni Spatie sistemine bak
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Kullanıcı Yönetici mi? (GÜNCELLENDİ)
     */
    public function isManager(): bool
    {
        return $this->role === 'yönetici' || $this->hasRole('yonetici');
    }


    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_user');
    }

    public function hasDepartment($departmentName)
    {
        // 1. Admin ise her yeri görsün
        if ($this->isAdmin()) {
            return true;
        }

        // 2. Önce İSİM (name) ile kontrol et (Mevcut kodun bozulmasın diye)
        if ($this->departments->contains('name', $departmentName)) {
            return true;
        }

        // 3. Bulamazsa bir de SLUG ile kontrol et
        $slug = Str::slug($departmentName);

        return $this->departments->contains('slug', $slug);
    }

    // YARDIMCI FONKSİYON: Kullanıcı belirli bir departmanda mı?
    public function inDepartment($deptSlug)
    {
        return $this->departments->contains('slug', $deptSlug);
    }

    /**
     * Kullanıcı Yönetici veya Müdür mü?
     */
    public function isManagerOrDirector(): bool
    {
        // Eski yöntem + Yeni yöntem
        if (in_array($this->role, ['yönetici', 'müdür']))
            return true;
        return $this->hasRole(['yonetici', 'mudur']);
    }

    // MÜŞTEREK ÇALIŞMA (Many to Many)
    // Bir kullanıcı birden fazla fabrikada yetkili olabilir.
    public function businessUnits()
    {
        return $this->belongsToMany(BusinessUnit::class, 'business_unit_user')
            ->withPivot('role_in_unit')

            ->withTimestamps();
    }
    // Admin ise tüm birimleri getir, değilse sadece yetkili olduklarını.
    public function getBusinessUnitsAttribute()
    {
        // Admin veya Global Yetkili ise TÜM aktif birimleri döndür
        if ($this->hasRole('admin') || $this->can('view_all_business_units')) {
            return \App\Models\BusinessUnit::where('is_active', true)->get();
        }

        // Değilse normal ilişkiyi döndür
        return $this->getRelationValue('businessUnits');
    }

    // Yardımcı Metod: Kullanıcının şu birime yetkisi var mı?
    public function hasAccessToUnit($unitId)
    {
        // Admin her yere girer
        if ($this->hasRole('admin')) { // 'admin' küçük harf kullandık seeder'da
            return true;
        }

        return $this->businessUnits->contains('id', $unitId);
    }
    /**
     * KÖKSAN AKILLI YETKİ KONTROLÜ
     * Kullanıcının departmanına göre Navbar menülerini filtreler.
     * Örn: Bakım yöneticisi ise permission 'view_logistics' olsa bile FALSE döner.
     */
    public function hasDepartmentPermission(string $permission): bool
    {
        // 1. ADIM: Temel Yetki Kontrolü (Spatie)
        // Rolünde bu yetki tanımlı değilse zaten göremez.
        if (!$this->can($permission)) {
            return false;
        }

        // 2. ADIM: Süper Yetkililer (Filtreye Takılmaz)
        // Admin her şeyi görür.
        if ($this->hasRole('admin')) {
            return true;
        }

        // 3. ADIM: "Departmansız" Yönetici Kontrolü (Genel Müdür vb.)
        // Eğer kullanıcının rolü "yönetici" ise VE department_id'si NULL ise -> Her yeri görsün.
        if ($this->hasRole('yönetici') && is_null($this->department_id)) {
            return true;
        }

        // 4. ADIM: Departman Eşleşmesi (En Önemli Kısım)
        // Eğer kullanıcının bir departmanı varsa, sadece o departmanla ilgili menüleri görsün.
        if ($this->department) {
            $userDeptName = mb_strtolower($this->department->name, 'UTF-8');

            // İzin Anahtarı -> Departman Kelimeleri Eşleşmesi
            // Bu liste sayesinde if-else yığınından kurtuluyoruz.
            $moduleMap = [
                'view_logistics' => ['lojistik', 'sevkiyat', 'depo', 'nakliye'],
                'view_production' => ['üretim', 'imalat', 'planlama', 'fabrika'],
                'view_maintenance' => ['bakım', 'teknik', 'tamir', 'onarım'],
                'view_administrative' => ['idari', 'hizmet', 'insan kaynakları', 'muhasebe', 'satın alma'],
            ];

            // Eğer sorgulanan izin (örn: view_logistics) haritamızda varsa
            if (array_key_exists($permission, $moduleMap)) {
                // Kullanıcının departman isminde, izin verilen kelimelerden biri geçiyor mu?
                foreach ($moduleMap[$permission] as $keyword) {
                    if (str_contains($userDeptName, $keyword)) {
                        return true; // Eşleşme var, menüyü göster.
                    }
                }
                return false; // Yetkisi var (Spatie'den gelmiş) ama departmanı uymuyor -> GİZLE.
            }
        }

        // Eğer haritada olmayan genel bir yetki ise (örn: view_users) ve 1. adımı geçtiyse göster.
        return true;
    }
}