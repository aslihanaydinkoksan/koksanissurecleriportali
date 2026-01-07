<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('custom_field_definitions', function (Blueprint $table) {
            $table->foreignId('business_unit_id')
                ->nullable()
                ->after('model_scope')
                ->constrained('business_units')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('custom_field_definitions', function (Blueprint $table) {
            $table->dropForeign(['business_unit_id']);
            $table->dropColumn('business_unit_id');
        });
    }
};
