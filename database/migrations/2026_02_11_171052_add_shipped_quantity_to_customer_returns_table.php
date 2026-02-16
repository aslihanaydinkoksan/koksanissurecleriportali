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
        Schema::table('customer_returns', function (Blueprint $table) {
            // Gönderilen toplam miktar (İade miktarından hemen önce eklensin)
            $table->decimal('shipped_quantity', 15, 2)->default(0)->after('product_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_returns', function (Blueprint $table) {
            //
        });
    }
};
