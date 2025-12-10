<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class File extends Model
{
    use SoftDeletes, Loggable;
    protected $guarded = [];

    public function fileable()
    {
        return $this->morphTo();
    }
}
