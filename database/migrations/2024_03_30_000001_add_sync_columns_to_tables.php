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
        if (Schema::hasTable('complaints')) {
            Schema::table('complaints', function (Blueprint $table) {
                if (!Schema::hasColumn('complaints', 'remote_creator_name')) {
                    $table->string('remote_creator_name')->nullable()->after('status');
                }
            });
        }

        if (Schema::hasTable('customer_returns')) {
            Schema::table('customer_returns', function (Blueprint $table) {
                if (!Schema::hasColumn('customer_returns', 'remote_url')) {
                    $table->string('remote_url')->nullable()->after('return_date');
                }
                if (!Schema::hasColumn('customer_returns', 'remote_id')) {
                    $table->string('remote_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('customer_returns', 'remote_system')) {
                    $table->string('remote_system')->nullable()->after('remote_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('remote_creator_name');
        });
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->dropColumn(['remote_url', 'remote_id', 'remote_system']);
        });
    }
};
