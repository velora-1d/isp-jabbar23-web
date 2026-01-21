@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <!-- Header with Filters -->
        <x-filter-bar :filters="$filters ?? []">
            <x-slot name="global">
                <x-filter-global :search-placeholder="'Cari Log...'" />
            </x-slot>

            <x-slot name="filters">
                <x-filter-select name="action" label="Aksi" :options="$actions" :selected="request('action')" />
                <x-filter-select name="user_id" label="Pengguna" :options="$users->pluck('name', 'id')"
                    :selected="request('user_id')" />
                <input type="text" name="model_type" value="{{ request('model_type') }}" placeholder="Tipe Model"
                    class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-cyan-500">
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-cyan-500">
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-cyan-500">
            </x-slot>

            <x-slot name="actions">
                <form action="{{ route('audit-logs.clear') }}" method="POST"
                    onsubmit="return confirm('Hapus log lebih dari 90 hari?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="days" value="90">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Log Lama
                    </button>
                </form>
            </x-slot>
        </x-filter-bar>

        <!-- Logs Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Action</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Model</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Deskripsi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">IP
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm text-white">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center">
                                            <span
                                                class="text-white font-bold text-xs">{{ strtoupper(substr($log->user_name ?? 'S', 0, 2)) }}</span>
                                        </div>
                                        <span class="text-white text-sm">{{ $log->user_name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-xs font-medium bg-{{ $log->action_color }}-500/20 text-{{ $log->action_color }}-400">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-300 text-sm">
                                    {{ $log->model_name }}
                                    @if($log->model_id)
                                        <span class="text-gray-500">#{{ $log->model_id }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-300 text-sm max-w-xs truncate">
                                    {{ $log->description }}
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-sm font-mono">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('audit-logs.show', $log) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-cyan-600/20 hover:bg-cyan-600/40 text-cyan-400 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Belum ada log aktivitas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-700/50">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
