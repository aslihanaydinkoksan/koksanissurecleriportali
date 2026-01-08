<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // business_unit_id sütununu ekliyoruz (nullable olabilir, belki fabrika personeli değildir)
            $table->unsignedBigInteger('business_unit_id')->nullable()->after('id');

            // Opsiyonel: Foreign Key (Eğer business_units tablon varsa)
            $table->foreign('business_unit_id')->references('id')->on('business_units')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('business_unit_id');
        });
    }
};
