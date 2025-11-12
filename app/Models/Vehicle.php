<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

/**
 * App\Models\Vehicle
 *
 * @property int $id
 * @property string $plate_number
 * @property string $type
 * @property string|null $brand_model
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VehicleAssignment> $assignments
 * @property-read int|null $assignments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereBrandModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle wherePlateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperVehicle
 */
class Vehicle extends Model
{
    use HasFactory, SoftDeletes, Loggable;
    protected $fillable = [
        'plate_number',
        'type',
        'brand_model',
        'description',
        'is_active',
    ];

    /**
     * Bu araca ait tüm atamaları (görevleri) getirir.
     */
    public function assignments(): HasMany // YENİ EKLENDİ
    {
        return $this->hasMany(VehicleAssignment::class);
    }
}
