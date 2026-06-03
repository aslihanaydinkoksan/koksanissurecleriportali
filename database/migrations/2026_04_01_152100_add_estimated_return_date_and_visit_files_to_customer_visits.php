<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            $table->dateTime('estimated_return_date')->nullable()->after('visit_date');
            $table->json('visit_files')->nullable()->after('visitor_name'); // IAA'dan gelen dosya URL'leri
        });
    }

    public function down()
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            $table->dropColumn(['estimated_return_date', 'visit_files']);
        });
    }
};
