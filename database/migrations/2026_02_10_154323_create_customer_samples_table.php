<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_samples', function (Blueprint $table) {
            $table->id();
            // Multi-tenant yapı (Hangi fabrika/birim?)
            $table->foreignId('business_unit_id')->constrained('business_units'); 
            
            // Hangi müşteri?
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            
            // Kim gönderdi/kaydetti?
            $table->foreignId('user_id')->constrained('users');
            
            $table->string('subject'); // Numune Konusu (Örn: Yeni Preform Denemesi)
            $table->string('product_info')->nullable(); // Ürün Bilgisi/Detayı
            
            // Miktar ve Birim
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit')->default('ad'); 
            
            // Lojistik Bilgileri
            $table->string('cargo_company')->nullable(); // Kargo Firması
            $table->string('tracking_number')->nullable(); // Takip No
            $table->date('sent_date')->nullable(); // Gönderim Tarihi
            
            // Durum ve Sonuç
            $table->enum('status', ['preparing', 'sent', 'delivered', 'approved', 'rejected'])->default('preparing');
            $table->text('feedback')->nullable(); // Müşteri Geri Bildirimi
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_samples');
    }
};