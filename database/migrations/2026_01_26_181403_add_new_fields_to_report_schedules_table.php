<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('report_schedules', function (Blueprint $table) {
            $table->string('data_scope')->after('frequency')->default('last_24h');
            $table->string('file_format')->after('data_scope')->default('excel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_schedules', function (Blueprint $table) {
            //
        });
    }
};
