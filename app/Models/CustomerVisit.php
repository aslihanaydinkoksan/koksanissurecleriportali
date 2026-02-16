<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
class CustomerVisit extends Model implements HasMedia
{
    use HasFactory, Loggable, HasBusinessUnit, InteractsWithMedia;
    protected $fillable = [
        'customer_id', 'user_id', 'event_id', 'travel_id',
        'visit_date', 
        'visit_reason', 'visit_notes', 'contact_persons',
        'customer_product_id', 'barcode', 'lot_no', 'complaint_id',
        'findings', 'result'
    ];
    protected $casts = [
        'visit_date' => 'datetime',
        'contact_persons' => 'array', // JSON dizisi olarak çalışacak
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
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function machine()
    {
        return $this->belongsTo(CustomerMachine::class, 'customer_machine_id');
    }
    public function user() { // Servis Veren
        return $this->belongsTo(User::class);
    }
    public function product() { // Ürün Tanımı
        return $this->belongsTo(CustomerProduct::class, 'customer_product_id');
    }
    public function complaint() { // Bağlı Şikayet
        return $this->belongsTo(Complaint::class);
    }
}
