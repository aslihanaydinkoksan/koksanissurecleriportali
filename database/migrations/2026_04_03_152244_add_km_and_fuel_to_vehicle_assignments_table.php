<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('vehicle_assignments', 'start_km')) {
            Schema::table('vehicle_assignments', function (Blueprint $table) {
                $table->decimal('start_km', 15, 2)->nullable()->after('notes');
                $table->decimal('end_km', 15, 2)->nullable()->after('start_km');
                $table->string('start_fuel_level')->nullable()->after('end_km');
                $table->string('end_fuel_level')->nullable()->after('start_fuel_level');
            });
        }
    }

    public function down()
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->dropColumn(['start_km', 'end_km', 'start_fuel_level', 'end_fuel_level']);
        });
    }
};
