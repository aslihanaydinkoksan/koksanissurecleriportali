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
        // Hangi tablolar izlenmeli? Neredeyse hepsi.
        $tables = [
            'shipments',
            'production_plans',
            'maintenance_plans',
            'complaints',
            'vehicles',
            'bookings',
            'events',
            'logistics_vehicles',
            'maintenance_assets',
            'maintenance_files',
            'travels',
            'vehicle_assignments'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    // Kaydı oluşturan kişi
                    $table->foreignId('created_by')->nullable()->constrained('users');

                    // Soft Delete (Eğer tabloda yoksa ekle)
                    if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                        $table->softDeletes();
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
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
