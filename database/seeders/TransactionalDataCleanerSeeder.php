<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransactionalDataCleanerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Foreign Key kontrolünü kapat (Hata almamak için şart)
        Schema::disableForeignKeyConstraints();

        // 2. TEMİZLENECEK TABLOLAR LİSTESİ
        // Buraya "Demirbaşlar" hariç, silinmesini istediğin operasyonel tabloları yazıyoruz.
        $tablesToTruncate = [
            'vehicle_assignments',       // Senin ana çalışma alanın
            'notifications',             // Bildirimler
            'activity_log',              // Loglar
            'customer_activity_logs',
            'customer_activities',
            'customer_visits',
            'failed_jobs',
            'jobs',
            'test_results',
            'bookings',
            'complaints',
            'travels',
            'shipments',
            'maintenance_activity_logs',
            'maintenance_time_entries',
            'service_schedules',
            'files',                     // Dosya kayıtları (fiziksel dosyaları silmez, db kaydını siler)
            'media',
            'password_resets',
            'personal_access_tokens',
            'customers',
            'customer_machines',
            'vehicles',
            'logistics_vehicles',
        ];

        foreach ($tablesToTruncate as $table) {
            // Tablonun var olup olmadığını kontrol et (Hata önleyici)
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("Tablo temizlendi: $table");
            }
        }

        // 3. Foreign Key kontrolünü tekrar aç
        Schema::enableForeignKeyConstraints();
    }
}