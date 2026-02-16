<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProduct extends Model
{
    protected $fillable = ['customer_id', 'name', 'category', 'annual_volume', 'notes'];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    public function machines() {
        return $this->hasMany(CustomerMachine::class);
    }
    public function testResults() {
        return $this->hasMany(TestResult::class);
    }
    public function samples() {
        return $this->hasMany(CustomerSample::class);
    }
}