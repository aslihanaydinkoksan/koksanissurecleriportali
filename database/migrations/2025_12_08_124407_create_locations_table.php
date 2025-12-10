<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    //Sistemin iskeleti
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            // Hiyerarşi: Site -> Blok -> Daire -> Oda
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();

            $table->string('name'); // Örn: "Elif Sitesi", "A Blok", "No: 5"

            // Lokasyon Tipi (Kod tarafında kontrol edeceğiz)
            $table->enum('type', ['campus', 'site', 'block', 'apartment', 'room', 'common_area']);

            // Mülkiyet (Kiralık mı Mülk mü?)
            $table->enum('ownership', ['owned', 'rented'])->default('owned');

            $table->integer('capacity')->default(0); // Kaç kişi kalabilir?
            $table->string('wifi_password')->nullable(); // Çıktı kağıdı için
            $table->boolean('is_active')->default(true); // Tadilattaysa false yapabiliriz

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
