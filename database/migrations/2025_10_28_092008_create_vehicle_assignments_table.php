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
        Schema::create('vehicle_assignments', function (Blueprint $table) {
            $table->id();

            // Hangi araca atandığı
            $table->foreignId('vehicle_id')
                ->constrained('vehicles') // 'vehicles' tablosuna bağlanır
                ->onDelete('cascade'); // Araç silinirse atamaları da silinsin

            // Atamayı kimin (Hizmet birimi kullanıcısı) yaptığı
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('task_description'); // Görevin açıklaması (Örn: "Merkeze kargo götürme")
            $table->string('destination')->nullable(); // Görev yeri
            $table->string('requester_name')->nullable(); // Görevi kimin talep ettiği (Örn: "Pazarlama Departmanı")

            // Görevin başlangıç ve bitiş zamanı (Takvim görünümü için önemli)
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->text('notes')->nullable(); // Atama ile ilgili ek notlar

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
        Schema::dropIfExists('vehicle_assignments');
    }
};
