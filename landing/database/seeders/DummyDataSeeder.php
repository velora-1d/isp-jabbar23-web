<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat 5 Hantu Reseller (Mitra)
        $partners = [
            ['name' => 'Celluler Berkah', 'email' => 'berkah@cell.com', 'phone' => '08123456781', 'balance' => 500000],
            ['name' => 'Wartel Pak Haji', 'email' => 'pakhaji@net.com', 'phone' => '08123456782', 'balance' => 1200000],
            ['name' => 'Juragan Voucher', 'email' => 'juragan@wifi.com', 'phone' => '08123456783', 'balance' => 50000],
            ['name' => 'Koperasi Unit Desa', 'email' => 'kud@desa.id', 'phone' => '08123456784', 'balance' => 2000000],
            ['name' => 'Net Cafe 24Jam', 'email' => 'warnet@game.com', 'phone' => '08123456785', 'balance' => 150000],
        ];

        foreach ($partners as $p) {
            DB::table('partners')->insert([
                'name' => $p['name'],
                'email' => $p['email'],
                'phone' => $p['phone'],
                'balance' => $p['balance'],
                'commission_rate' => 10.0, // 10%
                'password_hash' => Hash::make('rahasia123'),
                'created_at' => Carbon::now(),
            ]);
        }

        // 2. Buat 10 Hantu Customer (Mapping ERP <-> Radius)
        $statuses = ['ACTIVE', 'ACTIVE', 'ACTIVE', 'SUSPENDED', 'ACTIVE'];
        
        for ($i = 1; $i <= 10; $i++) {
            DB::table('sync_mapping')->insert([
                'erp_customer_id' => 'CUST-2024-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'radius_username' => 'user_wifi_' . $i,
                'inventory_device_sn' => 'ZTE' . rand(100000, 999999),
                'status' => $statuses[rand(0, 4)],
                'last_synced_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]);
        }

        // 3. Buat User Admin Login (Buat Bapak Login nanti)
        DB::table('users')->insertOrIgnore([
            'name' => 'Super Admin ISP',
            'email' => 'admin@isp.com',
            'password' => Hash::make('admin123'),
            'created_at' => Carbon::now(),
        ]);
    }
}
