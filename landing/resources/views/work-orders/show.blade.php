@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $workOrder->ticket_number }}</h1>
                <p class="text-gray-400 mt-1">Status: <span class="uppercase font-semibold text-{{ $workOrder->status_color }}-400">{{ str_replace('_', ' ', $workOrder->status) }}</span></p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('work-orders.index') }}" class="text-gray-400 hover:text-white transition px-3">Kembali</a>
            
            @if($workOrder->status === 'pending' || $workOrder->status === 'scheduled')
                <form action="{{ route('work-orders.update-status', $workOrder) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="on_way">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        OTW Lokasi
                    </button>
                </form>
            @endif

            @if($workOrder->status === 'on_way')
                <form action="{{ route('work-orders.update-status', $workOrder) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                        Mulai Pengerjaan
                    </button>
                </form>
            @endif

            @if($workOrder->status === 'in_progress')
                <button onclick="document.getElementById('complete-modal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20">
                    Selesaikan Pekerjaan
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Detail Pekerjaan</h3>
                
                <div class="grid grid-cols-2 gap-y-4 text-sm mb-6">
                    <div>
                        <div class="text-gray-500 mb-1">Customer</div>
                        <div class="text-white font-medium text-lg">{{ $workOrder->customer->name ?? 'Non-Customer WO' }}</div>
                        <div class="text-gray-400">{{ $workOrder->customer->address ?? '' }}</div>
                    </div>
                    </div>
                    
                    <div class="mt-4 border-t border-gray-700/50 pt-4 col-span-2">
                        <div class="text-gray-500 mb-1">Target Infrastructure (ODP)</div>
                        @if($workOrder->odp)
                            <div class="flex items-center gap-2">
                                <span class="text-white font-medium">{{ $workOrder->odp->name }}</span>
                                <span class="px-2 py-0.5 rounded text-xs bg-gray-700 text-gray-300">{{ $workOrder->odp->total_ports }} Ports</span>
                                <span class="text-xs text-{{ $workOrder->odp->status == 'active' ? 'green' : 'red' }}-400 uppercase">({{ $workOrder->odp->status }})</span>
                            </div>
                            <div class="text-gray-400 text-xs mt-1">{{ $workOrder->odp->address }}</div>
                        @else
                            <div class="text-gray-500 italic">Tidak ada data ODP terkait.</div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-700 mb-6">
                    <div class="text-xs text-gray-500 uppercase tracking-wider mb-2">Deskripsi</div>
                    <p class="text-gray-300 leading-relaxed">{{ $workOrder->description }}</p>
                </div>

                @if($workOrder->technician_notes)
                <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-700">
                    <div class="text-xs text-gray-500 uppercase tracking-wider mb-2">Catatan Penyelesaian</div>
                    <p class="text-gray-300 leading-relaxed">{{ $workOrder->technician_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Material Usage -->
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Material & Tools Used</h3>
                    @if($workOrder->status === 'in_progress')
                    <button onclick="document.getElementById('add-material-modal').classList.remove('hidden')" class="text-sm text-blue-400 hover:text-blue-300 font-medium">
                        + Tambah Material
                    </button>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-2">Item Name</th>
                                <th class="px-4 py-2 text-right">Qty</th>
                                <th class="px-4 py-2">Unit</th>
                                <th class="px-4 py-2">Notes</th>
                                <th class="px-4 py-2 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($workOrder->items as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium text-white">{{ $item->inventoryItem->name }}</td>
                                <td class="px-4 py-3 text-right text-emerald-400 font-bold">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">{{ $item->unit }}</td>
                                <td class="px-4 py-3">{{ $item->notes ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if($workOrder->status === 'in_progress')
                                    <form action="{{ route('work-order-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500 italic">Belum ada material yang dicatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Assignment Info</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-gray-500 mb-1">Lead Technician</div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-xs">
                                {{ substr($workOrder->technician->name ?? 'U', 0, 2) }}
                            </div>
                            <div class="text-white font-medium">{{ $workOrder->technician->name ?? 'Unassigned' }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 mb-1">Scheduled Date</div>
                        <div class="text-white font-medium">{{ $workOrder->scheduled_date ? $workOrder->scheduled_date->format('l, d F Y H:i') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Material Modal -->
<div id="add-material-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="document.getElementById('add-material-modal').classList.add('hidden')"></div>
        <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700 shadow-xl rounded-2xl">
            <h3 class="text-lg font-medium leading-6 text-white mb-4">Catat Material</h3>
            <form action="{{ route('work-orders.add-item', $workOrder) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Material / Barang</label>
                        <select name="inventory_item_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                            @foreach($inventoryItems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->total_stock }} {{ $item->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Quantity Used</label>
                        <input type="number" name="quantity" step="0.01" min="0.01" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Merek / Serial Number (Optional)</label>
                        <input type="text" name="notes" placeholder="Contoh: SN: 48575443322" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('add-material-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete WO Modal -->
<div id="complete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="document.getElementById('complete-modal').classList.add('hidden')"></div>
        <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700 shadow-xl rounded-2xl">
            <h3 class="text-lg font-medium leading-6 text-white mb-4">Selesaikan Pekerjaan</h3>
            <form action="{{ route('work-orders.update-status', $workOrder) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="completed">
                <div class="space-y-4">
                    <p class="text-sm text-gray-400">Pastikan semua material yang digunakan sudah dicatat dengan benar di tabel. Material akan otomatis dipotong dari stok setelah konfirmasi ini.</p>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Catatan Penyelesaian</label>
                        <textarea name="technician_notes" rows="4" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white" placeholder="Jelaskan hasil pekerjaan, kendala, solusi, dan sinyal redaman (jika ada)..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('complete-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Konfirmasi Selesai</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
