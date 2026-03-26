<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\Location;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('permission:view inventory')->only(['index']);
        $this->middleware('permission:create items')->only(['store']);
        $this->middleware('permission:adjust stock')->only(['adjustStock']);
    }

    public function index(Request $request)
    {
        $query = InventoryItem::with(['category', 'stocks']);

        // Apply global filters (year, month, search)
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'sku']
        ]);

        // Apply category filter
        $this->applyRelationFilter($query, $request, 'category_id');

        // Apply stock status filter
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM stocks WHERE stocks.inventory_item_id = inventory_items.id) <= min_stock_alert');
            } elseif ($request->stock_status === 'out') {
                $query->whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM stocks WHERE stocks.inventory_item_id = inventory_items.id) = 0');
            }
        }

        $items = $query->latest()->paginate(15)->withQueryString();
        $categories = InventoryCategory::all();
        $locations = Location::where('is_active', true)->get();

        // Calculate low stock items
        $lowStockCount = InventoryItem::get()->filter(function ($item) {
            return $item->total_stock <= $item->min_stock_alert;
        })->count();

        $stats = [
            'total_items' => InventoryItem::count(),
            'total_value' => InventoryItem::get()->sum(fn($item) => $item->total_stock * $item->purchase_price),
            'low_stock' => $lowStockCount,
            'categories' => $categories->count(),
        ];

        // Filter options
        $stockStatuses = [
            'low' => 'Low Stock',
            'out' => 'Out of Stock',
        ];

        return view('inventory.index', compact('items', 'categories', 'locations', 'stats', 'stockStatuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:inventory_items,sku',
            'category_id' => 'required|exists:inventory_categories,id',
            'unit' => 'required|string',
            'min_stock_alert' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ]);

        InventoryItem::create($validated);

        return back()->with('success', 'Item berhasil ditambahkan!');
    }

    public function adjustStock(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
            'reference_number' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $stock = Stock::firstOrCreate(
                [
                    'inventory_item_id' => $validated['inventory_item_id'],
                    'location_id' => $validated['location_id']
                ],
                ['quantity' => 0]
            );

            $previousQty = $stock->quantity;

            if ($validated['type'] === 'in') {
                $stock->quantity += $validated['quantity'];
            } else {
                if ($stock->quantity < $validated['quantity']) {
                    throw new \Exception('Stok tidak mencukupi!');
                }
                $stock->quantity -= $validated['quantity'];
            }

            $stock->save();

            StockMovement::create([
                'inventory_item_id' => $validated['inventory_item_id'],
                'location_id' => $validated['location_id'],
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'previous_quantity' => $previousQty,
                'new_quantity' => $stock->quantity,
                'reference_number' => $validated['reference_number'],
                'notes' => $validated['notes'],
            ]);
        });

        return back()->with('success', 'Stok berhasil diupdate!');
    }

    public function storeSerials(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'location_id' => 'required|exists:locations,id',
            'serials' => 'required|string',
            'reference_no' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $item = InventoryItem::findOrFail($validated['inventory_item_id']);
        $serials = preg_split('/[\n,]+/', $validated['serials']);
        $serials = array_map('trim', $serials);
        $serials = array_filter($serials); 

        DB::transaction(function () use ($item, $serials, $validated) {
            $count = 0;
            foreach ($serials as $sn) {
                if (\App\Models\InventorySerial::where('serial_number', $sn)->exists()) {
                    continue; 
                }

                $serial = \App\Models\InventorySerial::create([
                    'inventory_item_id' => $item->id,
                    'serial_number' => $sn,
                    'location_id' => $validated['location_id'],
                    'status' => 'available'
                ]);

                \App\Models\InventoryTransaction::create([
                    'inventory_item_id' => $item->id,
                    'inventory_serial_id' => $serial->id,
                    'type' => 'in',
                    'quantity' => 1,
                    'reference_no' => $validated['reference_no'],
                    'user_id' => Auth::id(),
                    'notes' => "Bulk Stock In: " . $validated['notes'],
                ]);
                
                $stock = Stock::firstOrCreate(
                    ['inventory_item_id' => $item->id, 'location_id' => $validated['location_id']],
                    ['quantity' => 0]
                );
                $stock->increment('quantity');
                $count++;
            }

            if ($count > 0) {
                StockMovement::create([
                    'inventory_item_id' => $item->id,
                    'location_id' => $validated['location_id'],
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $count,
                    'previous_quantity' => $item->total_stock - $count,
                    'new_quantity' => $item->total_stock,
                    'reference_number' => $validated['reference_no'],
                    'notes' => "Added {$count} items via SN Scan. " . $validated['notes'],
                ]);
            }
        });

        return back()->with('success', "Berhasil menambahkan item dengan Serial Number!");
    }

    public function assignSerial(Request $request)
    {
        $validated = $request->validate([
            'inventory_serial_id' => 'required|exists:inventory_serials,id',
            'customer_id' => 'required|exists:customers,id',
            'notes' => 'nullable|string',
        ]);

        $serial = \App\Models\InventorySerial::findOrFail($validated['inventory_serial_id']);
        
        if ($serial->status !== 'available') {
            return back()->withErrors(['error' => 'Perangkat ini sudah terpasang atau tidak tersedia.']);
        }

        DB::transaction(function () use ($serial, $validated) {
            $serial->update([
                'status' => 'assigned',
                'customer_id' => $validated['customer_id']
            ]);

            \App\Models\InventoryTransaction::create([
                'inventory_item_id' => $serial->inventory_item_id,
                'inventory_serial_id' => $serial->id,
                'type' => 'out',
                'quantity' => 1,
                'reference_no' => 'CUST-' . $validated['customer_id'],
                'user_id' => Auth::id(),
                'notes' => "Assigned to Customer: " . $validated['notes'],
            ]);
            
            // Also deduct from stock table if we track it there
            $stock = Stock::where('inventory_item_id', $serial->inventory_item_id)
                ->where('location_id', $serial->location_id)
                ->first();
            
            if ($stock) {
                $stock->decrement('quantity');
            }
        });

        return back()->with('success', 'Perangkat berhasil dipasang ke pelanggan!');
    }
    public function update(Request $request, InventoryItem $inventoryItem)
    {
        // Use separate route model binding or just find by ID if route param name differs
        // Route::resource uses 'inventory' as param name by default (inventory/{inventory})
        // So method signature should be update(Request $request, InventoryItem $inventory)

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:inventory_categories,id',
            'unit' => 'required|string',
            'sku' => 'nullable|string|unique:inventory_items,sku,' . $inventoryItem->id,
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'min_stock_alert' => 'required|numeric',
        ]);

        $inventoryItem->update($validated);

        return back()->with('success', 'Item berhasil diperbarui!');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        // Check if has stock or movements
        if ($inventoryItem->total_stock > 0) {
            return back()->withErrors(['error' => 'Gagal hapus! Item masih memiliki stok. Gunakan Stock Adjustment untuk mengosongkan.']);
        }

        // We can just soft delete or delete. For now delete.
        InventoryItem::destroy($inventoryItem->id);

        return back()->with('success', 'Item berhasil dihapus!');
    }
}
