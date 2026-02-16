<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customer_contacts', function (Blueprint $table) {
            // Sütunu id'den hemen sonraya ekleyelim.
            // Mevcut kayıtlar varsa hata vermemesi için nullable yapıyoruz,
            // ama kod tarafında dolduracağız.
            $table->foreignId('business_unit_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('business_units')
                  ->onDelete('cascade');
        });

        // (Opsiyonel) Eğer içeride veri varsa, mevcut verileri ebeveynlerinden (customer) alıp güncelle:
       DB::statement("UPDATE customer_contacts cc JOIN customers c ON cc.customer_id = c.id SET cc.business_unit_id = c.business_unit_id");
    }

    public function down()
    {
        Schema::table('customer_contacts', function (Blueprint $table) {
            $table->dropForeign(['business_unit_id']);
            $table->dropColumn('business_unit_id');
        });
    }
};