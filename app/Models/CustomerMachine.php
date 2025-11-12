<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

/**
 * App\Models\CustomerMachine
 *
 * @property int $id
 * @property int $customer_id
 * @property string $model
 * @property string|null $serial_number
 * @property string|null $installation_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereInstallationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperCustomerMachine
 */
class CustomerMachine extends Model
{
    use HasFactory, Loggable;
    protected $fillable = ['customer_id', 'model', 'serial_number', 'installation_date'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
