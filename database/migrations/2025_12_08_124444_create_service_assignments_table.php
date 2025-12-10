<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    //Hangi binaya hangi ustanın baktığını tutan tablo.
    public function up(): void
    {
        Schema::create('service_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();

            // Hizmet Türü (elektrik, su, dogalgaz, internet)
            $table->string('service_type');

            // Bir lokasyonun, bir hizmet türü için sadece 1 sorumlusu olabilir.
            // Örn: A Blok'un Elektrikçisi sadece 1 kişi/firma olabilir.
            $table->unique(['location_id', 'service_type']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_assignments');
    }
};
