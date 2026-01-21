@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Leads / Prospects</h1>
                <p class="text-gray-400 mt-1">Kelola calon pelanggan potensial</p>
            </div>
            <a href="{{ route('leads.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-cyan-600 transition-all shadow-lg shadow-blue-500/25">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Lead
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4">
                <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-400">Total Leads</div>
            </div>
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                <div class="text-2xl font-bold text-blue-400">{{ $stats['new'] }}</div>
                <div class="text-sm text-blue-300">Baru</div>
            </div>
            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4">
                <div class="text-2xl font-bold text-amber-400">{{ $stats['in_progress'] }}</div>
                <div class="text-sm text-amber-300">Sedang Proses</div>
            </div>
            <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4">
                <div class="text-2xl font-bold text-emerald-400">{{ $stats['won'] }}</div>
                <div class="text-sm text-emerald-300">Berhasil</div>
            </div>
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                <div class="text-2xl font-bold text-red-400">{{ $stats['lost'] }}</div>
                <div class="text-sm text-red-300">Gagal</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, email..."
                    class="flex-1 min-w-[200px] px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <select name="status"
                    class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Lead::STATUSES as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="source"
                    class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Sumber</option>
                    @foreach(\App\Models\Lead::SOURCES as $key => $label)
                        <option value="{{ $key }}" {{ request('source') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Filter</button>
                <a href="{{ route('leads.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Reset</a>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Lead
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Sumber</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Paket
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Sales
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($leads as $lead)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">{{ $lead->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $lead->lead_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-300">{{ $lead->phone ?: '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $lead->email ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-medium bg-gray-700 text-gray-300 rounded">{{ $lead->source_label }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $lead->interestedPackage->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $lead->status_color }}-500/20 text-{{ $lead->status_color }}-400 border border-{{ $lead->status_color }}-500/30">
                                        {{ $lead->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $lead->assignedTo->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('leads.show', $lead) }}"
                                            class="text-blue-400 hover:text-blue-300">Lihat</a>
                                        <a href="{{ route('leads.edit', $lead) }}"
                                            class="text-amber-400 hover:text-amber-300">Edit</a>
                                        @if(!in_array($lead->status, ['won', 'lost']))
                                            <form action="{{ route('leads.convert', $lead) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Konversi lead ini menjadi customer?')">
                                                @csrf
                                                <button type="submit"
                                                    class="text-emerald-400 hover:text-emerald-300">Konversi</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-500 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    Belum ada lead. <a href="{{ route('leads.create') }}"
                                        class="text-blue-400 hover:underline">Tambah lead pertama</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($leads->hasPages())
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
