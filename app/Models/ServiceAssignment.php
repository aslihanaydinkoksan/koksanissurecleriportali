<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class ServiceAssignment extends Model
{
    use Loggable, SoftDeletes;
    protected $guarded = [];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
