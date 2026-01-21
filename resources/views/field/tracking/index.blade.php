@extends('layouts.app')

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            z-index: 1;
        }

        .leaflet-popup-content-wrapper {
            background: rgba(31, 41, 55, 0.95);
            color: white;
            border-radius: 12px;
        }

        .leaflet-popup-tip {
            background: rgba(31, 41, 55, 0.95);
        }

        .tech-marker {
            background: linear-gradient(135deg, #14b8a6, #06b6d4);
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .tech-marker.offline {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-teal-400 to-cyan-400 bg-clip-text text-transparent">GPS
                    Tracking</h1>
                <p class="text-gray-400 mt-1">Pantau lokasi teknisi secara real-time</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="centerMap()"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Map Container -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-4">
            <div id="map" class="w-full h-[500px] rounded-xl overflow-hidden"></div>
        </div>

        <!-- Technicians List -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="p-4 border-b border-gray-700/50 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Teknisi Aktif</h3>
                <span class="text-sm text-gray-400">{{ $technicians->where('is_active', true)->count() }} online</span>
            </div>
            <div class="divide-y divide-gray-700/50">
                @forelse($technicians as $tech)
                    <div class="p-4 hover:bg-gray-700/30 transition-colors flex items-center justify-between cursor-pointer"
                        onclick="focusTech({{ $tech->id }}, {{ $tech->latitude ?? '-6.2088' }}, {{ $tech->longitude ?? '106.8456' }})">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $tech->is_active ? 'from-teal-500 to-cyan-600' : 'from-gray-500 to-gray-600' }} flex items-center justify-center">
                                <span class="text-white font-bold">{{ strtoupper(substr($tech->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $tech->name }}</p>
                                <p class="text-sm text-gray-400">{{ $tech->phone ?? 'No phone' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium {{ $tech->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-500/20 text-gray-400' }}">
                                {{ $tech->is_active ? 'Online' : 'Offline' }}
                            </span>
                            <button class="p-2 bg-blue-600/20 hover:bg-blue-600/40 text-blue-400 rounded-lg transition-colors"
                                title="Locate">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">Tidak ada teknisi aktif.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map centered on Indonesia (or your city)
        const defaultLat = -6.2088; // Jakarta
        const defaultLng = 106.8456;
        const map = L.map('map').setView([defaultLat, defaultLng], 12);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);

        // Custom marker icon
        function createMarkerIcon(isOnline, initials) {
            return L.divIcon({
                className: 'custom-marker',
                html: `<div class="tech-marker ${isOnline ? '' : 'offline'}" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;font-weight:bold;color:white;font-size:14px;">${initials}</div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -20]
            });
        }

        // Store markers
        const markers = {};

        // Add technicians to map
        @foreach($technicians as $tech)
            @if($tech->latitude && $tech->longitude)
                (function () {
                    const lat = {{ $tech->latitude }};
                    const lng = {{ $tech->longitude }};
                    const initials = "{{ strtoupper(substr($tech->name, 0, 2)) }}";
                    const isOnline = {{ $tech->is_active ? 'true' : 'false' }};

                    const marker = L.marker([lat, lng], {
                        icon: createMarkerIcon(isOnline, initials)
                    }).addTo(map);

                    marker.bindPopup(`
                        <div class="text-center p-2">
                            <p class="font-bold text-lg">{{ $tech->name }}</p>
                            <p class="text-sm text-gray-300">{{ $tech->phone ?? 'No phone' }}</p>
                            <p class="text-xs mt-2 ${isOnline ? 'text-emerald-400' : 'text-gray-400'}">
                                ${isOnline ? '🟢 Online' : '⚫ Offline'}
                            </p>
                        </div>
                    `);

                    markers[{{ $tech->id }}] = marker;
                })();
            @endif
        @endforeach

            // Focus on technician
            function focusTech(id, lat, lng) {
                map.setView([lat, lng], 16);
                if (markers[id]) {
                    markers[id].openPopup();
                }
            }

        // Center map to fit all markers
        function centerMap() {
            const group = new L.featureGroup(Object.values(markers));
            if (Object.keys(markers).length > 0) {
                map.fitBounds(group.getBounds().pad(0.1));
            } else {
                map.setView([defaultLat, defaultLng], 12);
            }
        }

        // Initial fit to markers
        if (Object.keys(markers).length > 0) {
            centerMap();
        }
    </script>
@endpush
