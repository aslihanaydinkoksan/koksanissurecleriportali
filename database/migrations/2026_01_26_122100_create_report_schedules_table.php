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
        Schema::create('report_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');   // Raporun Adı (Örn: Haftalık Doluluk Raporu)
            $table->string('type');          // Rapor Türü (stays, assets, logs vb.)
            $table->string('frequency');     // daily, weekly, monthly
            $table->json('recipients');      // Alıcılar (Array olarak tutacağız)
            $table->string('scope');         // günlük, haftalık, aylık, son 3 ay vb.
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();           // Senin mimarindeki SoftDeletes standardı
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_schedules');
    }
};
