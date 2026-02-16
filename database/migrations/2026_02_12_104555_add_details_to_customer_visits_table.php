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
            // Kağıt formdaki özel alanlar
            $table->string('visit_reason')->nullable(); // Şikayet, Ziyaret, Deneme...
            $table->text('visit_notes')->nullable(); // Ziyaret Açıklaması
            $table->json('contact_persons')->nullable(); // Görüşülen Kişiler (JSON)
            
            // Ürün ve Teknik Detaylar
            $table->foreignId('customer_product_id')->nullable()->constrained('customer_products')->nullOnDelete();
            $table->string('barcode')->nullable();
            $table->string('lot_no')->nullable();
            $table->foreignId('complaint_id')->nullable()->constrained('complaints')->nullOnDelete(); // Şikayet No
            
            // Sonuçlar
            $table->text('findings')->nullable(); // Tespitler / Yapılan İşlemler
            $table->text('result')->nullable(); // Sonuç
            
           
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
