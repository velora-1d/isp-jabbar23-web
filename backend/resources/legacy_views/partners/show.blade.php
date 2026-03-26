@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('partners.index') }}" class="p-2 bg-gray-800 border border-gray-700 rounded-lg text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $partner->name }}</h1>
                <p class="text-gray-400 mt-1 font-mono text-sm leading-none">{{ $partner->code }} &bull; <span class="text-{{ $partner->status_color }}-400">{{ $partner->status_label }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('partners.edit', $partner) }}" class="px-4 py-2 bg-gray-700 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                Edit Partner
            </a>
            <form action="{{ route('partners.destroy', $partner) }}" method="POST" onsubmit="return confirm('Hapus partner ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Informasi Partner</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <div class="text-sm text-gray-400 uppercase tracking-wider">Email</div>
                        <div class="text-white">{{ $partner->email ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 uppercase tracking-wider">Telepon</div>
                        <div class="text-white">{{ $partner->phone ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 uppercase tracking-wider">Tingkat Komisi</div>
                        <div class="text-lg font-semibold text-emerald-400">{{ $partner->commission_rate }}%</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 uppercase tracking-wider">Status</div>
                        <div class="mt-1">
                            <span class="px-2 py-0.5 bg-{{ $partner->status_color }}-500/20 text-{{ $partner->status_color }}-400 text-xs rounded border border-{{ $partner->status_color }}-500/30 font-medium">
                                {{ $partner->status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-sm text-gray-400 uppercase tracking-wider mb-2">Alamat</div>
                    <div class="text-white bg-gray-900/50 p-3 rounded-lg border border-gray-700">
                        {{ $partner->address ?: 'Tidak ada alamat.' }}
                    </div>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Catatan Internal</h3>
                <div class="text-gray-300 text-sm whitespace-pre-line bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                    {{ $partner->notes ?: 'Tidak ada catatan.' }}
                </div>
            </div>
        </div>

        <!-- Stats / Sidebar -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Ringkasan Saldo</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-900/80 rounded-xl border border-gray-700">
                        <div class="text-xs text-gray-400 mb-1">Total Komisi Terakumulasi</div>
                        <div class="text-2xl font-bold text-white">Rp {{ number_format($partner->balance ?? 0) }}</div>
                    </div>
                    <p class="text-xs text-gray-500 italic text-center">Data histori transaksi akan tersedia di modul finansial.</p>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6 text-center">
                <div class="text-sm text-gray-400 mb-1 leading-none">Terdaftar sejak</div>
                <div class="text-white text-sm font-medium">{{ $partner->created_at->format('d M Y') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
