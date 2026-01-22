<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Maliyetten hemen sonra, varsayılan TRY olacak şekilde ekliyoruz
            $table->string('currency', 3)->default('TRY')->after('cost');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
