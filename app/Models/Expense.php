<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasBusinessUnit;

class Expense extends Model
{
    use HasFactory, SoftDeletes, HasBusinessUnit;

    protected $fillable = [
        'business_unit_id',
        'category',
        'amount',
        'currency',
        'description',
        'receipt_date',
        'created_by'
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function expensable()
    {
        return $this->morphTo();
    }
}