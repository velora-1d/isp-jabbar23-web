<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TestUsersSeeder extends Seeder
{
    /**
     * Create test users for each role.
     */
    public function run(): void
    {
        // Sales & CS User
        $sales = User::firstOrCreate(
            ['email' => 'sales@isp.com'],
            [
                'name' => 'Sales User',
                'password' => bcrypt('password'),
            ]
        );
        $sales->syncRoles(['Sales & CS']);
        $this->command->info('Created: sales@isp.com (password: password)');

        // Finance User
        $finance = User::firstOrCreate(
            ['email' => 'finance@isp.com'],
            [
                'name' => 'Finance User',
                'password' => bcrypt('password'),
            ]
        );
        $finance->syncRoles(['Finance']);
        $this->command->info('Created: finance@isp.com (password: password)');

        // Technician User
        $tech = User::firstOrCreate(
            ['email' => 'teknisi@isp.com'],
            [
                'name' => 'Teknisi Lapangan',
                'password' => bcrypt('password'),
            ]
        );
        $tech->syncRoles(['Teknisi Lapangan']);
        $this->command->info('Created: teknisi@isp.com (password: password)');
    }
}
