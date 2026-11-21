@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-red-400 to-rose-400 bg-clip-text text-transparent">Buat Purchase Order</h1>
            <p class="text-gray-400 mt-1">No. PO: <span class="font-mono text-white">{{ $poNumber }}</span></p>
        </div>
        <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('purchase-orders.store') }}" method="POST" class="space-y-6" id="poForm">
        @csrf
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Informasi PO</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Vendor *</label>
                    <select name="vendor_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Order *</label>
                    <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Estimasi Diterima</label>
                    <input type="date" name="expected_date" value="{{ old('expected_date') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center justify-between border-b border-gray-700 pb-3 mb-4">
                <h3 class="text-lg font-semibold text-white">Item Pembelian</h3>
                <button type="button" onclick="addItem()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600/20 hover:bg-emerald-600/40 text-emerald-400 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Item
                </button>
            </div>

            <div id="itemsContainer" class="space-y-4">
                <div class="item-row grid grid-cols-12 gap-4 items-start">
                    <div class="col-span-5">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Nama Item *</label>
                        <input type="text" name="items[0][item_name]" required placeholder="Nama item" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-3 py-2 text-white text-sm focus:ring-2 focus:ring-red-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Qty *</label>
                        <input type="number" name="items[0][quantity]" required min="1" value="1" onchange="calculateTotal()" class="qty-input w-full bg-gray-700/50 border border-gray-600 rounded-xl px-3 py-2 text-white text-sm focus:ring-2 focus:ring-red-500">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Harga Satuan *</label>
                        <input type="number" name="items[0][unit_price]" required min="0" value="0" onchange="calculateTotal()" class="price-input w-full bg-gray-700/50 border border-gray-600 rounded-xl px-3 py-2 text-white text-sm focus:ring-2 focus:ring-red-500">
                    </div>
                    <div class="col-span-2 flex items-end pb-1">
                        <button type="button" onclick="removeItem(this)" class="p-2 text-red-400 hover:bg-red-600/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-700 text-right">
                <p class="text-gray-400 text-sm">Total Estimasi:</p>
                <p id="grandTotal" class="text-2xl font-bold text-emerald-400">Rp 0</p>
            </div>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <label class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
            <textarea name="notes" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('purchase-orders.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-red-500/25">Simpan PO</button>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'item-row grid grid-cols-12 gap-4 items-start';
    row.innerHTML = `
        <div class="col-span-5">
            <input type="text" name="items[${itemIndex}][item_name]" required placeholder="Nama item" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-3 py-2 text-white text-sm focus:ring-2 focus:ring-red-500">
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemIndex}][quantity]" required min="1" value="1" onchange="calculateTotal()" class="qty-input w-full bg-gray-700/50 border border-gray-600 rounded-xl px-3 py-2 text-white text-sm focus:ring-2 focus:ring-red-500">
        </div>
        <div class="col-span-3">
            <input type="number" name="items[${itemIndex}][unit_price]" required min="0" value="0" onchange="calculateTotal()" class="price-input w-full bg-gray-700/50 border border-gray-600 rounded-xl px-3 py-2 text-white text-sm focus:ring-2 focus:ring-red-500">
        </div>
        <div class="col-span-2 flex items-center">
            <button type="button" onclick="removeItem(this)" class="p-2 text-red-400 hover:bg-red-600/20 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    `;
    container.appendChild(row);
    itemIndex++;
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
        calculateTotal();
    }
}

function calculateTotal() {
    let total = 0;
    const rows = document.querySelectorAll('.item-row');
    rows.forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
        total += qty * price;
    });
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection
