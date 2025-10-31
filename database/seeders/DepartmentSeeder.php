<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $departments = [
            'Lojistik',
            'Üretim',
            'Hizmet'
        ];

        foreach ($departments as $departmentName) {
            Department::firstOrCreate([
                'slug' => Str::slug($departmentName)
            ], [
                'name' => $departmentName
            ]);
        }
    }
}
