@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Manajemen Tiket</h1>
                <p class="text-gray-400 text-sm mt-1">Kelola aduan dan permintaan support pelanggan.</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Tiket Baru
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center text-emerald-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
             <!-- Card 1: Open -->
             <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Status Open</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $stats['open'] }}</p>
                            <p class="text-xs text-red-400 mt-2">Perlu tindakan segera</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 shadow-lg shadow-red-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Card 2: On Progress -->
             <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Sedang Proses</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $stats['in_progress'] }}</p>
                            <p class="text-xs text-amber-400 mt-2">Sedang dikerjakan teknisi</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Card 3: Resolved -->
             <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Selesai (Resolved)</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $stats['resolved'] }}</p>
                            <p class="text-xs text-emerald-400 mt-2">Masalah teratasi</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
             </div>
        </div>

        <!-- Filter & Search -->
        <div class="mb-6 bg-gray-800 rounded-xl p-4 border border-gray-700/50">
            <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari tiket, customer...">
                </div>
                <div>
                     <select name="status" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>On Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div>
                    <select name="priority" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Semua Prioritas</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">Filter</button>
                    <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg text-sm font-medium transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <!-- Tickets Table -->
        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700/50 overflow-hidden">
             <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4">Tiket Info</th>
                            <th scope="col" class="px-6 py-4">Pelanggan</th>
                            <th scope="col" class="px-6 py-4">Teknisi</th>
                            <th scope="col" class="px-6 py-4">Prioritas</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white mb-1">{{ $ticket->subject }}</div>
                                <div class="text-xs text-blue-400 font-mono">{{ $ticket->ticket_number }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $ticket->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $ticket->customer->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->customer->customer_id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($ticket->technician)
                                    <div class="flex items-center">
                                         <div class="h-6 w-6 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-300 mr-2">
                                            {{ substr($ticket->technician->name, 0, 1) }}
                                         </div>
                                         <span class="text-gray-300">{{ $ticket->technician->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500 italic">Belum assign</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $prioColors = [
                                        'low' => 'text-gray-400 bg-gray-400/10',
                                        'medium' => 'text-blue-400 bg-blue-400/10',
                                        'high' => 'text-orange-400 bg-orange-400/10',
                                        'critical' => 'text-red-400 bg-red-400/10',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioColors[$ticket->priority] }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'open' => 'text-red-400 bg-red-400/10 border border-red-400/20',
                                        'in_progress' => 'text-amber-400 bg-amber-400/10 border border-amber-400/20',
                                        'resolved' => 'text-emerald-400 bg-emerald-400/10 border border-emerald-400/20',
                                        'closed' => 'text-gray-400 bg-gray-400/10 border border-gray-400/20',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$ticket->status] }}">
                                    {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-xs font-medium rounded-lg transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p>Belum ada tiket pengaduan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $tickets->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
