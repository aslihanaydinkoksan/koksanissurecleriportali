<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\Loggable;

/**
 * App\Models\Booking
 *
 * @property int $id
 * @property int $travel_id
 * @property int $user_id
 * @property string $type
 * @property string|null $provider_name
 * @property string|null $confirmation_code
 * @property string|null $cost
 * @property string|null $start_datetime
 * @property string|null $end_datetime
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Travel $travel
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereEndDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereStartDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperBooking
 */
class Booking extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Loggable;
    protected $fillable = [
        'travel_id',
        'user_id',
        'type',
        'provider_name',
        'confirmation_code',
        'cost',
        'start_datetime',
        'end_datetime',
        'notes',
    ];

    // Bu rezervasyonun ait olduğu seyahat
    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    // Bu rezervasyonu ekleyen kullanıcı
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
