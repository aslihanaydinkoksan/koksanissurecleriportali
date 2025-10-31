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
        Schema::table('shipments', function (Blueprint $table) {
            $table->timestamp('onaylanma_tarihi')
                ->nullable()
                ->after('varis_noktasi');
            $table->foreignId('onaylayan_user_id')
                ->nullable()
                ->after('onaylanma_tarihi') // Artık bu sütun var olacak
                ->constrained('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['onaylayan_user_id']);
            $table->dropColumn('onaylanma_tarihi');
        });
    }
};
