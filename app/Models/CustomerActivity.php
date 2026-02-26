<?php

namespace App\Models;

use App\Traits\HasBusinessUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerActivity extends Model
{
    use HasFactory, HasBusinessUnit , SoftDeletes;
    protected $fillable = ['customer_id', 'user_id', 'type', 'contact_persons','description', 'activity_date', 'business_unit_id'];

    protected $casts = [
        'activity_date' => 'datetime',
        'contact_persons' => 'array',
    ];

    // Aktiviteyi giren personel
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hangi müşteriye ait
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
