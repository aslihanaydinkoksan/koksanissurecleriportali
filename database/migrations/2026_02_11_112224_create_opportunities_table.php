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
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Fırsatı takip eden kişi
            $table->string('title'); // Örn: 2024 İhalesi, Yeni Preform İhtiyacı
            $table->text('description')->nullable(); // Duyum/Fırsat detayları
            $table->decimal('amount', 15, 2)->nullable(); // Tahmini Kazanç/Tutar
            $table->string('currency', 10)->default('TRY'); // Para Birimi
            $table->string('stage')->default('duyum'); // Aşama (duyum, teklif, kazanildi, kaybedildi)
            $table->date('expected_close_date')->nullable(); // Tahmini Kapanış/Karar Tarihi
            $table->string('source')->nullable(); // Kaynak (Ziyaret, Referans, İhale vs.)
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
        Schema::dropIfExists('opportunities');
    }
};
