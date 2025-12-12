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
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Örnek: Coped, Preform, Streç
            $table->string('code')->nullable(); // Örnek: COP, PRE (Raporlarda kısaltma için)
            $table->string('slug')->unique(); // URL dostu isim (coped-fabrikasi)
            $table->boolean('is_active')->default(true); // Yarın bir birim kapanırsa veriyi silme, pasife çek.
            $table->softDeletes(); // Senior kuralı: Asla fiziksel silme yapma.
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
        Schema::dropIfExists('business_units');
    }
};
