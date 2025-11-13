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
        Schema::table('events', function (Blueprint $table) {
            // 1. customer_id sütunu yoksa ekle
            if (!Schema::hasColumn('events', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            }

            // 2. customer_machine_id sütunu yoksa ekle
            // DİKKAT: Veritabanınızdaki tablo adı 'customer_machines' mi yoksa 'customermachines' mi? 
            // Laravel standartlarına göre çoğul olur. Eğer hata alırsanız burayı kontrol edin.
            if (!Schema::hasColumn('events', 'customer_machine_id')) {
                // Tablo adı 'customer_machines' olarak varsayıldı. 
                // Eğer sizin tablo adınız farklıysa (örn: customer_machine) aşağıdaki 'customer_machines' kısmını düzeltin.
                $table->foreignId('customer_machine_id')->nullable()->constrained('customer_machines')->onDelete('set null');
            }

            // 3. visit_status sütunu yoksa ekle
            if (!Schema::hasColumn('events', 'visit_status')) {
                $table->string('visit_status')->default('planlandi')->after('event_type');
            }

            // 4. cancellation_reason sütunu yoksa ekle
            if (!Schema::hasColumn('events', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('visit_status');
            }

            // 5. visit_purpose sütunu yoksa ekle
            if (!Schema::hasColumn('events', 'visit_purpose')) {
                $table->string('visit_purpose')->nullable();
            }

            // 6. after_sales_notes sütunu yoksa ekle
            if (!Schema::hasColumn('events', 'after_sales_notes')) {
                $table->text('after_sales_notes')->nullable();
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
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['customer_machine_id']);
            $table->dropColumn(['customer_id', 'customer_machine_id', 'visit_status', 'cancellation_reason', 'visit_purpose', 'after_sales_notes']);
        });
    }
};
