<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'remote_id')) {
                $table->unsignedBigInteger('remote_id')->nullable()->after('id');
                $table->string('remote_system')->nullable()->after('remote_id');
            }
        });

        Schema::table('customer_contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_contacts', 'remote_id')) {
                $table->unsignedBigInteger('remote_id')->nullable()->after('id');
                $table->string('remote_system')->nullable()->after('remote_id');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['remote_id', 'remote_system']);
        });

        Schema::table('customer_contacts', function (Blueprint $table) {
            $table->dropColumn(['remote_id', 'remote_system']);
        });
    }
};
