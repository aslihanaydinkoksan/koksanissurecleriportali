<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'slug'];

    /**
     * Bu birime ait kullanıcıları getirir.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
