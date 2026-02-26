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
        // 1. ÜRÜNLER TABLOSU GÜNCELLEMESİ
        Schema::table('customer_products', function (Blueprint $table) {
            // Tedarikçi Tipi: 'koksan' veya 'competitor'
            $table->string('supplier_type')->default('koksan')->after('customer_id');

            // Eğer rakipse hangi rakip?
            $table->unsignedBigInteger('competitor_id')->nullable()->after('supplier_type');

            // İlgili kişi (Söz konusu ürünle/denemeyle ilgilenen muhatap)
            $table->unsignedBigInteger('customer_contact_id')->nullable()->after('competitor_id');

            // Patronun istediği tüm dinamik teknik özellikler, reçeteler ve performans verileri
            $table->json('technical_specs')->nullable()->after('notes');
            $table->text('performance_notes')->nullable()->after('technical_specs');
        });

        // 2. MAKİNELER TABLOSU GÜNCELLEMESİ
        Schema::table('customer_machines', function (Blueprint $table) {
            // Makine kimin? KÖKSAN'ın kurduğu mu yoksa Müşterinin/Rakibin kendi makinesi mi?
            $table->string('ownership_type')->default('customer')->after('customer_id'); // 'koksan', 'customer', 'competitor'
            $table->string('brand')->nullable()->after('model'); // Makine markası
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('customer_products', function (Blueprint $table) {
            $table->dropColumn(['supplier_type', 'competitor_id', 'customer_contact_id', 'technical_specs', 'performance_notes']);
        });

        Schema::table('customer_machines', function (Blueprint $table) {
            $table->dropColumn(['ownership_type', 'brand']);
        });
    }
};
