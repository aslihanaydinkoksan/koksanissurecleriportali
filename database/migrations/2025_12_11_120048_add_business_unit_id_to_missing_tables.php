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
        // Eksik olma ihtimali yüksek olan tablolar listesi
        $tables = ['events', 'vehicle_assignments', 'travels'];

        foreach ($tables as $tableName) {
            // Tablo veritabanında var mı?
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Sütun zaten var mı? Yoksa ekle.
                    if (!Schema::hasColumn($tableName, 'business_unit_id')) {
                        $table->foreignId('business_unit_id')
                            ->nullable()
                            ->after('id') // ID'den sonraya koy
                            ->constrained('business_units');
                    }
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
        $tables = ['events', 'vehicle_assignments', 'travels'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'business_unit_id')) {
                        $table->dropForeign(['business_unit_id']);
                        $table->dropColumn('business_unit_id');
                    }
                });
            }
        }
    }
};
