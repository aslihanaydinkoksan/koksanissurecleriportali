<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit;

class CustomerProduct extends Model
{
    use SoftDeletes, Loggable, HasBusinessUnit;

    protected $fillable = [
        'business_unit_id',
        'customer_id',
        'name',
        'category',
        'annual_volume',
        'notes',
        'supplier_type',
        'competitor_id',
        'customer_contact_id',
        'technical_specs',
        'performance_notes'
    ];
    protected $casts = [
        'technical_specs' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    // Rakip İlişkisi
    public function competitor()
    {
        return $this->belongsTo(Competitor::class);
    }

    // İlgili Müşteri Yetkilisi İlişkisi
    public function contact()
    {
        return $this->belongsTo(CustomerContact::class, 'customer_contact_id');
    }

    public function machines()
    {
        return $this->hasMany(CustomerMachine::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function samples()
    {
        return $this->hasMany(CustomerSample::class);
    }
}
