<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    /**
     * Bu birime ait kullanıcıları getirir.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
