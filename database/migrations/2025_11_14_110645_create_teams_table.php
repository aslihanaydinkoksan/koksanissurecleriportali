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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable(); // Takım açıklaması
            $table->foreignId('created_by_user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Kullanıcı silinirse takımı da silinsin
            $table->boolean('is_active')->default(true); // Aktif/Pasif
            $table->timestamps();
            $table->softDeletes(); // Soft delete desteği
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
};