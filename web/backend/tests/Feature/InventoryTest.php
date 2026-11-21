<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\Location;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create permissions
        Permission::create(['name' => 'view inventory']);
        Permission::create(['name' => 'create items']);
        Permission::create(['name' => 'adjust stock']);

        // Create admin role with all permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['view inventory', 'create items', 'adjust stock']);

        // Create user with admin role
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
    }

    public function test_user_can_view_inventory_list(): void
    {
        // Create test inventory items
        InventoryItem::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('inventory.index'));

        $response->assertStatus(200);
        $response->assertViewIs('inventory.index');
        $response->assertViewHas('items');
    }

    public function test_user_can_create_inventory_item(): void
    {
        $category = InventoryCategory::factory()->create();

        $itemData = [
            'name' => 'Kabel FO Test',
            'sku' => 'SKU-TEST-001',
            'category_id' => $category->id,
            'unit' => 'meter',
            'min_stock_alert' => 10,
            'purchase_price' => 5000,
            'selling_price' => 7500,
        ];

        $response = $this->actingAs($this->user)->post(route('inventory.store'), $itemData);

        $response->assertRedirect();
        $this->assertDatabaseHas('inventory_items', [
            'name' => 'Kabel FO Test',
            'sku' => 'SKU-TEST-001',
        ]);
    }

    public function test_stock_adjustment_increases_stock(): void
    {
        $item = InventoryItem::factory()->create();
        $location = Location::factory()->create();

        $adjustmentData = [
            'inventory_item_id' => $item->id,
            'location_id' => $location->id,
            'type' => 'in',
            'quantity' => 50,
            'notes' => 'Initial stock',
            'reference_number' => 'REF-TEST-001',
        ];

        $response = $this->actingAs($this->user)
            ->withoutExceptionHandling()
            ->post(route('inventory.adjust'), $adjustmentData);

        $response->assertRedirect();

        $stock = Stock::where('inventory_item_id', $item->id)
            ->where('location_id', $location->id)
            ->first();

        $this->assertEquals(50, $stock->quantity);
    }

    public function test_stock_adjustment_decreases_stock(): void
    {
        $item = InventoryItem::factory()->create();
        $location = Location::factory()->create();

        // Create initial stock
        Stock::create([
            'inventory_item_id' => $item->id,
            'location_id' => $location->id,
            'quantity' => 100,
        ]);

        $adjustmentData = [
            'inventory_item_id' => $item->id,
            'location_id' => $location->id,
            'type' => 'out',
            'quantity' => 30,
            'notes' => 'Used for installation',
            'reference_number' => 'REF-TEST-002',
        ];

        $response = $this->actingAs($this->user)
            ->withoutExceptionHandling()
            ->post(route('inventory.adjust'), $adjustmentData);

        $response->assertRedirect();

        $stock = Stock::where('inventory_item_id', $item->id)
            ->where('location_id', $location->id)
            ->first();

        $this->assertEquals(70, $stock->quantity);
    }

    public function test_stock_adjustment_fails_on_insufficient_stock(): void
    {
        $item = InventoryItem::factory()->create();
        $location = Location::factory()->create();

        // Create initial stock with only 10 units
        Stock::create([
            'inventory_item_id' => $item->id,
            'location_id' => $location->id,
            'quantity' => 10,
        ]);

        $adjustmentData = [
            'inventory_item_id' => $item->id,
            'location_id' => $location->id,
            'type' => 'out',
            'quantity' => 50, // More than available
            'notes' => 'Should fail',
        ];

        $response = $this->actingAs($this->user)->post(route('inventory.adjust'), $adjustmentData);

        // Should fail with exception or validation error
        $response->assertStatus(500); // Exception thrown for insufficient stock

        // Stock should remain unchanged
        $stock = Stock::where('inventory_item_id', $item->id)
            ->where('location_id', $location->id)
            ->first();

        $this->assertEquals(10, $stock->quantity);
    }
}
