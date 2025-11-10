<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerActivityLog extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'model_type', 'model_id', 'action', 'changes'];
    protected $casts = ['changes' => 'array'];
}
