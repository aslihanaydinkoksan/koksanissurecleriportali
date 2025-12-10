<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Ownership sütunundan hemen sonra ekleyelim
            $table->string('landlord_name')->nullable()->after('ownership');
            $table->string('landlord_phone')->nullable()->after('landlord_name');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['landlord_name', 'landlord_phone']);
        });
    }
};
