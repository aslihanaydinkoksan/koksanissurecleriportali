<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TestResult extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia; // 2. Ekle
    protected $fillable = ['customer_id', 'user_id', 'customer_machine_id', 'test_name', 'test_date', 'summary'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
