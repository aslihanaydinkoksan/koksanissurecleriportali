<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            $table->unsignedBigInteger('remote_id')->nullable()->after('id');
            $table->string('remote_system')->nullable()->after('remote_id');
            $table->string('remote_url')->nullable()->after('remote_system');
        });
    }

    public function down()
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            $table->dropColumn(['remote_id', 'remote_system', 'remote_url']);
        });
    }
};
