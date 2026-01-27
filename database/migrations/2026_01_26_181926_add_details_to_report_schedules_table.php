<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('report_schedules', function (Blueprint $table) {
            // Eğer daha önce hiç eklenmemişse bu sütunları ekliyoruz
            if (!Schema::hasColumn('report_schedules', 'report_type')) {
                $table->string('report_type')->after('report_name');
            }
            if (!Schema::hasColumn('report_schedules', 'data_scope')) {
                $table->string('data_scope')->after('frequency')->default('last_24h');
            }
            if (!Schema::hasColumn('report_schedules', 'file_format')) {
                $table->string('file_format')->after('data_scope')->default('excel');
            }
        });
    }

    public function down(): void
    {
        Schema::table('report_schedules', function (Blueprint $table) {
            $table->dropColumn(['report_type', 'data_scope', 'file_format']);
        });
    }
};
