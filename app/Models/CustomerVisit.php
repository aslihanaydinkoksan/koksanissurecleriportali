<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

/**
 * App\Models\CustomerVisit
 *
 * @property int $id
 * @property int $event_id
 * @property int $customer_id
 * @property int|null $travel_id
 * @property int|null $customer_machine_id
 * @property string $visit_purpose
 * @property string|null $after_sales_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\Travel|null $travel
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereAfterSalesNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereCustomerMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereVisitPurpose($value)
 * @mixin \Eloquent
 * @mixin IdeHelperCustomerVisit
 */
class CustomerVisit extends Model
{
    use HasFactory, Loggable;
    protected $fillable = [
        'event_id',
        'customer_id',
        'travel_id',
        'visit_purpose',
        'has_machine',
        'after_sales_notes'
    ];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }
}
