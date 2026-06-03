<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('complaints', 'remote_url')) {
            Schema::table('complaints', function (Blueprint $table) {
                $table->string('remote_url')->nullable();
            });
        }

        if (!Schema::hasColumn('customer_returns', 'remote_url')) {
            Schema::table('customer_returns', function (Blueprint $table) {
                $table->string('remote_url')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->dropColumn('remote_url');
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('remote_url');
        });
    }
};
