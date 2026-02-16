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
        Schema::table('customer_visits', function (Blueprint $table) {
            // event_id boş bırakılabilsin
            $table->unsignedBigInteger('event_id')->nullable()->change();
            
            // travel_id boş bırakılabilsin (Eğer tablo yapında varsa)
            if (Schema::hasColumn('customer_visits', 'travel_id')) {
                $table->unsignedBigInteger('travel_id')->nullable()->change();
            }

            // Eski 'visit_purpose' alanı boş bırakılabilsin (Biz artık visit_reason kullanıyoruz)
            if (Schema::hasColumn('customer_visits', 'visit_purpose')) {
                $table->string('visit_purpose')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_visits', function (Blueprint $table) {
            //
        });
    }
};
