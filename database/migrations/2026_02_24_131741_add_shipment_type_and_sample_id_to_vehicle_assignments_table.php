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
    public function up(): void
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            // Gönderi türü: product (ürün) veya sample (numune)
            $table->string('shipment_type')->default('product')->after('customer_id');
            // Numune ID'si
            $table->unsignedBigInteger('customer_sample_id')->nullable()->after('customer_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->dropColumn(['shipment_type', 'customer_sample_id']);
        });
    }
};
