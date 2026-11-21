@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('contracts.index') }}" class="p-2 bg-gray-800 border border-gray-700 rounded-lg text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $contract->contract_number }}</h1>
                <p class="text-gray-400 mt-1">Pelanggan: <span class="text-white">{{ $contract->customer->name }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 text-sm font-semibold rounded-full bg-{{ $contract->status_color }}-500/20 text-{{ $contract->status_color }}-400 border border-{{ $contract->status_color }}-500/30">
                {{ $contract->status_label }}
            </span>
            <a href="{{ route('contracts.edit', $contract) }}" class="px-4 py-2 bg-gray-700 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                Edit Kontrak
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Detail Kontrak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-400">No. Kontrak</div>
                        <div class="text-white font-mono">{{ $contract->contract_number }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Status</div>
                        <div class="text-white">{{ $contract->status_label }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Tanggal Mulai</div>
                        <div class="text-white">{{ $contract->start_date->format('d F Y') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Tanggal Berakhir</div>
                        <div class="text-white">{{ $contract->end_date ? $contract->end_date->format('d F Y') : 'Ongoing / Otomatis Perpanjang' }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Syarat & Ketentuan</h3>
                <div class="text-gray-300 text-sm whitespace-pre-line bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                    {{ $contract->terms ?: 'Tidak ada syarat khusus yang dicatat.' }}
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Dokumen Pendukung</h3>
                @if($contract->scanned_copy_path)
                <div class="flex items-center p-3 bg-gray-900 border border-gray-700 rounded-lg mb-4">
                    <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-white truncate">Scan_Kontrak.pdf</div>
                        <div class="text-xs text-gray-400">Original Copy</div>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $contract->scanned_copy_path) }}" target="_blank" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Lihat Dokumen
                </a>
                @else
                <div class="text-center py-4">
                    <svg class="mx-auto h-12 w-12 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <p class="text-gray-500 text-xs">Belum ada file scan.</p>
                </div>
                @endif
            </div>

            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6 text-center">
                <div class="text-sm text-gray-400 mb-1">Dibuat pada</div>
                <div class="text-white text-sm font-medium">{{ $contract->created_at->format('d M Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
