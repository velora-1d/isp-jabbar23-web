@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 to-orange-400 bg-clip-text text-transparent">Edit Purchase Order</h1>
            <p class="text-gray-400 mt-1">{{ $purchaseOrder->po_number }}</p>
        </div>
        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Informasi PO</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Vendor *</label>
                    <select name="vendor_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ $purchaseOrder->vendor_id == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Order *</label>
                    <input type="date" name="order_date" value="{{ old('order_date', $purchaseOrder->order_date?->format('Y-m-d')) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Diharapkan</label>
                    <input type="date" name="expected_date" value="{{ old('expected_date', $purchaseOrder->expected_date?->format('Y-m-d')) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">{{ old('notes', $purchaseOrder->notes) }}</textarea>
            </div>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Item</h3>
                <button type="button" onclick="addRow()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm rounded-lg transition-colors">+ Tambah Item</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="items-table">
                    <thead>
                        <tr class="text-left text-gray-400 text-sm">
                            <th class="pb-3">Nama Item</th>
                            <th class="pb-3 w-28">Qty</th>
                            <th class="pb-3 w-40">Harga Satuan</th>
                            <th class="pb-3 w-40">Total</th>
                            <th class="pb-3 w-16"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        @foreach($purchaseOrder->items as $index => $item)
                        <tr class="item-row">
                            <td class="py-2 pr-3">
                                <input type="text" name="items[{{ $index }}][item_name]" value="{{ $item->item_name }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm">
                            </td>
                            <td class="py-2 pr-3">
                                <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" required class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm qty-input" onchange="calculateRow(this)">
                            </td>
                            <td class="py-2 pr-3">
                                <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" min="0" step="0.01" required class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm price-input" onchange="calculateRow(this)">
                            </td>
                            <td class="py-2 pr-3">
                                <span class="row-total text-amber-400 font-medium">Rp {{ number_format($item->total_price) }}</span>
                            </td>
                            <td class="py-2">
                                <button type="button" onclick="removeRow(this)" class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-gray-700">
                            <td colspan="3" class="py-4 text-right text-gray-300 font-medium">Grand Total:</td>
                            <td class="py-4"><span id="grand-total" class="text-xl font-bold text-amber-400">Rp {{ number_format($purchaseOrder->total) }}</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-semibold rounded-xl transition-all shadow-lg shadow-amber-500/25">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
let rowIndex = {{ count($purchaseOrder->items) }};

function addRow() {
    const tbody = document.getElementById('items-body');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td class="py-2 pr-3">
            <input type="text" name="items[${rowIndex}][item_name]" required class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm">
        </td>
        <td class="py-2 pr-3">
            <input type="number" name="items[${rowIndex}][quantity]" min="1" value="1" required class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm qty-input" onchange="calculateRow(this)">
        </td>
        <td class="py-2 pr-3">
            <input type="number" name="items[${rowIndex}][unit_price]" min="0" step="0.01" value="0" required class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm price-input" onchange="calculateRow(this)">
        </td>
        <td class="py-2 pr-3">
            <span class="row-total text-amber-400 font-medium">Rp 0</span>
        </td>
        <td class="py-2">
            <button type="button" onclick="removeRow(this)" class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    rowIndex++;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('tr').remove();
        calculateTotal();
    }
}

function calculateRow(input) {
    const row = input.closest('tr');
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = qty * price;
    row.querySelector('.row-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        total += qty * price;
    });
    document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection
