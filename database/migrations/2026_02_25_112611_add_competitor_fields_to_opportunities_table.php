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
    public function up(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            // İhalede / Fırsatta rekabet ettiğimiz veya işi kaybettiğimiz rakip
            $table->unsignedBigInteger('competitor_id')->nullable()->after('amount');

            // İşi kaybetme veya kazanma nedenimiz (Örn: "Fiyatımız %10 yüksekti", "Termin süremiz uzundu")
            $table->string('loss_reason')->nullable()->after('competitor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropColumn(['competitor_id', 'loss_reason']);
        });
    }
};
