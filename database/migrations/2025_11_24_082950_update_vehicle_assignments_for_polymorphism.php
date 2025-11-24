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
            $table->dropForeign(['vehicle_id']);
            $table->string('vehicle_type')->nullable()->after('vehicle_id');
            $table->index(['vehicle_id', 'vehicle_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->dropColumn('vehicle_type');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('set null');
        });
    }
};
