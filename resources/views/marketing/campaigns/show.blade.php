@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">Detail Campaign</h1>
            <p class="text-gray-400 mt-1">{{ $campaign->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($campaign->status === 'draft' || $campaign->status === 'scheduled')
            <a href="{{ route('campaigns.edit', $campaign) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('campaigns.launch', $campaign) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-colors">
                    🚀 Launch
                </button>
            </form>
            @endif
            <a href="{{ route('campaigns.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Campaign Info -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Informasi Campaign</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Type</p>
                        <p class="text-white font-medium capitalize">{{ $campaign->type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Status</p>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-{{ $campaign->status_color }}-500/20 text-{{ $campaign->status_color }}-400">
                            {{ $campaign->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Dibuat Oleh</p>
                        <p class="text-white">{{ $campaign->creator->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Tanggal Dibuat</p>
                        <p class="text-white">{{ $campaign->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @if($campaign->scheduled_at)
                    <div>
                        <p class="text-sm text-gray-400">Dijadwalkan</p>
                        <p class="text-white">{{ $campaign->scheduled_at->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                    @if($campaign->started_at)
                    <div>
                        <p class="text-sm text-gray-400">Dimulai</p>
                        <p class="text-white">{{ $campaign->started_at->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                </div>
                @if($campaign->description)
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <p class="text-sm text-gray-400 mb-2">Deskripsi</p>
                    <p class="text-gray-300">{{ $campaign->description }}</p>
                </div>
                @endif
            </div>

            <!-- Message Template -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Template Pesan</h3>
                <div class="bg-gray-900/50 rounded-xl p-4 border border-gray-700">
                    <pre class="text-gray-300 whitespace-pre-wrap text-sm">{{ $campaign->message_template }}</pre>
                </div>
            </div>
        </div>

        <!-- Stats Sidebar -->
        <div class="space-y-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Statistik</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Total Penerima</span>
                        <span class="text-white font-bold">{{ number_format($campaign->total_recipients) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Terkirim</span>
                        <span class="text-emerald-400 font-bold">{{ number_format($campaign->sent_count) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Delivered</span>
                        <span class="text-blue-400 font-bold">{{ number_format($campaign->delivered_count) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Gagal</span>
                        <span class="text-red-400 font-bold">{{ number_format($campaign->failed_count) }}</span>
                    </div>
                    @if($campaign->total_recipients > 0)
                    <div class="pt-4 border-t border-gray-700">
                        <p class="text-sm text-gray-400 mb-2">Delivery Rate</p>
                        <div class="w-full bg-gray-700 rounded-full h-3">
                            <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $campaign->delivery_rate }}%"></div>
                        </div>
                        <p class="text-right text-sm text-emerald-400 mt-1">{{ number_format($campaign->delivery_rate, 1) }}%</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($campaign->status === 'running')
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Aksi</h3>
                <form action="{{ route('campaigns.cancel', $campaign) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 hover:bg-red-500 text-white rounded-xl transition-colors" onclick="return confirm('Batalkan campaign ini?')">
                        Batalkan Campaign
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
