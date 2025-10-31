<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Yeni gemi alanlarını ekliyoruz (nullable, çünkü sadece gemi için geçerli)
            $table->string('imo_numarasi')->nullable()->after('sofor_adi');
            $table->string('gemi_adi')->nullable()->after('imo_numarasi');
            $table->string('kalkis_limani')->nullable()->after('gemi_adi');
            $table->string('varis_limani')->nullable()->after('kalkis_limani');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Geri alma işlemi: Eklediğimiz sütunları kaldırıyoruz
            $table->dropColumn(['imo_numarasi', 'gemi_adi', 'kalkis_limani', 'varis_limani']);
        });
    }
};