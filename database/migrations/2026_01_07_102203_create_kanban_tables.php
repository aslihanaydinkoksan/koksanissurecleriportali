<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. KANBAN PANOLARI (BOARDS)
        // Hangi fabrika (business_unit) hangi modül için pano kullanıyor?
        Schema::create('kanban_boards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_unit_id'); // Fabrika/Birim ID (Senin yapına uygun)
            $table->string('name'); // Örn: "Kopet Lojistik Panosu"

            // Hangi modüle ait olduğunu belirten scope (Kod tarafında switch için)
            // Değerler: 'maintenance', 'logistics', 'production', 'admin'
            $table->string('module_scope');

            $table->timestamps();

            // Bir fabrikada, bir modülün sadece bir panosu olabilir (Opsiyonel kısıt)
            $table->unique(['business_unit_id', 'module_scope'], 'unique_board_per_unit');
        });

        // 2. KANBAN SÜTUNLARI (COLUMNS)
        // Bekleyen, İşlemde, Onaylandı vb.
        Schema::create('kanban_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('kanban_boards')->onDelete('cascade');
            $table->string('title'); // Ekranda görünen isim
            $table->string('slug'); // Kod tarafında yakalamak için (örn: 'waiting', 'processing')
            $table->integer('order_index')->default(0); // Sütunların yan yana sıralaması
            $table->string('color_class')->nullable()->default('bg-gray-100'); // UI Renkleri
            $table->boolean('is_default')->default(false); // Yeni kartlar buraya düşsün
            $table->timestamps();
        });

        // 3. KANBAN KARTLARI (CARDS) - POLIMORFIK YAPI
        // Mevcut tabloların (shipments, maintenance_plans vs.) Kanban'daki izdüşümü
        Schema::create('kanban_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('column_id')->constrained('kanban_columns')->onDelete('cascade');

            // SİHİRLİ KISIM: Polymorphic İlişki
            // model_type -> 'App\Models\Shipment', 'App\Models\MaintenancePlan' vb.
            // model_id   -> İlgili tablonun ID'si
            $table->morphs('model');

            $table->integer('sort_order')->default(0); // Sütun içindeki yukarı-aşağı sıralaması
            $table->timestamps();

            // Performans için Indexler
            // Bir sütundaki kartları hızlı çekmek için:
            $table->index(['column_id', 'sort_order']);
            // Bir modüldeki (örn: shipment #15) kartı hızlı bulmak için composite index zaten morphs ile gelir.
        });
    }

    public function down()
    {
        Schema::dropIfExists('kanban_cards');
        Schema::dropIfExists('kanban_columns');
        Schema::dropIfExists('kanban_boards');
    }
};