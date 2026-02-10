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
        Schema::create('customer_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Müşteri silinirse aktiviteleri de silinsin
            $table->foreignId('user_id')->constrained(); // Aktiviteyi giren personel
            $table->string('type'); // phone, email, meeting, note
            $table->text('description'); // Görüşme detayları
            $table->dateTime('activity_date'); // Ne zaman yapıldı?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_activities');
    }
};
