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
        Schema::create('opportunity_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable(); // İşlemi yapan kişi
            $table->string('old_stage')->nullable(); // Eski Aşama
            $table->string('new_stage'); // Yeni Aşama
            $table->text('note')->nullable(); // Opsiyonel not ("Fırsat oluşturuldu" vs)
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
        Schema::dropIfExists('opportunity_histories');
    }
};
