<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

/**
 * App\Models\Birim
 *
 * @property int $id
 * @property string $ad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Birim newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim query()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereAd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperBirim
 */
class Birim extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    /**
     * Toplu atama (mass assignment) ile doldurulmasına izin verilen alanlar.
     *
     * @var array
     */
    protected $fillable = [
        'ad',
    ];
}
