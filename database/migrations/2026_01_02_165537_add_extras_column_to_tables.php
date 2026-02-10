<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $tables = [
        'meetings',           // Toplantılar
        'shipments',          // Lojistik / Sevkiyatlar
        'events',             // Takvim Etkinlikleri
        'vehicles',           // Araçlar
        'maintenance_plans',  // Bakım Planları
        'production_plans',   // Üretim Planları
        'users',              // Belki personele de ek alan istersin (Kan grubu vb.)
    ];

    public function up()
    {
        foreach ($this->tables as $tableName) {
            // Tablo veritabanında var mı diye kontrol edelim (Hata almamak için)
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    // Eğer daha önce eklenmediyse 'extras' sütununu ekle
                    if (!Schema::hasColumn($table->getTable(), 'extras')) {
                        $table->json('extras')->nullable()->after('updated_at');
                    }
                });
            }
        }
    }

    public function down()
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'extras')) {
                        $table->dropColumn('extras');
                    }
                });
            }
        }
    }
};
