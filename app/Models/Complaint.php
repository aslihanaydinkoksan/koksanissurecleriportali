<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit; // <--- 1. Use ekle

/**
 * App\Models\Complaint
 *
 * @property int $id
 * @property int $customer_id
 * @property int $user_id
 * @property int|null $customer_machine_id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Customer $customer
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCustomerMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperComplaint
 */
class Complaint extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Loggable, HasBusinessUnit;
    protected $fillable = ['customer_id', 'user_id', 'customer_machine_id', 'title', 'description', 'status'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
