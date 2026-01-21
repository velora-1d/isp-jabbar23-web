@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <!-- Header with Filters -->
        <x-filter-bar :filters="$filters ?? []">
            <x-slot name="global">
                <x-filter-global :search-placeholder="'Cari Credit Note...'" />
            </x-slot>

            <x-slot name="filters">
                <x-filter-select name="status" label="Status" :options="$statuses" :selected="request('status')" />
            </x-slot>

            <x-slot name="actions">
                <a href="{{ route('billing.credit-notes.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-rose-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Credit Note
                </a>
            </x-slot>
        </x-filter-bar>

        <!-- Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">No. Credit Note
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Jumlah</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Alasan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($creditNotes as $note)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('billing.credit-notes.show', $note) }}"
                                        class="text-rose-400 hover:text-rose-300 font-medium">
                                        {{ $note->credit_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center text-white text-sm font-semibold">
                                            {{ strtoupper(substr($note->customer->name ?? '-', 0, 1)) }}
                                        </div>
                                        <span class="text-white">{{ $note->customer->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-white font-medium">{{ $note->formatted_amount }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-500/20 text-gray-300">
                                        {{ $note->reason_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $note->status_color }}-500/20 text-{{ $note->status_color }}-400">
                                        {{ $note->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('billing.credit-notes.show', $note) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-600/20 hover:bg-rose-600/40 text-rose-400 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                    </svg>
                                    <p>Belum ada credit note</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($creditNotes->hasPages())
                <div class="px-6 py-4 border-t border-gray-700/50">
                    {{ $creditNotes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
