<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes;

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
        'competitor_id',
        'loss_reason'
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
    public function histories()
    {
        return $this->hasMany(OpportunityHistory::class)->orderBy('created_at', 'desc');
    }
    public function competitor()
    {
        return $this->belongsTo(Competitor::class, 'competitor_id');
    }
}
