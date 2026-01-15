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
    public function up(): void
    {
        // Bookings tablosuna lokasyon ekleme
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('location')->nullable()->after('provider_name');
        });

        // Travels tablosuna lokasyon ekleme
        Schema::table('travels', function (Blueprint $table) {
            $table->string('location')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('location');
        });

        Schema::table('travels', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
