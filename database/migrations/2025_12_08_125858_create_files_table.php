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
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            // Dosya nereye ait? (Odaya mı, Arıza Kaydına mı, Personele mi?)
            $table->morphs('fileable');

            $table->string('filename'); // orjinal_isim.jpg
            $table->string('path'); // uploads/2024/05/uuid.jpg
            $table->string('mime_type'); // image/jpeg, application/pdf
            $table->bigInteger('size'); // Byte cinsinden boyut

            // Dosyanın amacı ne? (Örn: 'profil_foto', 'ariza_resmi', 'sozlesme_pdf')
            $table->string('collection')->default('default');

            $table->timestamps();
            $table->softDeletes(); // Dosyayı da yanlışlıkla silersek geri getirebilelim
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
