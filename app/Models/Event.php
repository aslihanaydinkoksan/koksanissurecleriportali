<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit; // <--- 1. Use ekle

/**
 * App\Models\Event
 *
 * @property int $id
 * @property int $user_id
 * @property bool $is_important
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $start_datetime
 * @property \Illuminate\Support\Carbon $end_datetime
 * @property string|null $location
 * @property string $event_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\CustomerVisit|null $customerVisit
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEndDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperEvent
 */
class Event extends Model
{
    use HasFactory, SoftDeletes, Loggable, HasBusinessUnit; // <--- 2. Trait ekle
    /**
     * Toplu atanabilir alanlar.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'event_type',
        'is_important',
        'customer_id',
        'customer_machine_id',
        'visit_status',
        'cancellation_reason',
        'visit_purpose',
        'after_sales_notes',
        'business_unit_id',
    ];

    /**
     * Tarih alanlarını otomatik olarak Carbon nesnesine dönüştürür.
     * Bu, takvimde ve formlarda gösterim için çok önemlidir.
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_important' => 'boolean',
    ];

    /**
     * Etkinliği oluşturan kullanıcı.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
    public function customerVisit()
    {
        return $this->hasOne(CustomerVisit::class);
    }
    public function machine()
    {
        return $this->belongsTo(\App\Models\CustomerMachine::class, 'customer_machine_id');
    }
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
    // Yeni temiz ilişki
    public function type()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }
}
