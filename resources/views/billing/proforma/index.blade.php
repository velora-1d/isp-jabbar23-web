@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <!-- Header with Filters -->
        <x-filter-bar :filters="$filters ?? []">
            <x-slot name="global">
                <x-filter-global :search-placeholder="'Cari Proforma...'" />
            </x-slot>

            <x-slot name="filters">
                <x-filter-select name="status" label="Status" :options="$statuses" :selected="request('status')" />
            </x-slot>

            <x-slot name="actions">
                <a href="{{ route('billing.proforma.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-fuchsia-600 to-pink-600 hover:from-fuchsia-500 hover:to-pink-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-fuchsia-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Proforma
                </a>
            </x-slot>
        </x-filter-bar>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Menunggu</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['pending_count'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-fuchsia-500 to-pink-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Nilai Pending</p>
                        <p class="text-2xl font-bold text-white">Rp
                            {{ number_format($stats['pending_value'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Dikonversi</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['converted_count'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-gray-500 to-slate-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Kadaluarsa</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['expired_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">No. Proforma</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Jumlah</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Berlaku Sampai
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($proformas as $proforma)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('billing.proforma.show', $proforma) }}"
                                        class="text-fuchsia-400 hover:text-fuchsia-300 font-medium">
                                        {{ $proforma->proforma_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-br from-fuchsia-500 to-pink-600 flex items-center justify-center text-white text-sm font-semibold">
                                            {{ strtoupper(substr($proforma->customer->name ?? '-', 0, 1)) }}
                                        </div>
                                        <span class="text-white">{{ $proforma->customer->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-white font-medium">{{ $proforma->formatted_amount }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $proforma->valid_until->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $proforma->status_color }}-500/20 text-{{ $proforma->status_color }}-400">
                                        {{ $proforma->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('billing.proforma.show', $proforma) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-fuchsia-600/20 hover:bg-fuchsia-600/40 text-fuchsia-400 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Belum ada proforma invoice</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($proformas->hasPages())
                <div class="px-6 py-4 border-t border-gray-700/50">
                    {{ $proformas->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
