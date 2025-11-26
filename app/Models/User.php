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

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $department_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Department|null $department
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Travel> $travels
 * @property-read int|null $travels_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Authorizable, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
                    // Not: $this->teams() ilişkisinin user modelinde tanımlı olması gerekir (belongsToMany)
                    $teamIds = $this->teams()->pluck('teams.id');

                    $q->where('responsible_type', \App\Models\Team::class) // Team::class
                        ->whereIn('responsible_id', $teamIds);
                });
            })
            ->count();

        return $count;
    }
    /**
     * Kullanıcı Admin mi?
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Kullanıcı Yönetici mi?
     */
    public function isManager(): bool
    {
        return $this->role === 'yönetici';
    }

}
