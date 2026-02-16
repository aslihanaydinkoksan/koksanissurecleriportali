<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit;

class CustomerReturn extends Model
{
    use HasFactory, SoftDeletes, Loggable, HasBusinessUnit;

    protected $fillable = [
        'business_unit_id',
        'customer_id',
        'complaint_id',
        'user_id',
        'product_name',
        'shipped_quantity',
        'shipped_unit',
        'batch_number',
        'quantity',
        'unit',
        'reason',
        'status',
        'return_date'
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}