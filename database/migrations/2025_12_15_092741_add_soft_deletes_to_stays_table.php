<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('stays', function (Blueprint $table) {
            $table->softDeletes(); // Bu, 'deleted_at' sütununu ekler
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('stays', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
