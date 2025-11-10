<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMachine extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'model', 'serial_number', 'installation_date'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
