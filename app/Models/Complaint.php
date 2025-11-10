<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Complaint extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = ['customer_id', 'user_id', 'customer_machine_id', 'title', 'description', 'status'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
