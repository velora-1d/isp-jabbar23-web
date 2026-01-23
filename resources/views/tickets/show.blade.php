@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                 <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-bold text-white tracking-tight">Tiket #{{ $ticket->ticket_number }}</h1>
                    @php
                        $statusColors = [
                            'open' => 'text-red-400 bg-red-400/10 border border-red-400/20',
                            'in_progress' => 'text-amber-400 bg-amber-400/10 border border-amber-400/20',
                            'resolved' => 'text-emerald-400 bg-emerald-400/10 border border-emerald-400/20',
                            'closed' => 'text-gray-400 bg-gray-400/10 border border-gray-400/20',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$ticket->status] }}">
                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                    </span>
                </div>
                <p class="text-gray-400 text-sm">Dibuat pada {{ $ticket->created_at->format('d M Y H:i') }}</p>
            </div>
            
            <div class="flex items-center gap-3">
                 <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-white transition-colors text-sm font-medium">kembali ke daftar</a>
                 
                 <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tiket ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Hapus Tiket">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                 </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center text-emerald-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Info Card -->
                <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700/50 shadow-xl">
                    <h2 class="text-lg font-semibold text-white mb-4">Detail Pengaduan</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</label>
                            <div class="text-white font-medium text-lg mt-1">{{ $ticket->subject }}</div>
                        </div>
                        
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi Masalah</label>
                            <div class="bg-gray-900/50 rounded-xl p-4 mt-2 text-gray-300 whitespace-pre-line border border-gray-700/50">
                                {{ $ticket->description }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Notes Card -->
                 <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700/50 shadow-xl">
                    <h2 class="text-lg font-semibold text-white mb-4">Catatan Admin / Teknisi</h2>
                    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $ticket->status }}"> <!-- Keep current status unless changed in right panel -->
                        <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                        
                        <textarea name="admin_notes" rows="4" class="w-full bg-gray-900 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Tambahkan catatan internal progres penanganan...">{{ $ticket->admin_notes }}</textarea>
                        
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                                Simpan Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Meta & Actions -->
            <div class="space-y-6">
                
                <!-- Customer Info -->
                <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700/50 shadow-xl">
                     <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Info Pelanggan</h3>
                     <div class="flex items-center mb-4">
                        <div class="h-10 w-10 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-lg mr-3">
                            {{ substr($ticket->customer->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-white font-medium">{{ $ticket->customer->name }}</div>
                            <div class="text-xs text-gray-500">{{ $ticket->customer->customer_id }}</div>
                        </div>
                     </div>
                     <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Paket:</span>
                            <span class="text-gray-300">{{ $ticket->customer->package->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Alamat:</span>
                            <span class="text-gray-300 text-right truncate w-32" title="{{ $ticket->customer->address }}">{{ $ticket->customer->address }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">No. WA:</span>
                            <a href="https://wa.me/{{ $ticket->customer->phone }}" target="_blank" class="text-green-400 hover:underline flex items-center">
                                {{ $ticket->customer->phone }}
                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-8.683-2.031-.967-.272-.297-.471-.446-.646-.446-.175 0-.371.006-.568.006-.197 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/></svg>
                            </a>
                        </div>
                     </div>
                     <div class="mt-4 pt-4 border-t border-gray-700">
                        <a href="{{ route('customers.show', $ticket->customer) }}" class="block w-full text-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                            Lihat Profil Pelanggan
                        </a>
                     </div>
                </div>

                <!-- Management Panel -->
                <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700/50 shadow-xl">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Update Status</h3>
                    
                    <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Tiket</label>
                            <select name="status" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved (Selesai)</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed (Tutup)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Prioritas</label>
                            <select name="priority" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ $ticket->priority == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Assigned Teknisi</label>
                            <select name="technician_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="">-- Belum ada --</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ $ticket->technician_id == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors mt-2">
                            Update Tiket
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
