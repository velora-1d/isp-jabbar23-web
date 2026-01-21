@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <!-- Header with Filters -->
        <x-filter-bar :filters="$filters ?? []">
            <x-slot name="global">
                <x-filter-global :search-placeholder="'Cari Promo...'" />
            </x-slot>

            <x-slot name="filters">
                <x-filter-select name="status" label="Status" :options="$statuses" :selected="request('status')" />
            </x-slot>

            <x-slot name="actions">
                <a href="{{ route('promotions.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-pink-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Promo
                </a>
            </x-slot>
        </x-filter-bar>

        @if(session('success'))
            <div
                class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Promo</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Aktif</p>
                        <p class="text-2xl font-bold text-emerald-400">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Penggunaan</p>
                        <p class="text-2xl font-bold text-blue-400">{{ number_format($stats['total_usage']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Segera Berakhir</p>
                        <p class="text-2xl font-bold text-amber-400">{{ $stats['expiring_soon'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotions Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Promo</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Diskon</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Periode</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Penggunaan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($promotions as $promo)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $promo->name }}</p>
                                            <p class="text-sm text-gray-400">{{ Str::limit($promo->description, 40) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code
                                        class="px-2 py-1 bg-gray-700 text-pink-400 rounded text-sm font-mono">{{ $promo->code }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-sm font-medium bg-{{ $promo->type_color }}-500/20 text-{{ $promo->type_color }}-400">
                                        {{ $promo->discount_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    {{ $promo->start_date->format('d M') }} - {{ $promo->end_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-white font-medium">{{ $promo->usage_count }}</span>
                                    @if($promo->usage_limit)
                                        <span class="text-gray-400">/{{ $promo->usage_limit }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-{{ $promo->status_color }}-500/20 text-{{ $promo->status_color }}-400">
                                        {{ $promo->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('promotions.toggle-active', $promo) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 {{ $promo->is_active ? 'bg-amber-600/20 text-amber-400' : 'bg-emerald-600/20 text-emerald-400' }} hover:opacity-75 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="{{ $promo->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z' }}" />
                                                </svg>
                                            </button>
                                        </form>
                                        <a href="{{ route('promotions.edit', $promo) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600/40 text-blue-400 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('promotions.destroy', $promo) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Hapus promo ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600/20 hover:bg-red-600/40 text-red-400 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <p>Belum ada promo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-700/50">
                {{ $promotions->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
