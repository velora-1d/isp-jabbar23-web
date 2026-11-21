<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NetworkSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Core Router
        DB::table('routers')->insertOrIgnore([
            'name' => 'MikroTik CCR-1036 (Core)',
            'ip_address' => '192.168.88.1',
            'port' => 8728,
            'username' => 'admin',
            'password' => 'secret',
            'status' => 'online',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Seed OLTs
        DB::table('olts')->insertOrIgnore([
            'name' => 'ZTE C320 - Headend A',
            'ip_address' => '10.10.10.2',
            'total_pon_ports' => 8,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('olts')->insertOrIgnore([
            'name' => 'Huawei MA5608T - Headend B',
            'ip_address' => '10.10.10.3',
            'total_pon_ports' => 16,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 3. Seed ODPs
        $odps = [
            ['name' => 'ODP-JABBAR-01', 'total_ports' => 8],
            ['name' => 'ODP-JABBAR-02', 'total_ports' => 8],
            ['name' => 'ODP-KOTA-01', 'total_ports' => 16],
            ['name' => 'ODP-KOTA-02', 'total_ports' => 16],
        ];

        foreach ($odps as $odp) {
            DB::table('odps')->insertOrIgnore([
                'name' => $odp['name'],
                'total_ports' => $odp['total_ports'],
                'status' => 'active',
                'latitude' => '-6.200000',
                'longitude' => '106.816666',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
