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
        Schema::create('business_unit_user', function (Blueprint $table) {
            $table->id();
            // Hangi Kullanıcı?
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Hangi Birim?
            $table->foreignId('business_unit_id')->constrained()->onDelete('cascade');

            // Bu kullanıcının bu birimdeki özel rolü var mı? (Opsiyonel ama hayat kurtarır)
            // Örneğin Ahmet Lojistik Müdürüdür ama Coped biriminde sadece "İzleyici"dir.
            $table->string('role_in_unit')->nullable();

            $table->timestamps();

            // Aynı kullanıcı aynı birime iki kere eklenmesin
            $table->unique(['user_id', 'business_unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_unit_user');
    }
};
