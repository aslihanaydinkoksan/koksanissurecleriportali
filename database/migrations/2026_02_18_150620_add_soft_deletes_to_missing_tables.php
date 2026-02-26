<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Soft Delete olması gereken tüm tabloların listesi.
     * Zaten ekli olanları (Users, Shipments vb.) kod otomatik atlayacak.
     */
    protected $tables = [
        'bookings',
        'complaints',
        'customers',
        'customer_activities',
        'customer_activity_logs',
        'customer_visits',
        'customer_machines',
        'customer_products',
        'customer_returns',
        'customer_samples',
        'customer_contacts',
        'custom_field_definitions',
        'expenses',
        'events',
        'event_types',
        'files',
        'maintenance_activity_logs',
        'maintenance_assets',
        'maintenance_files',
        'maintenance_plans',
        'maintenance_time_entries',
        'maintenance_types',
        'opportunities',
        'production_plans',
        'teams',
        'team_user',
        'test_results',
        'todos',
        'travels',
        'event_types',
        'shipments',
        'shipments_vehicle_types',
        'roles', // Spatie rolleri için gerekirse
        'permissions',
        'vehicles',
        // 'activity_log' -> Loglar silinmez, buna eklemiyoruz.
    ];

    public function up()
    {
        foreach ($this->tables as $tableName) {
            // Tablo var mı ve 'deleted_at' sütunu EKSİK mi?
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->softDeletes(); // deleted_at sütununu ekler
                });
            }
        }
    }

    public function down()
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};