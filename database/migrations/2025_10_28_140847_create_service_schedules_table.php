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
        Schema::create('service_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Örn: "Sabah Kargo Turu", "Öğleden Sonra Servisi"
            $table->time('departure_time'); // Sadece saat, örn: "10:00:00", "15:00:00"
            $table->integer('cutoff_minutes')->default(30); // Kalkıştan kaç dakika önce görev alımı durur

            // Bu sefere varsayılan olarak hangi aracın atandığı (A Aracı vb.)
            $table->foreignId('default_vehicle_id')
                ->nullable()
                ->constrained('vehicles') // 'vehicles' tablosuna bağlanır
                ->onDelete('set null'); // Araç silinirse bu alan null olur

            $table->boolean('is_active')->default(true); // Bu sefer aktif mi?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_schedules');
    }
};
