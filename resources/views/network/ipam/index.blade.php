@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-violet-400 to-purple-400 bg-clip-text text-transparent">
                IP Address Management
            </h1>
            <p class="text-gray-400 mt-1">Kelola pool IP dan alokasi alamat</p>
        </div>
        <a href="{{ route('network.ipam.pools.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-500 hover:to-purple-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-violet-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pool
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Pools</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total_pools'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total IP</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $stats['total_ips'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Allocated</p>
                    <p class="text-2xl font-bold text-amber-400">{{ $stats['allocated'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Available</p>
                    <p class="text-2xl font-bold text-emerald-400">{{ $stats['available'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- IP Pools -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($pools as $pool)
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-white">{{ $pool->name }}</h3>
                    <p class="text-gray-400 font-mono text-sm">{{ $pool->network_cidr }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $pool->type_color }}-500/20 text-{{ $pool->type_color }}-400">
                    {{ ucfirst($pool->type) }}
                </span>
            </div>

            <!-- Usage Bar -->
            <div class="mb-4">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-400">Usage</span>
                    <span class="text-white">{{ $pool->usage_percent }}%</span>
                </div>
                <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-violet-500 to-purple-500 rounded-full transition-all" style="width: {{ $pool->usage_percent }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>{{ $pool->used_ips }} allocated</span>
                    <span>{{ $pool->available_ips }} available</span>
                </div>
            </div>

            <!-- Pool Info -->
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <span class="text-gray-500">Gateway</span>
                    <p class="text-gray-300 font-mono">{{ $pool->gateway ?: '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">DNS</span>
                    <p class="text-gray-300 font-mono">{{ $pool->dns_primary ?: '-' }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <form action="{{ route('network.ipam.pools.destroy', $pool) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pool ini? Semua IP di dalamnya akan terhapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 rounded-lg transition-colors text-sm">
                        Hapus Pool
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-12 text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p class="text-gray-400">Belum ada IP Pool yang terdaftar.</p>
            <a href="{{ route('network.ipam.pools.create') }}" class="text-violet-400 hover:underline mt-2 inline-block">Buat pool pertama</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
