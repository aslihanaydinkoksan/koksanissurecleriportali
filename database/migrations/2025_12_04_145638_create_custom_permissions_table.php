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
        // 1. YETKİLER TABLOSU
        // Spatie'den farkı: 'guard_name' yok, 'key' var.
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Kodda kullanacağımız anahtar (Örn: manage_bookings)
            $table->string('name'); // Ekranda görünecek isim (Örn: Rezervasyon Yönetimi)
            $table->timestamps();
        });

        // 2. ROL-YETKİ ARA TABLOSU
        // Senin mevcut 'roles' tablon ile yeni 'permissions' tablosunu bağlar.
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();

            // Senin mevcut 'roles' tablonun ID'sine bağlanır
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');

            // Yukarıdaki 'permissions' tablosuna bağlanır
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');

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
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
    }
};
