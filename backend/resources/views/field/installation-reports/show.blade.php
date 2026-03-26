@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-red-400 bg-clip-text text-transparent">Detail Laporan</h1>
            <p class="text-gray-400 mt-1">{{ $installationReport->installation_date->format('d F Y') }}</p>
        </div>
        <a href="{{ route('installation-reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Informasi Instalasi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Work Order</p>
                        <p class="text-white font-medium">WO #{{ $installationReport->work_order_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Status</p>
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-{{ $installationReport->status_color }}-500/20 text-{{ $installationReport->status_color }}-400">
                            {{ $installationReport->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Waktu Mulai</p>
                        <p class="text-white">{{ $installationReport->start_time ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Waktu Selesai</p>
                        <p class="text-white">{{ $installationReport->end_time ?? '-' }}</p>
                    </div>
                    @if($installationReport->duration)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-400">Durasi</p>
                        <p class="text-white font-semibold">{{ $installationReport->duration }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Detail Pekerjaan</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Pekerjaan yang Dilakukan</p>
                        <p class="text-white">{{ $installationReport->work_performed }}</p>
                    </div>
                    @if($installationReport->issues_found)
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Masalah Ditemukan</p>
                        <div class="p-3 bg-red-500/10 border border-red-500/30 rounded-xl">
                            <p class="text-red-400">{{ $installationReport->issues_found }}</p>
                        </div>
                    </div>
                    @endif
                    @if($installationReport->resolution)
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Resolusi</p>
                        <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl">
                            <p class="text-emerald-400">{{ $installationReport->resolution }}</p>
                        </div>
                    </div>
                    @endif
                    @if($installationReport->notes)
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Catatan</p>
                        <p class="text-gray-300">{{ $installationReport->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Pelanggan</h3>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ strtoupper(substr($installationReport->customer->name ?? 'C', 0, 2)) }}</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold">{{ $installationReport->customer->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-400">{{ $installationReport->customer->phone ?? '' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Teknisi</h3>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ strtoupper(substr($installationReport->technician->name ?? 'T', 0, 2)) }}</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold">{{ $installationReport->technician->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-400">{{ $installationReport->technician->phone ?? '' }}</p>
                    </div>
                </div>
            </div>

            @if($installationReport->customer_rating)
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Rating Pelanggan</h3>
                <div class="flex items-center gap-2 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-8 h-8 {{ $i <= $installationReport->customer_rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                @if($installationReport->customer_feedback)
                <p class="text-gray-300 text-sm italic">"{{ $installationReport->customer_feedback }}"</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
