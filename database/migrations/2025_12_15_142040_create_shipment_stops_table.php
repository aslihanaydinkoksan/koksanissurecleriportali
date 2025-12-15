<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_stops', function (Blueprint $table) {
            $table->id();

            // Ana sevkiyat ile bağlantı
            $table->unsignedBigInteger('shipment_id');

            // Durak/Müşteri Adı (Nereye uğradı?)
            $table->string('location_name');

            // İndirilen Miktar (Örn: 300 Ton)
            $table->string('dropped_amount');

            // Kalan Miktar (Örn: 500 Ton - Bunu otomatik hesaplayıp yazacağız)
            $table->string('remaining_amount');

            // İşlem Tarihi (Ne zaman indirdi?)
            $table->dateTime('stop_date');

            // Teslim Alan / Açıklama
            $table->string('receiver_name')->nullable(); // Teslim alan
            $table->text('note')->nullable(); // Ekstra notlar

            $table->timestamps();

            // İlişki (shipments tablosundaki id silinirse bu duraklar da silinsin)
            $table->foreign('shipment_id')
                ->references('id')
                ->on('shipments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_stops');
    }
};
