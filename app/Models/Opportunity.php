<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'title',
        'description',
        'amount',
        'currency',
        'stage',
        'expected_close_date',
        'source',
    ];

    protected $casts = [
        'expected_close_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}