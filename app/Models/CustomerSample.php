<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit;

class CustomerSample extends Model
{
    use HasFactory, SoftDeletes, Loggable, HasBusinessUnit;

    protected $fillable = [
        'business_unit_id', 'customer_id', 'customer_product_id','user_id',
        'subject', 'product_info', 'quantity', 'unit',
        'cargo_company', 'tracking_number', 'sent_date',
        'status', 'feedback'
    ];

    protected $casts = [
        'sent_date' => 'date',
        'quantity' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product()
    {
        return $this->belongsTo(CustomerProduct::class, 'customer_product_id');
    }
}