@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">Edit ODP: {{ $odp->name }}</h2>
                    <a href="{{ route('network.odps.index') }}" class="text-gray-400 hover:text-white">
                        &larr; Back
                    </a>
                </div>

                <form action="{{ route('network.odps.update', $odp) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Left Column: Form -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">ODP Name</label>
                                <input type="text" name="name" value="{{ old('name', $odp->name) }}" required 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Total Ports</label>
                                <select name="total_ports" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="8" {{ $odp->total_ports == 8 ? 'selected' : '' }}>8 Ports</option>
                                    <option value="16" {{ $odp->total_ports == 16 ? 'selected' : '' }}>16 Ports</option>
                                    <option value="24" {{ $odp->total_ports == 24 ? 'selected' : '' }}>24 Ports</option>
                                    <option value="48" {{ $odp->total_ports == 48 ? 'selected' : '' }}>48 Ports</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Status</label>
                                <select name="status" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="active" {{ $odp->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ $odp->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="full" {{ $odp->status == 'full' ? 'selected' : '' }}>Full</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Address / Description</label>
                                <textarea name="address" rows="3" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">{{ old('address', $odp->address) }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-gray-400">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" required readonly value="{{ old('latitude', $odp->latitude) }}"
                                        class="mt-1 block w-full bg-gray-700 border-transparent rounded-md text-gray-300 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" required readonly value="{{ old('longitude', $odp->longitude) }}"
                                        class="mt-1 block w-full bg-gray-700 border-transparent rounded-md text-gray-300 text-sm">
                                </div>
                            </div>
                            <p class="text-xs text-blue-400">* Move pin to update location</p>
                        </div>

                        <!-- Right Column: Map -->
                        <div class="h-96 bg-gray-700 rounded-lg overflow-hidden relative z-0">
                            <div id="mapPicker" class="w-full h-full z-0"></div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold">
                            Update ODP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var lat = {{ $odp->latitude ?? -6.200000 }};
        var lng = {{ $odp->longitude ?? 106.816666 }};
        
        var map = L.map('mapPicker').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([lat, lng], {draggable: true}).addTo(map);

        // Update inputs on drag
        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat.toFixed(8);
            document.getElementById('longitude').value = position.lng.toFixed(8);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat.toFixed(8);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(8);
        });
    });
</script>
@endpush
@endsection
