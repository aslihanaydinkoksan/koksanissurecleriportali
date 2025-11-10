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
        Schema::create('customer_visits', function (Blueprint $table) {
            $table->id();
            // Bu kayıt, senin 'Event' tablonla eşleşecek
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            // Bu kayıt, bir müşteriye bağlı olmalı
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // Bu kayıt, opsiyonel olarak bir seyahate bağlı olabilir
            $table->foreignId('travel_id')->nullable()->constrained('travels')->onDelete('set null');

            // CRM Alanları
            $table->string('visit_purpose'); // satis_sonrasi, egitim, rutin_ziyaret, vb.
            $table->boolean('has_machine')->nullable();
            $table->text('after_sales_notes')->nullable();
            // ... (bir önceki cevapta bahsettiğimiz diğer tüm CRM alanları)

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
        Schema::dropIfExists('customer_visits');
    }
};
