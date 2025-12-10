<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Varsa eski admini silmeyelim, kontrol edelim
        if (!User::where('email', 'admin@koksan.com')->exists()) {
            User::create([
                'name' => 'Sistem Yöneticisi',
                'email' => 'admin@koksan.com', // Giriş maili
                'password' => Hash::make('12345678'), // Güvenli şifre
                'email_verified_at' => now(),
            ]);

            $this->command->info('Admin kullanıcısı oluşturuldu! (Şifre: 12345678)');
        }
    }
}
