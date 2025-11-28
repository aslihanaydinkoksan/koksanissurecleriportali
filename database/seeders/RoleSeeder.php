<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Standart rolleri oluşturuyoruz
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Yönetici', 'slug' => 'yonetici'],
            ['name' => 'Müdür', 'slug' => 'mudur'], // Yeni eklediğimiz rol
            ['name' => 'Kullanıcı', 'slug' => 'kullanici'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}