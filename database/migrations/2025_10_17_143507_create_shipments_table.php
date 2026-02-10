<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            // Bu kaydı hangi kullanıcının oluşturduğunu bilmek için.
            // `users` tablosundaki id'ye bağlanır.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('arac_tipi'); // tır, gemi, kamyon vb.
            $table->string('plaka')->nullable(); // Gemi gibi araçların plakası olmayabilir
            $table->string('dorse_plakasi')->nullable(); // Sadece tır için
            $table->string('sofor_adi')->nullable();
            $table->string('kargo_icerigi');
            $table->string('kargo_tipi');
            $table->string('kargo_miktari');
            $table->dateTime('cikis_tarihi');
            $table->dateTime('tahmini_varis_tarihi');

            // Gemi için limanlar gibi araca özel, dinamik verileri esnek bir şekilde saklamak için
            $table->json('ekstra_bilgiler')->nullable();

            $table->text('aciklamalar')->nullable();

            $table->timestamps(); // created_at ve updated_at sütunlarını oluşturur
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
};
