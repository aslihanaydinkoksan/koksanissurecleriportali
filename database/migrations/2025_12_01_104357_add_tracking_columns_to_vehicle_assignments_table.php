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
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            // Eğer start_km yoksa onu da ekleyelim, varsa hata vermesin diye kontrol edebiliriz
            // Ama temiz olsun diye garanti eksik olanları ekliyoruz:

            if (!Schema::hasColumn('vehicle_assignments', 'start_km')) {
                $table->decimal('start_km', 10, 1)->nullable()->after('vehicle_id')->comment('Başlangıç Kilometresi');
            }

            if (!Schema::hasColumn('vehicle_assignments', 'end_km')) {
                $table->decimal('end_km', 10, 1)->nullable()->after('start_km')->comment('Bitiş Kilometresi');
            }

            if (!Schema::hasColumn('vehicle_assignments', 'start_fuel_level')) {
                $table->string('start_fuel_level')->nullable()->after('end_km')->comment('Başlangıç Yakıt: full, 1/2 vb.');
            }

            if (!Schema::hasColumn('vehicle_assignments', 'end_fuel_level')) {
                $table->string('end_fuel_level')->nullable()->after('start_fuel_level')->comment('Bitiş Yakıt');
            }

            if (!Schema::hasColumn('vehicle_assignments', 'fuel_cost')) {
                $table->decimal('fuel_cost', 10, 2)->nullable()->after('end_fuel_level')->comment('Yakıt Maliyeti (TL)');
            }
        });
    }

    public function down()
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->dropColumn(['start_km', 'end_km', 'start_fuel_level', 'end_fuel_level', 'fuel_cost']);
        });
    }
};
