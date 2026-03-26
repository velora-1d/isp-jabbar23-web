<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions
        $permissions = [
            // Dashboard
            'view dashboard',
            'view stats',

            // CRM (Leads & Customers)
            'view leads',
            'create leads',
            'edit leads',
            'delete leads',
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            'view contracts',
            'create contracts',
            'edit contracts',
            'delete contracts',
            'view partners',
            'create partners',
            'edit partners',
            'delete partners',

            // Finance (Invoices & Payments)
            'view financial reports',
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',
            'view payments',
            'create payments',
            'verify payments',
            'view recurring billing',
            'create recurring billing',
            'edit recurring billing',
            'view proforma',
            'create proforma',
            'edit proforma',
            'view credit notes',
            'create credit notes',
            'edit credit notes',
            'manage payment gateways',

            // Network
            'view network monitoring',
            'view olts',
            'create olts',
            'edit olts',
            'delete olts',
            'view odps',
            'create odps',
            'edit odps',
            'delete odps',
            'view routers',
            'create routers',
            'edit routers',
            'delete routers',
            'view ipam',
            'manage ipam',
            'view bandwidth',
            'manage bandwidth',
            'view topology',

            // Support
            'view tickets',
            'create tickets',
            'edit tickets',
            'delete tickets',
            'view messages',
            'send messages',
            'view knowledge base',
            'manage knowledge base',
            'view sla',
            'manage sla',

            // Field Operations
            'view technicians',
            'manage technicians',
            'view work orders',
            'create work orders',
            'edit work orders',
            'update work order status',
            'view schedule',
            'manage schedule',
            'view gps tracking',
            'view installation reports',
            'create installation reports',

            // Inventory
            'view inventory',
            'create items',
            'adjust stock',
            'view stock movements',
            'view assets',
            'create assets',
            'edit assets',
            'delete assets',
            'view vendors',
            'create vendors',
            'edit vendors',
            'delete vendors',
            'view purchase orders',
            'create purchase orders',
            'edit purchase orders',
            'approve purchase orders',

            // HRD
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'view attendance',
            'manage attendance',
            'view payroll',
            'manage payroll',
            'view leave',
            'manage leave',

            // Marketing
            'view campaigns',
            'create campaigns',
            'edit campaigns',
            'delete campaigns',
            'view promotions',
            'create promotions',
            'edit promotions',
            'delete promotions',
            'view referrals',
            'manage referrals',

            // Settings & Admin
            'view packages',
            'create packages',
            'edit packages',
            'delete packages',
            'view settings',
            'manage settings',
            'view users',
            'create users',
            'edit users',
            'manage roles',
            'view audit logs',
            'manage backup',
            'manage api',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Define Roles & Assign Permissions

        // A. SUPER ADMIN (God Mode) - All permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // B. ADMIN (Operational Manager) - Almost all except system-level
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::whereNotIn('name', [
            'manage backup',
            'manage api',
            'view audit logs',
            'manage settings'
        ])->get());

        // C. SALES (Sales & CS)
        $sales = Role::firstOrCreate(['name' => 'sales-cs']);
        $sales->givePermissionTo([
            'view dashboard',
            'view stats',
            // CRM
            'view leads',
            'create leads',
            'edit leads',
            'view customers',
            'create customers',
            'edit customers',
            'view contracts',
            'create contracts',
            'edit contracts',
            'view partners',
            'create partners',
            'edit partners',
            // Support
            'view tickets',
            'create tickets',
            'edit tickets',
            'view messages',
            'send messages',
            'view knowledge base',
            // Marketing
            'view campaigns',
            'create campaigns',
            'edit campaigns',
            'view promotions',
            'create promotions',
            'edit promotions',
            'view referrals',
            'manage referrals',
            // Packages (view only)
            'view packages',
        ]);

        // D. FINANCE (Keuangan)
        $finance = Role::firstOrCreate(['name' => 'finance']);
        $finance->givePermissionTo([
            'view dashboard',
            'view stats',
            // Billing
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',
            'view payments',
            'create payments',
            'verify payments',
            'view recurring billing',
            'create recurring billing',
            'edit recurring billing',
            'view proforma',
            'create proforma',
            'edit proforma',
            'view credit notes',
            'create credit notes',
            'edit credit notes',
            'view financial reports',
            'manage payment gateways',
            // View customers for billing context
            'view customers',
            // Payroll
            'view payroll',
            'manage payroll',
            // Purchase Orders (approval)
            'view purchase orders',
            'approve purchase orders',
            // Packages
            'view packages',
        ]);

        // E. NOC (Network Operations Center)
        $noc = Role::firstOrCreate(['name' => 'noc']);
        $noc->givePermissionTo([
            'view dashboard',
            'view stats',
            // Network (all)
            'view network monitoring',
            'view olts',
            'create olts',
            'edit olts',
            'delete olts',
            'view odps',
            'create odps',
            'edit odps',
            'delete odps',
            'view routers',
            'create routers',
            'edit routers',
            'delete routers',
            'view ipam',
            'manage ipam',
            'view bandwidth',
            'manage bandwidth',
            'view topology',
            // Field Ops (Supervising)
            'view technicians',
            'manage technicians',
            'view work orders',
            'create work orders',
            'edit work orders',
            'update work order status',
            'view schedule',
            'manage schedule',
            'view gps tracking',
            'view installation reports',
            'create installation reports',
            // Support
            'view tickets',
            'create tickets',
            'edit tickets',
            'view knowledge base',
            'manage knowledge base',
            'view sla',
            'manage sla',
            // View customers for context
            'view customers',
        ]);

        // F. TECHNICIAN (Teknisi Lapangan)
        $technician = Role::firstOrCreate(['name' => 'technician']);
        $technician->givePermissionTo([
            'view dashboard',
            'view customers',
            'view odps', // Need to see ODP locations
            'view topology',
            'view tickets',
            'edit tickets', // Update ticket status
            'view knowledge base',
            'view work orders',
            'update work order status', // Can update their specific WOs
            'view schedule',
            'view installation reports',
            'create installation reports',
        ]);

        // G. WAREHOUSE (Admin Gudang)
        $warehouse = Role::firstOrCreate(['name' => 'warehouse']);
        $warehouse->givePermissionTo([
            'view dashboard',
            // Inventory (all)
            'view inventory',
            'create items',
            'adjust stock',
            'view stock movements',
            'view assets',
            'create assets',
            'edit assets',
            'delete assets',
            'view vendors',
            'create vendors',
            'edit vendors',
            'delete vendors',
            'view purchase orders',
            'create purchase orders',
            'edit purchase orders',
        ]);

        // H. HRD (Human Resources)
        $hrd = Role::firstOrCreate(['name' => 'hrd']);
        $hrd->givePermissionTo([
            'view dashboard',
            // HRD (all)
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'view attendance',
            'manage attendance',
            'view payroll',
            'manage payroll',
            'view leave',
            'manage leave',
            // View users for employee context
            'view users',
        ]);

        // I. RESELLER (Mitra)
        $reseller = Role::firstOrCreate(['name' => 'reseller']);
        $reseller->givePermissionTo([
            'view dashboard',
            'view customers', // Only their own customers (handled by query scope)
            'view invoices', // Only their own invoices
            'view packages',
            'view leads',
            'create leads',
        ]);

        // 3. Create Demo Users
        $demoUsers = [
            ['email' => 'super@isp.local', 'name' => 'Super Admin', 'role' => 'super-admin'],
            ['email' => 'admin@isp.local', 'name' => 'Admin Manager', 'role' => 'admin'],
            ['email' => 'sales@isp.local', 'name' => 'Sales Staff', 'role' => 'sales-cs'],
            ['email' => 'finance@isp.local', 'name' => 'Finance Staff', 'role' => 'finance'],
            ['email' => 'noc@isp.local', 'name' => 'NOC Admin', 'role' => 'noc'],
            ['email' => 'tech@isp.local', 'name' => 'Field Technician', 'role' => 'technician'],
            ['email' => 'warehouse@isp.local', 'name' => 'Warehouse Staff', 'role' => 'warehouse'],
            ['email' => 'hrd@isp.local', 'name' => 'HRD Manager', 'role' => 'hrd'],
        ];

        foreach ($demoUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                ]
            );
            $user->syncRoles([$userData['role']]);
        }
    }
}
