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
        Schema::create('vehicle_assignments', function (Blueprint $table) {
            $table->id();

            // ========== GÖREV TİPİ ==========
            $table->enum('assignment_type', ['vehicle', 'general'])
                ->comment('vehicle: Araç gerektiren görev, general: Genel görev');

            // ========== ARAÇ BİLGİSİ (Sadece vehicle tipinde) ==========
            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained('vehicles')
                ->onDelete('cascade');
            $table->enum('vehicle_type', ['company', 'logistics'])
                ->nullable()
                ->comment('company: Şirket aracı, logistics: Nakliye aracı');

            // ========== SORUMLU BİLGİSİ (Polymorphic) ==========
            $table->enum('responsible_type', ['user', 'team'])
                ->comment('user: Tek kişi, team: Takım');
            $table->unsignedBigInteger('responsible_id')
                ->comment('User ID veya Team ID');

            // Index for polymorphic relation
            $table->index(['responsible_type', 'responsible_id']);

            // ========== GÖREV DETAYLARI ==========
            $table->string('title'); // Görev başlığı
            $table->text('task_description'); // Görevin açıklaması
            $table->string('destination')->nullable(); // Görev yeri
            $table->dateTime('start_time'); // Başlangıç zamanı
            $table->dateTime('end_time'); // Bitiş zamanı
            $table->text('notes')->nullable(); // Ek notlar

            // ========== GÖREV DURUMU ==========
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
                ->default('pending')
                ->comment('Görev durumu');

            // ========== NAKLİYE ÖZEL ALANLARI (Sadece logistics tipinde) ==========
            $table->decimal('initial_km', 10, 2)->nullable()->comment('Çıkış km');
            $table->decimal('final_km', 10, 2)->nullable()->comment('Dönüş km');
            $table->decimal('initial_fuel', 10, 2)->nullable()->comment('Çıkış yakıt miktarı (litre)');
            $table->decimal('final_fuel', 10, 2)->nullable()->comment('Dönüş yakıt miktarı (litre)');
            $table->decimal('fuel_cost', 10, 2)->nullable()->comment('Yakıt maliyeti (TL)');

            // ========== KİM OLUŞTURDU ==========
            $table->foreignId('created_by_user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Görevi kim oluşturdu');

            // ========== TALEP EDEN ==========
            $table->string('requester_name')->nullable()
                ->comment('Talebi yapan departman/kişi adı');
            $table->foreignId('requester_user_id')->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('Talebi yapan kullanıcı (varsa)');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_assignments');
    }
};