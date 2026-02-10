<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Önce eski verileri temizle (Mevcut kodunda vardı, koruyoruz)
        $this->call(TransactionalDataCleanerSeeder::class);

        // 2. KRİTİK ADIM: Rolleri ve İzinleri Yükle (Spatie)
        $this->call(RolePermissionSeeder::class);

        // 3. Admin Kullanıcısı (Eğer AdminUserSeeder'ın varsa onu da açabilirsin)
        // $this->call(AdminUserSeeder::class);

        // 4. Diğer veriler...
        // $this->call(MaintenanceSeeder::class);
        // $this->call(ShipmentsVehicleTypeSeeder::class);
    }
}