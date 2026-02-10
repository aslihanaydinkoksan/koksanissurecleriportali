<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            // Polymorphic ilişki: Hem Travel, hem Event, hem de başka modüllere bağlanabilir.
            $table->morphs('expensable');

            $table->string('category'); // Ulaşım, Konaklama, Yemek vb.
            $table->decimal('amount', 10, 2); // Tutar
            $table->string('currency', 3)->default('TRY'); // Para Birimi
            $table->string('description')->nullable(); // Açıklama
            $table->date('receipt_date')->nullable(); // Fiş Tarihi

            $table->unsignedBigInteger('created_by')->nullable(); // Ekleyen kullanıcı
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
