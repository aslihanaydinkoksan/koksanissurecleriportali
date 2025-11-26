<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaintenanceType;
use App\Models\MaintenanceAsset;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. BAKIM TÜRLERİ (Departman Alt Birimleri)
        // firstOrCreate kullanıyoruz ki seeder'ı yanlışlıkla 2 kere çalıştırırsan aynı veriyi tekrar yazmasın.
        $types = [
            ['name' => 'Elektronik Bakım', 'color_code' => '#3498db'], // Mavi
            ['name' => 'Mekanik Bakım', 'color_code' => '#e74c3c'],   // Kırmızı
            ['name' => 'Yardımcı İşletmeler', 'color_code' => '#2ecc71'], // Yeşil
        ];

        foreach ($types as $type) {
            MaintenanceType::firstOrCreate(['name' => $type['name']], $type);
        }

        // 2. VARLIKLAR (Makineler, Zonelar, Tesisat)
        // Category sütunu sayesinde hepsini tek tabloda ama ayrı kategorilerde tutuyoruz.
        $assets = [
            // MAKİNELER
            [
                'name' => 'CNC Lazer Kesim - 01',
                'category' => 'machine',
                'code' => 'CNC-LZR-01',
                'location' => 'Üretim Holü A',
                'description' => 'Ana üretim lazer kesim makinesi. 3kW güç.',
                'is_active' => true
            ],
            [
                'name' => 'Abkant Büküm - 03',
                'category' => 'machine',
                'code' => 'ABK-03',
                'location' => 'Büküm Hattı',
                'description' => 'Durmazlar marka abkant.',
                'is_active' => true
            ],
            [
                'name' => 'Vidalı Kompresör',
                'category' => 'machine',
                'code' => 'KOMP-01',
                'location' => 'Kompresör Odası',
                'description' => 'Tüm fabrikanın hava ihtiyacını karşılar.',
                'is_active' => true
            ],

            // ZONELAR (Bölgeler)
            [
                'name' => 'Yükleme Rampası A',
                'category' => 'zone',
                'code' => 'ZONE-A',
                'location' => 'Lojistik Depo',
                'description' => 'Tır yükleme alanı.',
                'is_active' => true
            ],
            [
                'name' => 'Yemekhane Binası',
                'category' => 'zone',
                'code' => 'ZONE-YMK',
                'location' => 'İdari Bina Yanı',
                'description' => 'Personel yemekhanesi ve mutfak.',
                'is_active' => true
            ],

            // TESİSAT / SİSTEMLER
            [
                'name' => 'Yangın Söndürme Sistemi',
                'category' => 'facility',
                'code' => 'FIRE-SYS',
                'location' => 'Tüm Fabrika',
                'description' => 'Sprinkler ve pompa dairesi dahil.',
                'is_active' => true
            ],
        ];

        foreach ($assets as $asset) {
            MaintenanceAsset::firstOrCreate(['code' => $asset['code']], $asset);
        }
    }
}
