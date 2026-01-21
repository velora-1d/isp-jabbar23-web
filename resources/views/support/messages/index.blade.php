@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <!-- Header with Filters -->
        <x-filter-bar :filters="$filters ?? []">
            <x-slot name="global">
                <x-filter-global :search-placeholder="'Cari Pesan...'" />
            </x-slot>

            <x-slot name="filters">
                <x-filter-select name="channel" label="Kanal" :options="$channels" :selected="request('channel')" />
                <x-filter-select name="direction" label="Arah" :options="$directions" :selected="request('direction')" />
            </x-slot>

            <x-slot name="actions">
                <a href="{{ route('messages.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-green-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Kirim Pesan
                </a>
            </x-slot>
        </x-filter-bar>

        <!-- Messages Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Channel</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Pesan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($messages as $message)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                            <span
                                                class="text-white font-bold text-sm">{{ strtoupper(substr($message->customer->name ?? 'C', 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $message->customer->name ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-400">{{ $message->customer->phone ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($message->direction == 'inbound')
                                            <span class="text-cyan-400">↓</span>
                                        @else
                                            <span class="text-teal-400">↑</span>
                                        @endif
                                        <span
                                            class="px-2 py-1 rounded-lg text-xs font-medium bg-gray-700 text-gray-300 capitalize">{{ $message->channel }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-300 max-w-md truncate">
                                    {{ Str::limit($message->content, 80) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-xs font-medium bg-{{ $message->status_color }}-500/20 text-{{ $message->status_color }}-400 capitalize">
                                        {{ $message->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">
                                    {{ $message->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    <p>Belum ada pesan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-700/50">
                {{ $messages->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
