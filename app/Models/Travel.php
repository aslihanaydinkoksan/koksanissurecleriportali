<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Travel
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $status
 * @property int $is_important
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerVisit> $customerVisits
 * @property-read int|null $customer_visits_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperTravel
 */
class Travel extends Model
{
    use HasFactory, Loggable;

    protected $table = 'travels';
    protected $fillable = [
        'user_id',
        'name',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'status',
        'is_important',
    ];
    /**
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function user(): BelongsTo // İlişki tipini belirt
    {
        return $this->belongsTo(User::class);
    }
    public function customerVisits()
    {
        return $this->hasMany(CustomerVisit::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class)->orderBy('start_datetime', 'asc');
    }
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
