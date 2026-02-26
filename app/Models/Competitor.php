<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_unit_id',
        'name',
        'notes',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(\App\Models\CustomerProduct::class, 'competitor_id');
    }
}
