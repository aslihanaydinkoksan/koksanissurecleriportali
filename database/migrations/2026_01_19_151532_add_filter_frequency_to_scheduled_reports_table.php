<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('scheduled_reports', function (Blueprint $table) {
            // frequency sütunundan hemen sonrasına ekleyelim
            $table->string('filter_frequency')->default('weekly')->after('frequency');
        });
    }

    public function down()
    {
        Schema::table('scheduled_reports', function (Blueprint $table) {
            $table->dropColumn('filter_frequency');
        });
    }
};
