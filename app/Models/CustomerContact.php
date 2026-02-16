<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit;

class CustomerContact extends Model
{
    use HasFactory, SoftDeletes, Loggable, HasBusinessUnit;

    protected $fillable = ['business_unit_id','customer_id', 'name', 'title', 'email', 'phone', 'is_primary'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}