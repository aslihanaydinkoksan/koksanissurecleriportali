<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Önce Foreign Key kısıtlamasını kaldırıyoruz
            if (Schema::hasColumn('users', 'department_id')) {
                // Laravel'in standart isimlendirme kuralı: tablo_sutun_foreign
                $table->dropForeign(['department_id']);
            }

            // 2. Şimdi sütunları güvenle silebiliriz
            if (Schema::hasColumn('users', 'department_id')) {
                $table->dropColumn('department_id');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();

            // Geri alırken foreign key'i tekrar ekliyoruz
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }
};