@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <x-filter-bar :filters="$filters ?? []">
                        <x-slot name="global">
                            <x-filter-global :search-placeholder="'Cari OLT...'" />
                        </x-slot>

                        <x-slot name="filters">
                            <x-filter-select name="status" label="Status" :options="$statuses"
                                :selected="request('status')" />
                            <x-filter-select name="type" label="Tipe" :options="$types" :selected="request('type')" />
                        </x-slot>

                        <x-slot name="actions">
                            <a href="{{ route('network.olts.create') }}"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah OLT Baru
                            </a>
                        </x-slot>
                    </x-filter-bar>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3">Nama</th>
                                    <th class="px-6 py-3">Alamat IP</th>
                                    <th class="px-6 py-3">Tipe / Merek</th>
                                    <th class="px-6 py-3">Port PON</th>
                                    <th class="px-6 py-3">Lokasi</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($olts as $olt)
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                        <td class="px-6 py-4 font-medium text-white">
                                            {{ $olt->name }}
                                        </td>
                                        <td class="px-6 py-4 font-mono text-indigo-400">
                                            {{ $olt->ip_address ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $olt->type }} <span class="text-xs text-gray-500">({{ $olt->brand }})</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-900 text-blue-300">
                                                {{ $olt->total_pon_ports }} PON
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $olt->location ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $colors = [
                                                    'active' => 'green',
                                                    'offline' => 'red',
                                                    'maintenance' => 'yellow'
                                                ];
                                                $color = $colors[$olt->status] ?? 'gray';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-{{ $color }}-900 text-{{ $color }}-300 capitalize">
                                                {{ $olt->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 flex gap-2">
                                            <a href="{{ route('network.olts.edit', $olt) }}"
                                                class="text-blue-400 hover:text-blue-300">Edit</a>
                                            <form action="{{ route('network.olts.destroy', $olt) }}" method="POST"
                                                onsubmit="return confirm('Delete OLT? This might affect linked ODPs.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center">No OLT found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $olts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
