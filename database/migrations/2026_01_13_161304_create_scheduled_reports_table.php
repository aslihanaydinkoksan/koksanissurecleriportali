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
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');  // Raporun adı (Örn: Haftalık Lojistik)
            $table->string('report_class'); // Arka planda çalışacak PHP sınıfı
            $table->string('frequency');    // daily, weekly, monthly
            $table->time('send_time');      // Gönderim saati (08:30)
            $table->json('recipients');     // Alıcılar: ["admin@koksan.com", "müdür@koksan.com"]
            $table->string('file_format');  // excel, pdf
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sent_at')->nullable();
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
        Schema::dropIfExists('scheduled_reports');
    }
};
