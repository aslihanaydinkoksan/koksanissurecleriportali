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
        Schema::create('logistics_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number'); // Plaka
            $table->string('brand');        // Marka
            $table->string('model');        // Model
            $table->decimal('capacity', 8, 2)->nullable(); // Yük kapasitesi
            $table->decimal('current_km', 10, 2)->default(0); // Güncel KM
            $table->string('fuel_type')->nullable(); // Dizel vs.
            $table->string('status')->default('active');
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
        Schema::dropIfExists('logistics_vehicles');
    }
};
