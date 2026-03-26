<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\Location;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * List master barang dan total stok.
     */
    public function index(): JsonResponse
    {
        $items = InventoryItem::with(['category', 'stocks.location'])
            ->withSum('stocks as total_stock', 'quantity')
            ->get();

        return response()->json($items);
    }

    /**
     * Detail barang dan riwayat transaksi.
     */
    public function show(int $id): JsonResponse
    {
        $item = InventoryItem::with(['category', 'stocks.location', 'transactions.user', 'serials'])
            ->withSum('stocks as total_stock', 'quantity')
            ->findOrFail($id);

        return response()->json($item);
    }

    /**
     * Input transaksi barang (Barang Masuk/Keluar).
     */
    public function transaction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|in:in,out,transfer,adjustment',
            'quantity' => 'required|numeric|min:0.01',
            'inventory_serial_id' => 'nullable|exists:inventory_serials,id',
            'reference_no' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $transaction = $this->inventoryService->recordTransaction($validated);

        return response()->json([
            'message' => 'Transaksi berhasil dicatat',
            'transaction' => $transaction
        ]);
    }

    /**
     * List Lokasi (Gudang/Mobil/Site).
     */
    public function locations(): JsonResponse
    {
        return response()->json(Location::where('is_active', true)->get());
    }

    /**
     * List Kategori Barang.
     */
    public function categories(): JsonResponse
    {
        return response()->json(InventoryCategory::all());
    }

    /**
     * Simpan barang baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:inventory_categories,id',
            'sku' => 'nullable|string|unique:inventory_items,sku',
            'unit' => 'required|string|max:20',
            'min_stock_alert' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $item = InventoryItem::create($validated);

        return response()->json([
            'message' => 'Barang berhasil ditambahkan',
            'item' => $item
        ], 201);
    }
}
