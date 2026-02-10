<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Sütun eklenecek tabloların listesi.
     */
    protected $tables = [
        'customer_activities',
        'customer_activity_logs',
        'customer_machines',
        'customer_visits',
        'logistics_vehicles',
        'service_schedules',
        'shipment_stops',
        'teams',
        'test_results',
        'maintenance_activity_logs' // Az önce hata aldığın tabloyu da ekledim, garanti olsun.
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            // Tablo var mı kontrol et (Hata almamak için)
            if (Schema::hasTable($tableName)) {

                // Sütun zaten var mı kontrol et (Tekrar çalıştırılırsa patlamasın)
                if (!Schema::hasColumn($tableName, 'business_unit_id')) {

                    Schema::table($tableName, function (Blueprint $table) {
                        // Sütunu 'id'den hemen sonraya ekle, indeksle ve boş geçilebilir yap
                        $table->unsignedBigInteger('business_unit_id')
                            ->nullable()
                            ->index()
                            ->after('id');
                    });
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'business_unit_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('business_unit_id');
                });
            }
        }
    }
};