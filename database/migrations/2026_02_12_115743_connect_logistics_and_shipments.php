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
        // 1. ADIM: Araç Görevleri Tablosunu CRM Uyumlu Hale Getiriyoruz
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            // CRM'den hızlıca "Şu ürünü götür" diyebilmek için:
            if (!Schema::hasColumn('vehicle_assignments', 'customer_product_id')) {
                $table->foreignId('customer_product_id')->nullable()->after('customer_id')->constrained('customer_products')->nullOnDelete();
            }
            if (!Schema::hasColumn('vehicle_assignments', 'quantity')) {
                $table->decimal('quantity', 15, 2)->nullable()->after('customer_product_id');
            }
            if (!Schema::hasColumn('vehicle_assignments', 'unit')) {
                $table->string('unit', 20)->nullable()->after('quantity');
            }
            // Durum yönetimi için (Pending, On Road, Completed) - Eğer yoksa
            if (!Schema::hasColumn('vehicle_assignments', 'status')) {
                $table->string('status')->default('pending')->after('notes');
            }
        });

        // 2. ADIM: Sevkiyatları (Shipments) Araçlara Bağlıyoruz
        Schema::table('shipments', function (Blueprint $table) {
            // Bir sevkiyat, bir araç görevine atanabilir.
            if (!Schema::hasColumn('shipments', 'vehicle_assignment_id')) {
                $table->foreignId('vehicle_assignment_id')->nullable()->after('id')->constrained('vehicle_assignments')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        // Geri alma işlemleri
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['vehicle_assignment_id']);
            $table->dropColumn('vehicle_assignment_id');
        });

        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->dropForeign(['customer_product_id']);
            $table->dropColumn(['customer_product_id', 'quantity', 'unit']);
        });
    }
};
