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
        Schema::table('maintenance_tables', function (Blueprint $table) {
            // Ana Tablolar
            Schema::table('maintenance_plans', function (Blueprint $table) {
                $table->softDeletes();
            });
            Schema::table('maintenance_assets', function (Blueprint $table) {
                $table->softDeletes();
            });
            Schema::table('maintenance_types', function (Blueprint $table) {
                $table->softDeletes();
            });

            // Yan Tablolar
            Schema::table('maintenance_files', function (Blueprint $table) {
                $table->softDeletes();
            });
            Schema::table('maintenance_time_entries', function (Blueprint $table) {
                $table->softDeletes();
            });
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
            $table->dropSoftDeletes();
        });
        Schema::table('maintenance_assets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('maintenance_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('maintenance_files', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('maintenance_time_entries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
