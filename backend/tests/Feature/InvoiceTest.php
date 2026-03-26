<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create permissions
        Permission::create(['name' => 'view invoices']);
        Permission::create(['name' => 'create invoices']);
        Permission::create(['name' => 'edit invoices']);
        Permission::create(['name' => 'delete invoices']);

        // Create admin role with all permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['view invoices', 'create invoices', 'edit invoices', 'delete invoices']);

        // Create user with admin role
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
    }

    public function test_user_can_view_invoice_list(): void
    {
        // Create test invoices
        Invoice::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('invoices.index'));

        $response->assertStatus(200);
        $response->assertViewIs('invoices.index');
        $response->assertViewHas('invoices');
    }

    public function test_user_can_create_invoice_for_customer(): void
    {
        $customer = Customer::factory()->create();

        $invoiceData = [
            'customer_id' => $customer->id,
            'period_start' => now()->startOfMonth()->format('Y-m-d'),
            'period_end' => now()->endOfMonth()->format('Y-m-d'),
            'due_date' => now()->endOfMonth()->addDays(7)->format('Y-m-d'),
            'amount' => 300000,
        ];

        $response = $this->actingAs($this->user)->post(route('invoices.store'), $invoiceData);

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', [
            'customer_id' => $customer->id,
            'amount' => 300000,
            'status' => 'unpaid',
        ]);
    }

    public function test_invoice_validation_rules(): void
    {
        // Test with invalid data (missing required fields)
        $response = $this->actingAs($this->user)->post(route('invoices.store'), []);

        $response->assertSessionHasErrors(['customer_id', 'period_start', 'period_end', 'due_date', 'amount']);
    }

    public function test_invoice_number_is_unique(): void
    {
        $customer = Customer::factory()->create();

        // Create first invoice
        $invoiceData = [
            'customer_id' => $customer->id,
            'period_start' => now()->startOfMonth()->format('Y-m-d'),
            'period_end' => now()->endOfMonth()->format('Y-m-d'),
            'due_date' => now()->endOfMonth()->addDays(7)->format('Y-m-d'),
            'amount' => 300000,
        ];

        $this->actingAs($this->user)->post(route('invoices.store'), $invoiceData);

        // Create second invoice
        $this->actingAs($this->user)->post(route('invoices.store'), $invoiceData);

        // Both should be created with unique invoice numbers
        $this->assertDatabaseCount('invoices', 2);

        $invoices = Invoice::all();
        $this->assertNotEquals($invoices[0]->invoice_number, $invoices[1]->invoice_number);
    }
}
