<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

/**
 * App\Models\VehicleAssignment
 *
 * @property int $id
 * @property int $vehicle_id
 * @property int $user_id
 * @property bool $is_important
 * @property string $task_description
 * @property string|null $destination
 * @property string|null $requester_name
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Vehicle $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereRequesterName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereTaskDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereVehicleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperVehicleAssignment
 */
class VehicleAssignment extends Model
{
    use HasFactory, SoftDeletes, Loggable;
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'task_description',
        'destination',
        'requester_name',
        'start_time',
        'end_time',
        'notes',
        'is_important',
    ];

    // Tarih alanlarını Carbon nesnesi yap
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_important' => 'boolean',
    ];

    // Bu atamanın hangi araca ait olduğunu belirtir
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Bu atamayı hangi kullanıcının yaptığını belirtir
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
