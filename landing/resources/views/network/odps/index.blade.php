@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <x-filter-bar :filters="$filters ?? []">
                        <x-slot name="global">
                            <x-filter-global :search-placeholder="'Cari ODP...'" />
                        </x-slot>

                        <x-slot name="filters">
                            <x-filter-select name="status" label="Status" :options="$statuses"
                                :selected="request('status')" />
                        </x-slot>

                        <x-slot name="actions">
                            <a href="{{ route('network.odps.create') }}"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah ODP Baru
                            </a>
                        </x-slot>
                    </x-filter-bar>

                    <!-- Map Visualization -->
                    <div id="odpMap" class="w-full h-96 mb-8 rounded-lg z-0 relative"></div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3">Nama</th>
                                    <th class="px-6 py-3">Lokasi</th>
                                    <th class="px-6 py-3">Port</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($odps as $odp)
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                        <td class="px-6 py-4 font-medium text-white">
                                            {{ $odp->name }}
                                            <div class="text-xs text-gray-500">{{ $odp->address }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $odp->coordinates }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-900 text-blue-300">
                                                {{ $odp->total_ports }} Ports
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $colors = [
                                                    'active' => 'green',
                                                    'maintenance' => 'yellow',
                                                    'full' => 'red'
                                                ];
                                                $color = $colors[$odp->status] ?? 'gray';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-{{ $color }}-900 text-{{ $color }}-300 capitalize">
                                                {{ $odp->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 flex gap-2">
                                            <a href="{{ route('network.odps.edit', $odp) }}"
                                                class="text-blue-400 hover:text-blue-300">Edit</a>
                                            <form action="{{ route('network.odps.destroy', $odp) }}" method="POST"
                                                onsubmit="return confirm('Delete ODP?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">No ODP found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $odps->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            .leaflet-container {
                font-family: inherit;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Init Map
                var map = L.map('odpMap').setView([-6.200000, 106.816666], 13); // Default Jakarta (nanti ganti default via config)

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                var odps = @json($odps->items());
                var markers = [];

                odps.forEach(function (odp) {
                    if (odp.latitude && odp.longitude) {
                        var marker = L.marker([odp.latitude, odp.longitude])
                            .addTo(map)
                            .bindPopup(`<b>${odp.name}</b><br>${odp.status}<br>${odp.total_ports} Ports`);
                        markers.push(marker);
                    }
                });

                // Fit bounds if markers exist
                if (markers.length > 0) {
                    var group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            });
        </script>
    @endpush
@endsection
