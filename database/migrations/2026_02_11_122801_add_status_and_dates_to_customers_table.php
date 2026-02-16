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
    Schema::table('customers', function (Blueprint $table) {
        $table->boolean('is_active')->default(true)->after('email'); // Aktif/Pasif durumu
        $table->date('start_date')->nullable()->after('is_active'); // Çalışmaya başlama tarihi
        $table->date('end_date')->nullable()->after('start_date'); // Bitiş tarihi (Eğer pasifse)
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
