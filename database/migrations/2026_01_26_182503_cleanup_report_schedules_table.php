<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('report_schedules', function (Blueprint $table) {
            // Eski isimli sütunlar varsa kaldırıyoruz
            if (Schema::hasColumn('report_schedules', 'scope')) {
                $table->dropColumn('scope');
            }
            if (Schema::hasColumn('report_schedules', 'type')) {
                $table->dropColumn('type');
            }
            // Eğer format diye bir sütun da varsa onu da temizleyelim (garanti olsun)
            if (Schema::hasColumn('report_schedules', 'format')) {
                $table->dropColumn('format');
            }
        });
    }

    public function down(): void
    {
        Schema::table('report_schedules', function (Blueprint $table) {
            $table->string('scope')->nullable();
            $table->string('type')->nullable();
        });
    }
};