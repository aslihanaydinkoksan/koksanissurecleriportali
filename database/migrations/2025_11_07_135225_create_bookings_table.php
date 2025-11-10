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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Hangi Seyahat Planına ait?
            $table->foreignId('travel_id')->constrained('travels')->onDelete('cascade');

            // Rezervasyonu kim ekledi?
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Rezervasyon Tipi (Uçuş, Otel vb.)
            $table->string('type'); // 'flight', 'hotel', 'car_rental', 'train', 'other'

            // Rezervasyon Bilgileri
            $table->string('provider_name')->nullable(); // Örn: "Türk Hava Yolları", "Hilton"
            $table->string('confirmation_code')->nullable(); // Örn: "PNR: ABC123"
            $table->decimal('cost', 10, 2)->nullable(); // Masraf takibi için

            // Tarihler
            $table->dateTime('start_datetime')->nullable(); // Uçuş kalkış / Otel Check-in
            $table->dateTime('end_datetime')->nullable(); // Uçuş varış / Otel Check-out

            $table->text('notes')->nullable(); // Ek notlar
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
        Schema::dropIfExists('bookings');
    }
};
