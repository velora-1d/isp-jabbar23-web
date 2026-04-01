<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'name' => '10 Mbps Home',
                'speed_up' => 10,
                'speed_down' => 10,
                'price' => 150000,
                'description' => 'Paket hemat untuk keluarga kecil.',
                'is_active' => true,
            ],
            [
                'name' => '20 Mbps Streamer',
                'speed_up' => 20,
                'speed_down' => 20,
                'price' => 250000,
                'description' => 'Pas untuk streaming 4K tanpa buffering.',
                'is_active' => true,
            ],
            [
                'name' => '50 Mbps Pro',
                'speed_up' => 50,
                'speed_down' => 50,
                'price' => 450000,
                'description' => 'Solusi bisnis dan gaming profesional.',
                'is_active' => true,
            ],
            [
                'name' => '100 Mbps Ultimate',
                'speed_up' => 100,
                'speed_down' => 100,
                'price' => 750000,
                'description' => 'Kecepatan maksimal untuk seluruh perangkat.',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkg) {
            DB::table('packages')->insertOrIgnore(array_merge($pkg, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }
    }
}
