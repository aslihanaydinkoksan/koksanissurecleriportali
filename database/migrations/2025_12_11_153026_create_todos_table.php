<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Sahibi

            // İşletme Birimi (Fabrika) Bağlantısı - Kritik!
            $table->foreignId('business_unit_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('title'); // Görev Başlığı
            $table->text('description')->nullable(); // Detay
            $table->dateTime('due_date')->nullable(); // Son Tarih (Varsa takvimde görünür)
            $table->boolean('is_completed')->default(false); // Tamamlandı mı?
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // Öncelik

            $table->softDeletes();
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
        Schema::dropIfExists('todos');
    }
};
