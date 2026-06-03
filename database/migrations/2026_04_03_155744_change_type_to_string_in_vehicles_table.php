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
        Schema::table('vehicles', function (Blueprint $table) {
            // ENUM olan 'type' sütununu 'string' yaparak "Kamyon", "Sedan" vb. değerlerin girilmesine izin veriyoruz.
            $table->string('type', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->enum('type', ['company', 'logistics'])->change();
        });
    }
};
