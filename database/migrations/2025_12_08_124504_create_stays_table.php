<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    //Check-in / Check-out işlemleri burada dönecek.
    public function up(): void
    {
        Schema::create('stays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained();
            $table->foreignId('location_id')->constrained(); // Hangi odaya girdi?

            $table->dateTime('check_in_date');
            $table->dateTime('check_out_date')->nullable(); // Çıkınca dolacak

            // JSON olarak basit checklist tutabiliriz.
            // Örn: {"anahtar": true, "klima_kumandasi": true}
            $table->json('check_in_items')->nullable();
            $table->json('check_out_items')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stays');
    }
};
