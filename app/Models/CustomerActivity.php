<?php

namespace App\Models;

use App\Traits\HasBusinessUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerActivity extends Model
{
    use HasFactory, HasBusinessUnit;
    protected $fillable = ['customer_id', 'user_id', 'type', 'description', 'activity_date', 'business_unit_id'];

    protected $casts = [
        'activity_date' => 'datetime',
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
