<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

/**
 * App\Models\CustomerActivityLog
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $model_type
 * @property int $model_id
 * @property string $action
 * @property array|null $changes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperCustomerActivityLog
 */
class CustomerActivityLog extends Model
{
    use HasFactory, Loggable;
    protected $fillable = ['user_id', 'model_type', 'model_id', 'action', 'changes'];
    protected $casts = ['changes' => 'array'];
}
