<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit;
/**
 * App\Models\TestResult
 *
 * @property int $id
 * @property int $customer_id
 * @property int $user_id
 * @property int|null $customer_machine_id
 * @property string $test_name
 * @property string $test_date
 * @property string|null $summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Customer $customer
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereCustomerMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereTestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereTestName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperTestResult
 */
class TestResult extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Loggable, HasBusinessUnit; // 2. Ekle
    protected $fillable = ['customer_id', 'user_id', 'customer_machine_id', 'test_name', 'test_date', 'summary', 'business_unit_id'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
