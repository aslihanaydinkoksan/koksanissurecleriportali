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
        Schema::create('maintenance_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // CNC-01, Jeneratör
            $table->string('category')->default('machine'); // machine, zone, facility
            $table->string('code')->nullable(); // Envanter kodu
            $table->string('location')->nullable(); // Konum
            $table->text('description')->nullable(); // Teknik detay
            $table->boolean('is_active')->default(true); // Pasif cihazlar listede çıkmaz
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
        Schema::dropIfExists('maintenance_assets');
    }
};
