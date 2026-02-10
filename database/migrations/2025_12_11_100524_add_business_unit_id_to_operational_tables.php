<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Dizi içindeki tablolara tek seferde ekleme yapalım (Dinamik yaklaşım)
        $tables = [
            'shipments',
            'production_plans',
            'maintenance_assets',
            'vehicles',
            'complaints',
            'bookings'
        ];

        foreach ($tables as $tableName) {
            // Tablo var mı kontrolü (Hata almamak için)
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    // foreignId ile ekliyoruz, nullable yapıyoruz ki mevcut veriler patlamasın.
                    // İleride verileri doldurunca nullable'ı kaldırabilirsin.
                    $table->foreignId('business_unit_id')
                        ->nullable()
                        ->after('id') // ID'den hemen sonra gelsin, göze çarpsın
                        ->constrained('business_units');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = ['shipments', 'production_plans', 'maintenance_assets', 'vehicles', 'complaints', 'bookings'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['business_unit_id']);
                    $table->dropColumn('business_unit_id');
                });
            }
        }
    }
};
