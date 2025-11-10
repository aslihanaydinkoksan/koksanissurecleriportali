<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVisit extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'customer_id',
        'travel_id',
        'visit_purpose',
        'has_machine',
        'after_sales_notes'
    ];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }
}
