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

        // 2. SADECE 3 ROL OLUŞTURUYORUZ

        // A. ADMIN: Sınırsız yetki
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // B. YONETICI: Tüm modülleri "Görür" ve "Onaylar" ama sistem ayarlarını yapamaz
        $yonetici = Role::firstOrCreate(['name' => 'yonetici', 'guard_name' => 'web']);
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

        // C. USER (STANDART KULLANICI): Sadece temel görüntüleme ve işlem yetkileri
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'view_dashboard',
            'view_logistics',    // BU EKSİK OLABİLİR
            'view_production',
            'view_maintenance',
            'view_administrative'
        ]);
    }
}