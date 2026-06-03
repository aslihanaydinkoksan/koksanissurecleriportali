<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. TÜM İZİNLER (Modül bazlı görünüm yetkileri dahil)
        $allPermissions = [
            'view_dashboard',
            'view_logistics',
            'view_production',
            'view_maintenance',
            'view_administrative',
            'view_customers',
            'manage_shipments',
            'manage_users',
            'approve_shipments',
            'approve_maintenance',
            'manage_fleet' // vb.
        ];

        foreach ($allPermissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // 2. ROLLERİ OLUŞTURUYORUZ

        // A. SUPERADMIN: Gerçek sınırsız yetki (Sistem Sahibi)
        $superadmin = Role::firstOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web'],
            ['slug' => 'superadmin']
        );
        $superadmin->syncPermissions(Permission::all());

        // B. ADMIN: Yüksek yetkili yönetici (Birim Yöneticisi)
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['slug' => 'admin']
        );
        $admin->syncPermissions(Permission::all());

        // C. YONETICI: Tüm modülleri "Görür" ve "Onaylar" ama sistem ayarlarını yapamaz
        $yonetici = Role::firstOrCreate(
            ['name' => 'yonetici', 'guard_name' => 'web'],
            ['slug' => 'yonetici']
        );
        $yonetici->syncPermissions([
            'view_dashboard',
            'view_logistics',
            'view_production',
            'view_maintenance',
            'view_administrative',
            'view_customers',
            'approve_shipments',
            'approve_maintenance'
        ]);

        // D. USER (STANDART KULLANICI): Sadece temel görüntüleme ve işlem yetkileri
        $userRole = Role::firstOrCreate(
            ['name' => 'user', 'guard_name' => 'web'],
            ['slug' => 'user']
        );
        $userRole->syncPermissions([
            'view_dashboard',
            'view_logistics',
            'view_production',
            'view_maintenance',
            'view_administrative'
        ]);
    }
}