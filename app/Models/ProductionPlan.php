<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit; // <--- 1. Use ekle

/**
 * App\Models\ProductionPlan
 *
 * @property int $id
 * @property int $user_id
 * @property bool $is_important
 * @property string $plan_title
 * @property \Illuminate\Support\Carbon $week_start_date
 * @property array|null $plan_details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan wherePlanDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan wherePlanTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereWeekStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperProductionPlan
 */
class ProductionPlan extends Model
{
    use HasFactory, SoftDeletes, Loggable, HasBusinessUnit; // <--- 2. Trait ekle
    /**
     * Toplu atanabilir alanlar.
     */
    protected $fillable = [
        'user_id',
        'plan_title',
        'week_start_date',
        'plan_details',
        'is_important',
        'business_unit_id',
    ];

    /**
     * JSON alanını otomatik olarak array'e dönüştürmek için.
     */
    protected $casts = [
        'week_start_date' => 'date',
        'plan_details' => 'array',
        'is_important' => 'boolean',
    ];

    /**
     * Planı oluşturan kullanıcı.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
