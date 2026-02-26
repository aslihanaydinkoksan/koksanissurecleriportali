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
        Schema::create('vehicle_assignment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_assignment_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable(); // İşlemi yapan kişi
            $table->string('old_status')->nullable(); // Eski Durum
            $table->string('new_status'); // Yeni Durum
            $table->text('note')->nullable(); // Opsiyonel not ("Görev oluşturuldu", "Yola çıktı" vb.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_assignment_histories');
    }
};
