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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Etkinliği hangi kullanıcının eklediği
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('title'); // Örn: "ABC Firması Müşteri Ziyareti", "Siber Güvenlik Eğitimi"
            $table->text('description')->nullable(); // Etkinlik hakkında detaylar

            // Bu iki alan, hem "Hizmet" takvimi hem de "Genel KÖKSAN Takvimi" için kritik öneme sahip
            $table->dateTime('start_datetime'); // Etkinlik Başlangıç Tarihi ve Saati
            $table->dateTime('end_datetime');   // Etkinlik Bitiş Tarihi ve Saati

            $table->string('location')->nullable(); // Örn: "Toplantı Odası A", "İstanbul Fuar Merkezi"

            // Talebinizdeki (fuar, gezi, eğitim, toplantı, misafir) kategorilendirme için:
            $table->string('event_type');

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
        Schema::dropIfExists('events');
    }
};
