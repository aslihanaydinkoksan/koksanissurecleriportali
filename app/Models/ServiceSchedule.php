<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

/**
 * App\Models\ServiceSchedule
 *
 * @property int $id
 * @property string $name
 * @property string $departure_time
 * @property int $cutoff_minutes
 * @property int|null $default_vehicle_id
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Vehicle|null $defaultVehicle
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereCutoffMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereDefaultVehicleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereDepartureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperServiceSchedule
 */
class ServiceSchedule extends Model
{
    use HasFactory, SoftDeletes, Loggable;
    protected $fillable = [
        'name',
        'departure_time',
        'cutoff_minutes',
        'default_vehicle_id',
        'is_active',
        'is_important',
    ];

    // Tarih/saat alanlarını otomatik Carbon'a çevirme
    protected $casts = [
        // 'departure_time' => 'datetime:H:i', // Sadece time olduğu için Carbon'a gerek yok
        'is_important' => 'boolean',
    ];

    /**
     * Bu sefere atanan varsayılan aracı getirir.
     */
    public function defaultVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'default_vehicle_id');
    }
}
