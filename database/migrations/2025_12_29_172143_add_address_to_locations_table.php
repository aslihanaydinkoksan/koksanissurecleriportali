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
        Schema::table('locations', function (Blueprint $table) {
            // İsimden sonra adres alanı (Text çünkü uzun olabilir)
            $table->text('address')->nullable()->after('name');
            // Adresten sonra Google Maps linki
            $table->string('map_link', 500)->nullable()->after('address');
        });
    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['address', 'map_link']);
        });
    }
};
