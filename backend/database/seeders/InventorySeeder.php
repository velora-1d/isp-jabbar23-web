<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryCategory;
use App\Models\Location;

class InventorySeeder extends Seeder
{
    public function run()
    {
        // 1. Lokasi
        $locations = [
            ['name' => 'Gudang Utama', 'type' => 'warehouse', 'address' => 'Kantor Pusat'],
            ['name' => 'Mobil Teknisi 1', 'type' => 'vehicle', 'address' => 'Unit Mobile 1'],
            ['name' => 'Mobil Teknisi 2', 'type' => 'vehicle', 'address' => 'Unit Mobile 2'],
            ['name' => 'Site Server A', 'type' => 'site', 'address' => 'Server Room Lt. 2'],
        ];

        foreach ($locations as $loc) {
            Location::firstOrCreate(['name' => $loc['name']], $loc);
        }

        // 2. Kategori
        $categories = [
            ['name' => 'Kabel FO', 'slug' => 'kabel-fo', 'description' => 'Kabel Fiber Optik berbagai core'],
            ['name' => 'Modem / ONU', 'slug' => 'modem-onu', 'description' => 'Perangkat ONU/ONT Customer'],
            ['name' => 'Router', 'slug' => 'router', 'description' => 'Mikrotik, Ubiquiti, Tenda, dll'],
            ['name' => 'Aksesoris Jaringan', 'slug' => 'aksesoris', 'description' => 'Adapter, Patch Cord, Splitter'],
            ['name' => 'Tools', 'slug' => 'tools', 'description' => 'Splicer, OPM, OLS, Tang'],
            ['name' => 'Consumables', 'slug' => 'consumables', 'description' => 'Lakban, Ties, Alkohol'],
        ];

        foreach ($categories as $cat) {
            InventoryCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
