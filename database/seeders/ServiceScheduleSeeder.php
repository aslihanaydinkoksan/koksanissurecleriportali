<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceSchedule;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class ServiceScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_schedules')->truncate();
        $defaultVehicle = Vehicle::where('is_active', true)->first();

        ServiceSchedule::firstOrCreate(
            ['name' => 'Sabah Kargo Turu'],
            [
                'departure_time' => '09:00:00',
                'cutoff_minutes' => 30, // 09:30 son
                'default_vehicle_id' => $defaultVehicle?->id
            ]
        );

        ServiceSchedule::firstOrCreate(
            ['name' => 'Öğleden Sonra Kargo Turu'],
            [
                'departure_time' => '13:00:00',
                'cutoff_minutes' => 30, // 13:30 son
                'default_vehicle_id' => $defaultVehicle?->id
            ]
        );
    }
}
