<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address'
    ];
    public function machines()
    {
        return $this->hasMany(CustomerMachine::class);
    }
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }
    public function visits()
    {
        return $this->hasMany(CustomerVisit::class);
    }
}
