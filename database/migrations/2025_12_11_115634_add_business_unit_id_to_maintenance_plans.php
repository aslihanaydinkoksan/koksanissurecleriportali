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
        Schema::table('maintenance_plans', function (Blueprint $table) {
            // Eğer sütun zaten varsa hata vermesin diye kontrol ediyoruz
            if (!Schema::hasColumn('maintenance_plans', 'business_unit_id')) {
                $table->foreignId('business_unit_id')
                    ->nullable() // Mevcut veriler patlamasın diye nullable
                    ->after('id')
                    ->constrained('business_units');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_plans', function (Blueprint $table) {
            if (Schema::hasColumn('maintenance_plans', 'business_unit_id')) {
                $table->dropForeign(['business_unit_id']);
                $table->dropColumn('business_unit_id');
            }
        });
    }
};
