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
        Schema::table('maintenance_activity_logs', function (Blueprint $table) {
            // Sütunu ekliyoruz
            $table->unsignedBigInteger('business_unit_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('maintenance_activity_logs', function (Blueprint $table) {
            $table->dropColumn('business_unit_id');
        });
    }
};
