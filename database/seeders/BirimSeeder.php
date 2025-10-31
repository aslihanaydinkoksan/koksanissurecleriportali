<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
  use App\Models\Birim;

class BirimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Birim::create(['ad' => 'Ton']);
        Birim::create(['ad' => 'Adet']);
        Birim::create(['ad' => 'Palet']);
        Birim::create(['ad' => 'Koli']);
        Birim::create(['ad' => 'Litre']);
    }
}
