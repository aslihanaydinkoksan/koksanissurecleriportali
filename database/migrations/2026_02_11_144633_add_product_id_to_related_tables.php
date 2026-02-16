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
        // Makinelere Ürün Bağlantısı
        Schema::table('customer_machines', function (Blueprint $table) {
            $table->foreignId('customer_product_id')->nullable()->constrained('customer_products')->nullOnDelete();
        });

        // Test Sonuçlarına Ürün Bağlantısı
        Schema::table('test_results', function (Blueprint $table) {
            $table->foreignId('customer_product_id')->nullable()->constrained('customer_products')->nullOnDelete();
        });

        // Numunelere Ürün Bağlantısı
        Schema::table('customer_samples', function (Blueprint $table) {
            $table->foreignId('customer_product_id')->nullable()->constrained('customer_products')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('related_tables', function (Blueprint $table) {
            //
        });
    }
};
