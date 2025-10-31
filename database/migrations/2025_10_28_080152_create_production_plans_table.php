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
        Schema::create('production_plans', function (Blueprint $table) {
            $table->id();

            // Planı hangi kullanıcının eklediğini bilmek için
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Kullanıcı silinirse planları da silinsin

            $table->string('plan_title'); // Örn: "42. Hafta Üretim Planı"
            $table->date('week_start_date'); // Planın ait olduğu haftanın başlangıç tarihi (Pazartesi)

            // Planın detaylarını (makine, ürün, adet vs.) esnek bir yapıda
            // tutmak için JSON türünü kullanmak çok faydalıdır.
            $table->json('plan_details')->nullable();

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
        Schema::dropIfExists('production_plans');
    }
};
