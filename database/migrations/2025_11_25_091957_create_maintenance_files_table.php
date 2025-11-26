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
        Schema::create('maintenance_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_plan_id')->constrained('maintenance_plans')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name'); // Orijinal ad
            $table->string('file_type'); // pdf, jpg
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
        Schema::dropIfExists('maintenance_files');
    }
};
