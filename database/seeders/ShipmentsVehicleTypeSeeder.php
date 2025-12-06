<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShipmentsVehicleType;

class ShipmentsVehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Tır',
            'Kamyon',
            'Gemi',
            'Kamyonet'
        ];
        foreach ($types as $type) {
            ShipmentsVehicleType::firstOrCreate(['name' => $type]);
        }
    }
}
