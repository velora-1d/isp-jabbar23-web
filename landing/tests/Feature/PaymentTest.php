<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create permissions
        Permission::create(['name' => 'view payments']);
        Permission::create(['name' => 'create payments']);
        Permission::create(['name' => 'verify payments']);
        Permission::create(['name' => 'view invoices']);

        // Create admin role with all permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['view payments', 'create payments', 'verify payments', 'view invoices']);

        // Create user with admin role
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
    }

    public function test_manual_payment_is_recorded_correctly(): void
    {
        $invoice = Invoice::factory()->unpaid()->create(['amount' => 300000]);

        $paymentData = [
            'invoice_id' => $invoice->id,
            'amount' => 300000,
            'payment_method' => 'cash',
            'reference_number' => 'REF-12345',
            'notes' => 'Test payment',
        ];

        $response = $this->actingAs($this->user)->post(route('payments.store'), $paymentData);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 300000,
            'payment_method' => 'cash',
            'status' => 'confirmed',
        ]);
    }

    public function test_full_payment_marks_invoice_as_paid(): void
    {
        $invoice = Invoice::factory()->unpaid()->create(['amount' => 300000]);

        $paymentData = [
            'invoice_id' => $invoice->id,
            'amount' => 300000, // Full amount
            'payment_method' => 'bank_transfer',
        ];

        $response = $this->actingAs($this->user)
            ->withoutExceptionHandling()
            ->post(route('payments.store'), $paymentData);

        $response->assertRedirect();

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
    }

    public function test_partial_payment_marks_invoice_as_partial(): void
    {
        $invoice = Invoice::factory()->unpaid()->create(['amount' => 300000]);

        $paymentData = [
            'invoice_id' => $invoice->id,
            'amount' => 150000, // Partial amount
            'payment_method' => 'cash',
        ];

        $response = $this->actingAs($this->user)
            ->withoutExceptionHandling()
            ->post(route('payments.store'), $paymentData);

        $response->assertRedirect();

        $invoice->refresh();
        $this->assertEquals('partial', $invoice->status);
    }

    public function test_payment_validation_rules(): void
    {
        // Test with invalid data (missing required fields)
        $response = $this->actingAs($this->user)->post(route('payments.store'), []);

        $response->assertSessionHasErrors(['invoice_id', 'amount', 'payment_method']);
    }
}
