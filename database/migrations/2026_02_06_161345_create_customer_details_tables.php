<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Müşteri İletişim Kişileri Tablosu
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Kişi Adı Soyadı
            $table->string('title')->nullable(); // Ünvanı (Satın Alma Müdürü vb.)
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_primary')->default(false); // Ana iletişim kişisi mi?
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. İadeler Tablosu
        Schema::create('customer_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_unit_id')->constrained(); // İş birimi aidiyeti
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            // İade bir şikayete bağlı olabilir (Opsiyonel)
            $table->foreignId('complaint_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('user_id')->constrained(); // Kaydı açan personel
            $table->string('product_name')->nullable(); // İade edilen ürün/kalem (İleride ürün tablosuna bağlanabilir)
            $table->string('batch_number')->nullable(); // Parti No
            $table->decimal('quantity', 10, 2)->default(0); // Miktar
            $table->string('unit')->default('ad'); // Birim (kg, adet vb.)
            $table->text('reason')->nullable(); // İade Nedeni
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending'); // Durum
            $table->date('return_date'); // İade Tarihi
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_returns');
        Schema::dropIfExists('customer_contacts');
    }
};