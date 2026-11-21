@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
            Network Reports
        </h1>
        <p class="text-gray-400 mt-1">Status dan statistik infrastruktur jaringan</p>
    </div>

    <!-- ODP Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total ODP</p>
                    <p class="text-2xl font-bold text-white">{{ $odpStats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">ODP Available</p>
                    <p class="text-2xl font-bold text-emerald-400">{{ $odpStats['available'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">ODP Full</p>
                    <p class="text-2xl font-bold text-red-400">{{ $odpStats['full'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- OLT Stats -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">OLT Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-700/30 rounded-xl">
                <p class="text-sm text-gray-400">Total OLT</p>
                <p class="text-3xl font-bold text-white">{{ $oltStats['total'] }}</p>
            </div>
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl">
                <p class="text-sm text-emerald-400">Online</p>
                <p class="text-3xl font-bold text-emerald-400">{{ $oltStats['online'] }}</p>
            </div>
            <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
                <p class="text-sm text-red-400">Offline</p>
                <p class="text-3xl font-bold text-red-400">{{ $oltStats['offline'] }}</p>
            </div>
        </div>
    </div>

    <!-- Router Stats -->
    @if(!empty($routerStats))
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Router Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-700/30 rounded-xl">
                <p class="text-sm text-gray-400">Total Router</p>
                <p class="text-3xl font-bold text-white">{{ $routerStats['total'] }}</p>
            </div>
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl">
                <p class="text-sm text-emerald-400">Online</p>
                <p class="text-3xl font-bold text-emerald-400">{{ $routerStats['online'] }}</p>
            </div>
            <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
                <p class="text-sm text-red-400">Offline</p>
                <p class="text-3xl font-bold text-red-400">{{ $routerStats['offline'] }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Bandwidth Usage -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Bandwidth Allocation</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-gradient-to-br from-teal-500/20 to-pink-500/20 border border-teal-500/30 rounded-xl">
                <p class="text-sm text-gray-400">Total Allocated Bandwidth</p>
                <p class="text-4xl font-bold text-teal-400 mt-2">{{ number_format($bandwidthUsage['total_allocated'] ?? 0) }} Mbps</p>
                <p class="text-sm text-gray-500 mt-2">Dari semua pelanggan aktif</p>
            </div>
            <div class="p-6 bg-gray-700/30 rounded-xl">
                <p class="text-gray-400 mb-4">Network Health</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">ODP Utilization</span>
                        @php
                            $odpUtil = $odpStats['total'] > 0 ? (($odpStats['total'] - $odpStats['available']) / $odpStats['total']) * 100 : 0;
                        @endphp
                        <span class="text-white font-bold">{{ number_format($odpUtil, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-600 rounded-full h-2">
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-2 rounded-full" style="width: {{ min($odpUtil, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
