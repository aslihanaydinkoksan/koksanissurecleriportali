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
        Schema::create('team_user', function (Blueprint $table) {
            $table->id(); // Pivot tablolarda ID olması bazen yararlıdır
            $table->foreignId('team_id')
                ->constrained('teams')
                ->onDelete('cascade'); // Takım silinirse üyelikler de silinsin
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Kullanıcı silinirse üyelikler de silinsin
            $table->timestamps(); // Ne zaman eklendiğini takip için

            // Aynı kullanıcı aynı takıma birden fazla eklenemesin
            $table->unique(['team_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_user');
    }
};