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
        Schema::create('maintenance_time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_plan_id')->constrained('maintenance_plans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Çalışan teknisyen

            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable(); // Null ise şu an çalışıyor

            $table->integer('duration_minutes')->default(0);
            $table->text('note')->nullable();

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
        Schema::dropIfExists('maintenance_time_entries');
    }
};
