@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">Detail Log</h1>
            <p class="text-gray-400 mt-1">{{ $auditLog->created_at->format('d F Y, H:i:s') }}</p>
        </div>
        <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Action Info -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Informasi Aktivitas</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Action</p>
                        <span class="inline-flex items-center gap-2 mt-1 px-3 py-1 rounded-lg text-sm font-medium bg-{{ $auditLog->action_color }}-500/20 text-{{ $auditLog->action_color }}-400">
                            {{ ucfirst($auditLog->action) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Model</p>
                        <p class="text-white font-medium">{{ $auditLog->model_name }} @if($auditLog->model_id)<span class="text-gray-400">#{{ $auditLog->model_id }}</span>@endif</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-400">Deskripsi</p>
                        <p class="text-white">{{ $auditLog->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Changes -->
            @if($auditLog->old_values || $auditLog->new_values)
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Perubahan Data</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($auditLog->old_values)
                    <div>
                        <p class="text-sm font-medium text-red-400 mb-2">Data Lama</p>
                        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                            <pre class="text-sm text-gray-300 whitespace-pre-wrap overflow-auto">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                    @endif
                    @if($auditLog->new_values)
                    <div>
                        <p class="text-sm font-medium text-emerald-400 mb-2">Data Baru</p>
                        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4">
                            <pre class="text-sm text-gray-300 whitespace-pre-wrap overflow-auto">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">User</h3>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ strtoupper(substr($auditLog->user_name ?? 'S', 0, 2)) }}</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold">{{ $auditLog->user_name ?? 'System' }}</p>
                        <p class="text-sm text-gray-400">{{ $auditLog->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Request Info -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">Request Info</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-400">IP Address</p>
                        <p class="text-white font-mono">{{ $auditLog->ip_address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Method</p>
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-blue-500/20 text-blue-400">{{ $auditLog->method ?? '-' }}</span>
                    </div>
                    <div>
                        <p class="text-gray-400">URL</p>
                        <p class="text-white text-xs break-all">{{ $auditLog->url ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">User Agent</p>
                        <p class="text-white text-xs break-all">{{ Str::limit($auditLog->user_agent, 100) ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
