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
        Schema::create('custom_field_definitions', function (Blueprint $table) {
            $table->id();
            // Hangi Model için? (Örn: 'App\Models\Shipment' veya 'shipment')
            $table->string('model_scope')->index();

            $table->string('key');    // form_input_name
            $table->string('label');  // Ekranda görünen isim
            $table->string('type');   // text, select, date, number, boolean
            $table->json('options')->nullable(); // Select ise seçenekler
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true); // Alanı silmek yerine pasife çekmek için

            $table->unique(['model_scope', 'key']); // Aynı modelde aynı key olamaz
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
        Schema::dropIfExists('custom_field_definitions');
    }
};
