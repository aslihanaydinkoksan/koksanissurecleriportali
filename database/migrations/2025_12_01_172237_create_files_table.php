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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            // morphs: Hangi modele ait olduğunu tutar (model_type + model_id)
            $table->morphs('fileable');
            $table->string('path'); // Dosya yolu
            $table->string('original_name'); // Dosyanın orijinal adı (fatura.pdf)
            $table->string('mime_type')->nullable(); // pdf, jpg, png vs.
            $table->unsignedBigInteger('uploaded_by'); // Yükleyen kişi
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
        Schema::dropIfExists('files');
    }
};
