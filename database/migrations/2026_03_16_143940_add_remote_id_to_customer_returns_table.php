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
        if (!Schema::hasColumn('customer_returns', 'remote_id')) {
            Schema::table('customer_returns', function (Blueprint $table) {
                $table->unsignedBigInteger('remote_id')->nullable()->after('id');
                $table->string('remote_system')->nullable()->after('remote_id'); // e.g., 'iaa'
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->dropColumn(['remote_id', 'remote_system']);
        });
    }
};
