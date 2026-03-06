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
        Schema::table('scheduled_reports', function (Blueprint $table) {
            $table->softDeletes(); // deleted_at sütununu ekler
        });
    }

    public function down()
    {
        Schema::table('scheduled_reports', function (Blueprint $table) {
            $table->dropSoftDeletes(); // geri alındığında sütunu siler
        });
    }
};
