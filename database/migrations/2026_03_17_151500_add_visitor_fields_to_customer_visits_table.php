<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            $table->foreignId('visitor_id')->nullable()->constrained('users')->after('user_id');
            $table->string('visitor_name')->nullable()->after('visitor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            $table->dropForeign(['visitor_id']);
            $table->dropColumn(['visitor_id', 'visitor_name']);
        });
    }
};
