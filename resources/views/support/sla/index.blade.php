@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header with Filters -->
        <x-filter-bar :filters="$filters ?? []">
            <x-slot name="global">
                <x-filter-global :search-placeholder="'Cari SLA...'" :show-date-range="false" />
            </x-slot>

            <x-slot name="filters">
                <x-filter-select name="priority" label="Prioritas" :options="$priorities" :selected="request('priority')" />
            </x-slot>

            <x-slot name="actions">
                <a href="{{ route('sla.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-600 to-lime-600 hover:from-yellow-500 hover:to-lime-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-yellow-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah SLA
                </a>
            </x-slot>
        </x-filter-bar>

        @if(session('success'))
            <div
                class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-yellow-500 to-lime-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Kebijakan</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Aktif</p>
                    <p class="text-2xl font-bold text-emerald-400">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Prioritas</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-400 uppercase">First Response
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-400 uppercase">Resolution</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($policies as $policy)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-white">{{ $policy->name }}</p>
                                    @if($policy->description)
                                    <p class="text-sm text-gray-400">{{ Str::limit($policy->description, 50) }}</p>@endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-{{ $policy->priority_color }}-500/20 text-{{ $policy->priority_color }}-400">{{ $policy->priority_label }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-white font-medium">{{ $policy->first_response_hours }}h
                                </td>
                                <td class="px-6 py-4 text-center text-white font-medium">{{ $policy->resolution_hours }}h</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium {{ $policy->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-500/20 text-gray-400' }}">
                                        {{ $policy->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('sla.edit', $policy) }}"
                                            class="p-2 bg-blue-600/20 hover:bg-blue-600/40 text-blue-400 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('sla.destroy', $policy) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Hapus SLA ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada SLA policy.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
