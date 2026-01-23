@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-red-400 to-rose-400 bg-clip-text text-transparent">Detail Purchase Order</h1>
            <p class="text-gray-400 mt-1">{{ $purchaseOrder->po_number }}</p>
        </div>
        <div class="flex gap-2">
            @if(in_array($purchaseOrder->status, ['draft', 'pending']))
            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600/20 hover:bg-amber-600/40 text-amber-400 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @endif
            <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-2xl">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Informasi PO</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">No. PO</p>
                        <p class="text-white font-mono font-semibold">{{ $purchaseOrder->po_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Status</p>
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-{{ $purchaseOrder->status_color }}-500/20 text-{{ $purchaseOrder->status_color }}-400 mt-1">
                            {{ $purchaseOrder->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Tanggal Order</p>
                        <p class="text-white">{{ $purchaseOrder->order_date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Estimasi Diterima</p>
                        <p class="text-white">{{ $purchaseOrder->expected_date?->format('d F Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Dibuat Oleh</p>
                        <p class="text-white">{{ $purchaseOrder->creator->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Disetujui Oleh</p>
                        <p class="text-white">{{ $purchaseOrder->approver->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white">Item Pembelian</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Item</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Harga</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @foreach($purchaseOrder->items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-white font-medium">{{ $item->item_name }}</p>
                                @if($item->item_code)
                                <p class="text-sm text-gray-400">{{ $item->item_code }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-gray-300">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right text-gray-300">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-white font-semibold">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900/30">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-gray-400 font-semibold">Total</td>
                            <td class="px-6 py-4 text-right text-2xl font-bold text-emerald-400">Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Vendor Info -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Vendor</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ strtoupper(substr($purchaseOrder->vendor->name ?? 'V', 0, 2)) }}</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold">{{ $purchaseOrder->vendor->name ?? '-' }}</p>
                        <p class="text-sm text-gray-400">{{ $purchaseOrder->vendor->contact_person ?? '' }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Email</span>
                        <span class="text-white">{{ $purchaseOrder->vendor->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Phone</span>
                        <span class="text-white">{{ $purchaseOrder->vendor->phone ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 space-y-3">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Aksi</h3>
                
                @if($purchaseOrder->status === 'draft')
                <form action="{{ route('purchase-orders.approve', $purchaseOrder) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-colors">
                        Setujui PO
                    </button>
                </form>
                @endif

                @if(!in_array($purchaseOrder->status, ['cancelled', 'received']))
                <form action="{{ route('purchase-orders.cancel', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Batalkan PO ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 rounded-xl transition-colors">
                        Batalkan
                    </button>
                </form>
                @endif
            </div>

            <!-- Notes -->
            @if($purchaseOrder->notes)
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Catatan</h3>
                <p class="text-gray-300">{{ $purchaseOrder->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
