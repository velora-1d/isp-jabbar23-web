@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-amber-400 bg-clip-text text-transparent">
                Bandwidth Management
            </h1>
            <p class="text-gray-400 mt-1">Kelola paket bandwidth untuk pelanggan</p>
        </div>
        <a href="{{ route('network.bandwidth.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-500 hover:to-amber-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-orange-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Plan
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

    <!-- Bandwidth Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($plans as $plan)
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 {{ !$plan->is_active ? 'opacity-60' : '' }}">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-white">{{ $plan->name }}</h3>
                    <p class="text-gray-400 font-mono text-sm">{{ $plan->code }}</p>
                </div>
                @if(!$plan->is_active)
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-600/50 text-gray-400">Inactive</span>
                @endif
            </div>

            <!-- Speed Display -->
            <div class="bg-gradient-to-r from-orange-500/10 to-amber-500/10 rounded-xl p-4 mb-4 border border-orange-500/20">
                <div class="flex items-center justify-center gap-4">
                    <div class="text-center">
                        <div class="flex items-center gap-1 text-orange-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                            <span class="text-2xl font-bold">{{ $plan->download_speed }}</span>
                            <span class="text-sm">Mbps</span>
                        </div>
                        <p class="text-xs text-gray-500">Download</p>
                    </div>
                    <div class="h-8 w-px bg-gray-700"></div>
                    <div class="text-center">
                        <div class="flex items-center gap-1 text-amber-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span class="text-2xl font-bold">{{ $plan->upload_speed }}</span>
                            <span class="text-sm">Mbps</span>
                        </div>
                        <p class="text-xs text-gray-500">Upload</p>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <span class="text-gray-500">Burst</span>
                    <p class="text-gray-300">{{ $plan->burst_label }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Priority</span>
                    <p class="text-gray-300">{{ $plan->priority }}/8</p>
                </div>
            </div>

            @if($plan->description)
            <p class="text-gray-400 text-sm mb-4">{{ Str::limit($plan->description, 80) }}</p>
            @endif

            <!-- Actions -->
            <div class="flex gap-2">
                <a href="{{ route('network.bandwidth.edit', $plan) }}" class="flex-1 px-4 py-2 bg-amber-600/20 hover:bg-amber-600/40 text-amber-400 rounded-lg transition-colors text-sm text-center">
                    Edit
                </a>
                <form action="{{ route('network.bandwidth.destroy', $plan) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus bandwidth plan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 rounded-lg transition-colors text-sm">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-12 text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <p class="text-gray-400">Belum ada bandwidth plan yang terdaftar.</p>
            <a href="{{ route('network.bandwidth.create') }}" class="text-orange-400 hover:underline mt-2 inline-block">Buat plan pertama</a>
        </div>
        @endforelse
    </div>

    @if($plans->hasPages())
    <div class="mt-6">
        {{ $plans->links() }}
    </div>
    @endif
</div>
@endsection
