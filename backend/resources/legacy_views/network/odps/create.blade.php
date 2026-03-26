@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">Add New ODP</h2>
                    <a href="{{ route('network.odps.index') }}" class="text-gray-400 hover:text-white">
                        &larr; Back
                    </a>
                </div>

                <form action="{{ route('network.odps.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Left Column: Form -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">ODP Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100"
                                    placeholder="e.g. ODP-JBR-001">
                                @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Total Ports</label>
                                <select name="total_ports" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="8">8 Ports</option>
                                    <option value="16">16 Ports</option>
                                    <option value="24">24 Ports</option>
                                    <option value="48">48 Ports</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Status</label>
                                <select name="status" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="active">Active</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="full">Full</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Address / Description</label>
                                <textarea name="address" rows="3" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">{{ old('address') }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-gray-400">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" required readonly
                                        class="mt-1 block w-full bg-gray-700 border-transparent rounded-md text-gray-300 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" required readonly
                                        class="mt-1 block w-full bg-gray-700 border-transparent rounded-md text-gray-300 text-sm">
                                </div>
                            </div>
                            <p class="text-xs text-blue-400">* Click on map to set location</p>
                        </div>

                        <!-- Right Column: Map -->
                        <div class="h-96 bg-gray-700 rounded-lg overflow-hidden relative z-0">
                            <div id="mapPicker" class="w-full h-full z-0"></div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold">
                            Save ODP
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
        // Default View (Jakarta) - Change to local ISP area if needed
        var map = L.map('mapPicker').setView([-6.200000, 106.816666], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker;

        // Try to get user location
        if (navigator.geolocation) {
             navigator.geolocation.getCurrentPosition(function(position) {
                 var lat = position.coords.latitude;
                 var lng = position.coords.longitude;
                 map.setView([lat, lng], 15);
             });
        }

        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
        });
    });
</script>
@endpush
@endsection
