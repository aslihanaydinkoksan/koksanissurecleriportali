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
        Schema::table('customer_visits', function (Blueprint $table) {
            // Eğer sütun yoksa ekle (güvenli yöntem)
            if (!Schema::hasColumn('customer_visits', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('customer_id')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
