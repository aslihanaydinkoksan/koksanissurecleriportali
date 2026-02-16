<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit;
use App\Models\CustomerContact;
use App\Models\CustomerReturn;
use App\Models\CustomerSample;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $name
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaints
 * @property-read int|null $complaints_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerMachine> $machines
 * @property-read int|null $machines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestResult> $testResults
 * @property-read int|null $test_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerVisit> $visits
 * @property-read int|null $visits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperCustomer
 */
class Customer extends Model
{
    use HasFactory, SoftDeletes, Loggable, HasBusinessUnit;
    protected $fillable = [
        'business_unit_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'is_active',
        'start_date',
        'end_date'
    ];
    public function machines()
    {
        return $this->hasMany(CustomerMachine::class);
    }
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }
    public function visits()
    {
        return $this->hasMany(CustomerVisit::class);
    }
    // Müşterinin geçmiş aktiviteleri (En yeniden eskiye)
    public function activities()
    {
        return $this->hasMany(CustomerActivity::class)->orderBy('activity_date', 'desc');
    }

    // Müşteriye yapılan araç görevleri
    public function vehicleAssignments()
    {
        return $this->hasMany(VehicleAssignment::class)->orderBy('start_time', 'desc');
    }
    //  İletişim Kişileri
    public function contacts()
    {
        return $this->hasMany(CustomerContact::class);
    }

    //  İadeler
    public function returns()
    {
        return $this->hasMany(CustomerReturn::class)->orderBy('return_date', 'desc');
    }
    public function samples()
    {
        return $this->hasMany(CustomerSample::class)->orderBy('created_at', 'desc');
    }
    public function opportunities()
    {
        return $this->hasMany(Opportunity::class)->orderBy('created_at', 'desc');
    }
    public function products()
    {
        return $this->hasMany(CustomerProduct::class)->orderBy('name', 'asc');
    }
}
