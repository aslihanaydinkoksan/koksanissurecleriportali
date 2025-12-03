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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('bookable_id')->nullable()->after('id');
            $table->string('bookable_type')->nullable()->after('bookable_id');
        });
        \DB::table('bookings')->whereNotNull('travel_id')->update([
            'bookable_id' => \DB::raw('travel_id'),
            'bookable_type' => 'App\Models\Travel'
        ]);

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['travel_id']);
            $table->dropColumn('travel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('travel_id')->nullable();
            $table->foreign('travel_id')->references('id')->on('travels')->onDelete('cascade');
        });

        \DB::table('bookings')->where('bookable_type', 'App\Models\Travel')->update([
            'travel_id' => \DB::raw('bookable_id')
        ]);

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['bookable_id', 'bookable_type']);
        });
    }
};
