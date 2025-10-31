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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique(); // Plaka (Benzersiz olmalı)
            $table->string('type'); // Örn: "Otomobil", "Kamyonet", "Minibüs"
            $table->string('brand_model')->nullable(); // Örn: "Ford Transit", "Fiat Doblo"
            $table->text('description')->nullable(); // Araçla ilgili notlar
            $table->boolean('is_active')->default(true); // Araç kullanımda mı? (Arşivlemek için)
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
        Schema::dropIfExists('vehicles');
    }
};
