<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('kanban_boards', function (Blueprint $table) {
            // Hata veren eski kısıtlamayı (index) siliyoruz
            $table->dropUnique('unique_board_per_unit');

            // İsteğe bağlı: Eğer bir kullanıcının aynı fabrikada aynı isimle 
            // iki pano açmasını engellemek isterseniz yeni kural ekleyebilirsiniz:
            $table->unique(['user_id', 'business_unit_id', 'name'], 'unique_user_board_name');
        });
    }

    public function down()
    {
        Schema::table('kanban_boards', function (Blueprint $table) {
            // Geri alma durumunda eski kısıtlamayı tekrar ekle
            $table->unique(['business_unit_id', 'module_scope'], 'unique_board_per_unit');
        });
    }
};
