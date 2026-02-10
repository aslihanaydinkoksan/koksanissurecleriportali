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
        Schema::table('vehicle_assignments', function (Blueprint $table) {

            // 1. "Görev Sepeti" (Türü)
            // 'company_vehicle', 'logistics', 'personal'
            $table->string('assignment_type')->default('company_vehicle')->after('id');

            // 2. "Sorumlu" (Polimorfik)
            // responsible_id (User ID veya Team ID)
            // responsible_type (App\Models\User veya App\Models\Team)
            // 'id'den sonra ekleyelim ki mantıksal dursun
            $table->morphs('responsible', 'idx_responsible_assignment');

            // 3. "Kaynak" (Polimorfik)
            // resource_id (Vehicle ID veya null)
            // resource_type (App\Models\Vehicle veya null)
            $table->nullableMorphs('resource', 'idx_resource_assignment');

            // 4. "Nakliye Görevi" için özel alanlar
            $table->decimal('start_km', 10, 2)->nullable()->after('notes');
            $table->string('start_fuel_level')->nullable()->after('start_km');
            $table->decimal('fuel_cost', 10, 2)->nullable()->after('start_fuel_level');

            // 5. Mevcut sütunları yeni sisteme uyumlu hale getirme

            // Talep edeni ID olarak tutmak daha sağlıklıdır. 
            // 'users' tablonuz olduğu için bunu ekliyoruz.
            $table->foreignId('created_by_user_id')
                ->nullable()
                ->after('id')
                ->constrained('users');

            // 'requester_name' artık zorunlu olmayabilir, çünkü ID'yi tutuyoruz
            $table->string('requester_name')->nullable()->change();

            // Mevcut 'vehicle_id' sütunu artık 'resource' morfu ile yönetilecek.
            // Yeni 'kişisel' görevlerde bu sütun boş olacak, bu yüzden 'nullable' yapıyoruz.
            // (Eğer zaten 'nullable' değilse)
            $table->foreignId('vehicle_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            // Geri alma işlemleri (migration'ı geri almak isterseniz)
            $table->dropColumn('assignment_type');
            $table->dropMorphs('responsible', 'idx_responsible_assignment');
            $table->dropMorphs('resource', 'idx_resource_assignment');
            $table->dropColumn(['start_km', 'start_fuel_level', 'fuel_cost']);
            $table->dropForeign(['created_by_user_id']);
            $table->dropColumn('created_by_user_id');
            // 'requester_name' ve 'vehicle_id'yi eski zorunlu hallerine
            // (eğer öyleyse) döndürmek için 'change' metotları eklenebilir.
        });
    }
};
