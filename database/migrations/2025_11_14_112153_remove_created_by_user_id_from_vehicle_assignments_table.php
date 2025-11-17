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
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->dropForeign(['created_by_user_id']);
            $table->dropColumn('created_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')
                ->nullable()
                ->after('id')
                ->constrained('users');
        });
    }
};
