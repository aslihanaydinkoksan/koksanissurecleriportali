<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('kanban_boards', function (Blueprint $table) {
            // Pano sahibini belirliyoruz. 
            // nullable() yapıyoruz çünkü sistemde "Genel" panolar da kalabilir (Adminlerin oluşturduğu).
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('kanban_boards', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
