@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        
        <!-- Welcome & Status -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Halo, {{ $technician->name }}!</h1>
                <p class="text-gray-400 text-sm mt-1">
                    @if($technician->is_active)
                        <span class="text-emerald-400 font-medium">● Sedang Bertugas (On Duty)</span>
                    @else
                        <span class="text-gray-500 font-medium">● Sedang Istirahat (Off Duty)</span>
                    @endif
                </p>
            </div>
            
            <form action="{{ route('technicians.toggleActive', $technician) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full md:w-auto flex items-center justify-center px-4 py-2 rounded-xl text-sm font-semibold shadow-lg transition-all {{ $technician->is_active ? 'bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500/20' : 'bg-emerald-500 text-white hover:bg-emerald-600' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    {{ $technician->is_active ? 'Matikan Status Aktif' : 'Mulai Bertugas' }}
                </button>
            </form>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Pending -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Tugas Pending</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $stats['pending_tasks'] }}</p>
                            <p class="text-xs text-amber-400 mt-2">Menunggu tindakan</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Today -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Selesai Hari Ini</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $stats['completed_today'] }}</p>
                            <p class="text-xs text-blue-400 mt-2">Kerja bagus!</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Month -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total Selesai (Bulan Ini)</p>
                            <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $stats['completed_month'] }}</p>
                            <p class="text-xs text-emerald-500 mt-2">Kinerja Bulanan</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tasks Section -->
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Daftar Tugas (Active)
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activeTasks as $task)
                <div class="bg-gray-800 rounded-2xl border border-gray-700/50 overflow-hidden hover:border-gray-600 transition-colors group">
                    <div class="p-5">
                         <!-- Header: Status & Priority -->
                        <div class="flex justify-between items-start mb-4">
                            @php
                                $statusColors = [
                                    'survey' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'approved' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'scheduled' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                    'installing' => 'bg-pink-500/10 text-pink-500 border-pink-500/20',
                                ];
                                $colorClass = $statusColors[$task->status] ?? 'bg-gray-500/10 text-gray-500 border-gray-500/20';
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $colorClass }}">
                                {{ strtoupper($task->status) }}
                            </span>
                            <span class="text-xs text-gray-500 font-mono">#{{ $task->customer_id }}</span>
                        </div>

                        <!-- Customer Details -->
                        <h3 class="text-lg font-bold text-white mb-1">{{ $task->name }}</h3>
                        <p class="text-gray-400 text-sm mb-3 limit-text">{{ $task->address }}</p>
                        
                        <div class="flex items-center text-sm text-gray-300 mb-4 bg-gray-900/50 p-2 rounded-lg">
                             <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                             {{ $task->package->name ?? 'No Package' }}
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-3">
                             <a href="https://www.google.com/maps/search/?api=1&query={{ $task->latitude }},{{ $task->longitude }}" target="_blank" class="flex items-center justify-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-xl transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Rute
                            </a>
                            <a href="{{ route('customers.show', $task) }}" class="flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                                Update
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                    <!-- Footer Info (Optional) -->
                    @if($task->install_date)
                     <div class="px-5 py-3 bg-gray-900/30 border-t border-gray-700/50 text-xs text-gray-400 flex justify-between items-center">
                        <span>Jadwal:</span>
                        <span class="text-white font-medium">{{ \Carbon\Carbon::parse($task->install_date)->format('d M, H:i') }}</span>
                     </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 mb-4">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-lg font-medium text-gray-400">Tidak ada tugas aktif saat ini. Great job!</p>
                    <p class="text-sm mt-1">Istirahatlah sejenak atau minta assignment baru ke Admin.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

<style>
    .limit-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
