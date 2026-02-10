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
        Schema::table('maintenance_assets', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('name'); // Marka (Husky, Netstal)
            $table->string('model')->nullable()->after('brand'); // Model
            $table->string('serial_number')->nullable()->after('model'); // Seri No
            $table->year('manufacturing_year')->nullable()->after('serial_number'); // Üretim Yılı
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_assets', function (Blueprint $table) {
            $table->dropColumn(['brand', 'model', 'serial_number', 'manufacturing_year']);
        });
    }
};
