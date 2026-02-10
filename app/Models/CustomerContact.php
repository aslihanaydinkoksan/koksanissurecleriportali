<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerContact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['customer_id', 'name', 'title', 'email', 'phone', 'is_primary'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}