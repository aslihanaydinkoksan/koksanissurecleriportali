<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(); // İşlemi yapan admin/personel

            $table->string('action'); // create, update, delete, soft_delete, restore

            // Polimorfik İlişki: Hangi kayıtta değişiklik oldu?
            // loggable_id: 5, loggable_type: App\Models\Location
            $table->morphs('loggable');

            // Eski ve Yeni veriyi JSON tutuyoruz (Önemli!)
            // Örn: Eski kapasite 3, yeni kapasite 4
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->ipAddress('ip_address')->nullable(); // Güvenlik için IP takibi
            $table->string('user_agent')->nullable(); // Tarayıcı bilgisi

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
