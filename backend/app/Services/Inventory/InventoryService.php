<?php

namespace App\Services\Inventory;

use App\Models\InventoryItem;
use App\Models\InventorySerial;
use App\Models\InventoryTransaction;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryService
{
    /**
     * Catat transaksi inventaris dan update stok.
     */
    public function recordTransaction(array $data): InventoryTransaction
    {
        return DB::transaction(function () use ($data) {
            $type = $data['type']; // in, out, transfer, adjustment
            $itemId = $data['inventory_item_id'];
            $locationId = $data['location_id'];
            $quantity = $data['quantity'];
            $serialId = $data['inventory_serial_id'] ?? null;

            // 1. Create Transaction Log
            $transaction = InventoryTransaction::create([
                'inventory_item_id' => $itemId,
                'inventory_serial_id' => $serialId,
                'type' => $type,
                'quantity' => $quantity,
                'reference_no' => $data['reference_no'] ?? null,
                'user_id' => Auth::id() ?? 1, // Fallback to 1 for testing/seeding
                'notes' => $data['notes'] ?? null,
            ]);

            // 2. Update Stock Table
            $this->updateStock($itemId, $locationId, $quantity, $type);

            // 3. Update Serial Status (if applicable)
            if ($serialId) {
                $this->updateSerialStatus($serialId, $type, $locationId);
            }

            return $transaction;
        });
    }

    /**
     * Update quantity di tabel stocks.
     */
    protected function updateStock(int $itemId, int $locationId, float $quantity, string $type): void
    {
        $stock = Stock::firstOrNew([
            'inventory_item_id' => $itemId,
            'location_id' => $locationId,
        ]);

        switch ($type) {
            case 'in':
            case 'adjustment': // adjustment +
                $stock->quantity += $quantity;
                break;
            case 'out':
            case 'adjustment_minus': // adjustment -
                $stock->quantity -= $quantity;
                break;
            default:
                break;
        }

        $stock->save();
    }

    /**
     * Update status nomor seri.
     */
    protected function updateSerialStatus(int $serialId, string $type, int $locationId): void
    {
        $serial = InventorySerial::find($serialId);
        if (!$serial) return;

        switch ($type) {
            case 'in':
                $serial->status = 'available';
                $serial->location_id = $locationId;
                break;
            case 'out':
                $serial->status = 'assigned';
                $serial->location_id = null; // Terpasang di pelanggan
                break;
            default:
                break;
        }

        $serial->save();
    }

    /**
     * Ambil peringatan stok rendah.
     */
    public function getLowStockAlerts()
    {
        return InventoryItem::whereHas('stocks', function($query) {
            $query->select(DB::raw('SUM(quantity) as total'))
                  ->having('total', '<=', DB::raw('inventory_items.min_stock_alert'));
        })->withSum('stocks as total_stock', 'quantity')->get();
    }
}
