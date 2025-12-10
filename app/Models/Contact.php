<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Contact extends Model
{
    use SoftDeletes, Loggable;
    protected $guarded = [];

    public function assignments()
    {
        return $this->hasMany(ServiceAssignment::class);
    }
}
