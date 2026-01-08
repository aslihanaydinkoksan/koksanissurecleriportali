<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Varsayılan olarak 'pending' (Bekliyor) olsun
            $table->string('shipment_status')->default('pending')->after('business_unit_id');
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('shipment_status');
        });
    }
};
