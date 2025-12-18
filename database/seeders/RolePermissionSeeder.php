<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Cache Temizliği
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. İZİNLER (Yetkiler) - Senin görseldeki 'manage_bookings'i de ekledim
        $permissions = [
            'view_dashboard',
            'view_logistics',
            'view_production',
            'view_maintenance',
            'view_administrative',
            'manage_users',
            'manage_bookings', // Rezervasyon yönetimi
            'manage_fleet',    // Filo yönetimi
            'approve_maintenance',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. ROLLER - Görselindeki verileri buraya işledim
        $roles = [
            'admin' => Permission::all(), // Admin her şeyi yapar
            'yonetici' => Permission::all(),
            'mudur' => ['view_logistics', 'view_production'],
            'lojistik_personeli' => ['view_logistics'],
            'uretim_personeli' => ['view_production'],
            'idari_isler_personeli' => ['view_administrative'],
            'bakim_personeli' => ['view_maintenance'],
            'booking_manager' => ['view_administrative', 'manage_bookings'], // Rezervasyon Sorumlusu
            'fleet_manager' => ['view_administrative', 'manage_fleet'],      // Filo Sorumlusu
            'user' => ['view_dashboard'], // Standart kullanıcı
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            // Rolü oluştur
            $role = Role::firstOrCreate(['name' => $roleName]);
            // Yetkileri ata
            $role->givePermissionTo($rolePermissions);
        }
    }
}