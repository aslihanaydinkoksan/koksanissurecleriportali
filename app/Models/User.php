<?php

namespace App\Models;

// Laravel Standart Kütüphaneleri
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Bizim Eklediklerimiz (Proje Gereksinimleri)
use Illuminate\Database\Eloquent\SoftDeletes; // Silineni saklamak için
use App\Traits\Loggable; // Otomatik log tutmak için
use Illuminate\Database\Eloquent\Relations\HasMany; // İlişki kurmak için
use App\Models\SystemLog; // Log modelini tanıtıyoruz

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    // BURASI ÖNEMLİ: Hem SoftDeletes hem de Loggable özelliklerini yüklüyoruz.
    use HasFactory, Notifiable, SoftDeletes, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | İlişkiler (Relationships)
    |--------------------------------------------------------------------------
    |
    | Kullanıcının yaptığı işlemleri takip etmek için gerekli bağlantı.
    |
    */

    /**
     * Bu kullanıcının sistemde yaptığı tüm işlemleri (Logları) getirir.
     * Örnek: $user->logs dediginde adamın ne zaman ne yaptığını göreceksin.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(SystemLog::class);
    }
}