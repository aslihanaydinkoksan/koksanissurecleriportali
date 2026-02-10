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
        Schema::table('customer_visits', function (Blueprint $table) {
            // Yeni 'customer_machine_id' sütununu ekle (nullable, opsiyonel)
            $table->foreignId('customer_machine_id')
                ->nullable() // Her ziyaret bir makineyle ilgili olmayabilir
                ->after('travel_id') // travel_id'den sonra gelsin
                ->constrained('customer_machines') // customer_machines tablosuna bağla
                ->onDelete('set null'); // Makine silinirse bu ID null olsun

            // 'has_machine' sütununu kaldır
            if (Schema::hasColumn('customer_visits', 'has_machine')) {
                $table->dropColumn('has_machine');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            //
        });
    }
};
