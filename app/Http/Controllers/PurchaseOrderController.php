<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin|finance');
    }

    public function index(): View
    {
        $purchaseOrders = PurchaseOrder::query()
            ->with(['vendor', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => PurchaseOrder::query()->count('*'),
            'draft' => PurchaseOrder::query()->where('status', '=', 'draft')->count('*'),
            'pending' => PurchaseOrder::query()->where('status', '=', 'pending')->count('*'),
            'received' => PurchaseOrder::query()->where('status', '=', 'received')->count('*'),
            'total_value' => PurchaseOrder::query()->whereIn('status', ['approved', 'ordered', 'partial', 'received'])->sum('total'),
        ];

        return view('inventory.purchase-orders.index', compact('purchaseOrders', 'stats'));
    }

    public function create(): View
    {
        $vendors = Vendor::query()
            ->where('status', '=', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $inventoryItems = InventoryItem::query()
            ->orderBy('name', 'asc')
            ->get();

        $poNumber = PurchaseOrder::generatePoNumber();

        return view('inventory.purchase-orders.create', compact('vendors', 'inventoryItems', 'poNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        /** @var PurchaseOrder $po */
        $po = PurchaseOrder::create([
            'po_number' => PurchaseOrder::generatePoNumber(),
            'vendor_id' => $validated['vendor_id'],
            'order_date' => $validated['order_date'],
            'expected_date' => $validated['expected_date'],
            'notes' => $validated['notes'],
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'inventory_item_id' => $item['inventory_item_id'] ?? null,
                'item_name' => $item['item_name'],
                'item_code' => $item['item_code'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('purchase-orders.show', $po)
            ->with('success', 'Purchase Order berhasil dibuat!');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['vendor', 'items', 'creator', 'approver']);

        return view('inventory.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View|RedirectResponse
    {
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return back()->with('error', 'PO tidak dapat diedit.');
        }

        $vendors = Vendor::query()
            ->where('status', '=', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $inventoryItems = InventoryItem::query()
            ->orderBy('name', 'asc')
            ->get();

        $purchaseOrder->load(['items']);

        return view('inventory.purchase-orders.edit', compact('purchaseOrder', 'vendors', 'inventoryItems'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return back()->with('error', 'PO tidak dapat diedit.');
        }

        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $purchaseOrder->update([
            'vendor_id' => $validated['vendor_id'],
            'order_date' => $validated['order_date'],
            'expected_date' => $validated['expected_date'],
            'notes' => $validated['notes'],
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);

        // Delete existing items and recreate
        $purchaseOrder->items()->delete();

        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'inventory_item_id' => $item['inventory_item_id'] ?? null,
                'item_name' => $item['item_name'],
                'item_code' => $item['item_code'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase Order berhasil diperbarui!');
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if (!in_array($purchaseOrder->status, ['draft', 'cancelled'])) {
            return back()->with('error', 'Hanya PO draft atau cancelled yang dapat dihapus.');
        }

        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dihapus!');
    }

    public function approve(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Purchase Order disetujui!');
    }

    public function cancel(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->update(['status' => 'cancelled']);

        return back()->with('success', 'Purchase Order dibatalkan.');
    }
}
