<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations. ANA TABLO, TÜM OPERASYONUN DÖNDÜĞÜ YER
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_plans', function (Blueprint $table) {
            $table->id();

            // İlişkiler
            $table->foreignId('user_id')->constrained(); // Planı açan kişi
            $table->foreignId('maintenance_type_id')->constrained('maintenance_types'); // Hangi birim?
            $table->foreignId('maintenance_asset_id')->constrained('maintenance_assets'); // Hangi makine?

            // Detaylar
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, critical

            // Durum (pending, in_progress, completed, cancelled)
            $table->string('status')->default('pending');

            // Planlanan Tarihler
            $table->dateTime('planned_start_date');
            $table->dateTime('planned_end_date');

            // Gerçekleşen Tarihler (Gecikme analizi için)
            $table->dateTime('actual_start_date')->nullable();
            $table->dateTime('actual_end_date')->nullable();

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
        Schema::dropIfExists('maintenance_plans');
    }
};
