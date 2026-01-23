<x-app-layout>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <style>
        #map { height: 320px; }
        .leaflet-container { background: #0a0f18 !important; border-radius: 1rem; }
        .leaflet-tile { filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%); }
    </style>

    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('technicians.index') }}" class="p-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white">{{ $technician->name }}</h1>
                        <p class="text-sm text-gray-500">Technician Dashboard</p>
                    </div>
                </div>
                
                <!-- Status Badge -->
                @php
                    $status = $technician->computed_status;
                    $isActive = $technician->is_active;
                    
                    if (!$isActive) {
                        $badgeColor = 'gray';
                        $statusLabel = 'OFFLINE';
                    } elseif ($status === 'on_task') {
                        $badgeColor = 'amber';
                        $statusLabel = 'ON TASK';
                    } else {
                        $badgeColor = 'emerald';
                        $statusLabel = 'ONLINE';
                    }
                @endphp
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-{{ $badgeColor }}-500/10 text-{{ $badgeColor }}-400 border border-{{ $badgeColor }}-500/20">
                    <span class="w-2 h-2 rounded-full bg-{{ $badgeColor }}-500 {{ $isActive ? 'animate-pulse' : '' }}"></span>
                    {{ $statusLabel }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- LEFT: Profile & Actions -->
                <div class="space-y-6">
                    
                    <!-- Profile Card - Clean Design -->
                    <div class="bg-gray-800 rounded-2xl border border-gray-700/50 overflow-hidden">
                        <!-- Profile Content -->
                        <div class="p-6">
                            <!-- Avatar -->
                            <div class="flex justify-center mb-4">
                                @if($technician->photo)
                                    <img src="{{ $technician->photo_url }}" class="w-20 h-20 rounded-full object-cover ring-4 ring-gray-700">
                                @else
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-teal-600 flex items-center justify-center text-2xl font-bold text-white ring-4 ring-gray-700">
                                        {{ strtoupper(substr($technician->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Identity -->
                            <div class="text-center">
                                <h2 class="text-lg font-bold text-white">{{ $technician->name }}</h2>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $technician->email }}</p>
                                <p class="text-xs text-gray-600 font-mono mt-1">ID: {{ substr($technician->phone, -4) ?? '0000' }}</p>
                                
                                <!-- Status Badge -->
                                @if($isActive)
                                <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-xs text-emerald-400 font-medium">Online</span>
                                </div>
                                @else
                                <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-700/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                    <span class="text-xs text-gray-500 font-medium">Offline</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="border-t border-gray-700/50 p-3 space-y-2">
                            @if($technician->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $technician->phone) }}" target="_blank" 
                               class="flex items-center justify-center gap-2 w-full py-2.5 bg-gray-700/50 hover:bg-gray-700 text-gray-300 hover:text-white rounded-xl text-sm font-medium transition-all">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                WhatsApp
                            </a>
                            @endif
                            <a href="{{ route('technicians.edit', $technician) }}" 
                               class="flex items-center justify-center gap-2 w-full py-2.5 bg-gray-700/50 hover:bg-gray-700 text-gray-300 hover:text-white rounded-xl text-sm font-medium transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Profil
                            </a>
                        </div>
                    </div>

                    <!-- Shift Toggle -->
                    <form action="{{ route('technicians.toggleActive', $technician) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        @if(!$isActive)
                            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-900/30 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                MULAI SHIFT
                            </button>
                        @else
                            <button type="submit" class="w-full py-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 rounded-2xl font-semibold text-sm transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                                Akhiri Shift
                            </button>
                        @endif
                    </form>

                    <!-- Stats Cards - Dashboard Style -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Active Tasks -->
                        <div class="relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                            <div class="relative p-4 bg-gray-800 rounded-xl border border-gray-700/50">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="p-2 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 shadow-lg shadow-amber-500/30">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                </div>
                                <p class="text-2xl font-bold text-white mb-0.5">{{ $technician->current_tasks_count }}</p>
                                <p class="text-xs text-gray-400 font-medium">Tugas Aktif</p>
                            </div>
                        </div>
                        
                        <!-- Completed -->
                        <div class="relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                            <div class="relative p-4 bg-gray-800 rounded-xl border border-gray-700/50">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="p-2 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 shadow-lg shadow-emerald-500/30">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                </div>
                                <p class="text-2xl font-bold text-white mb-0.5">{{ $technician->completed_customers_count }}</p>
                                <p class="text-xs text-gray-400 font-medium">Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Map & Tasks (2 columns) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- GPS Map Card -->
                    <div class="bg-gray-800 rounded-2xl border border-gray-700/50 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-700/50 flex items-center justify-between">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Live Position
                            </h3>
                            <span class="flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                        </div>
                        <div class="p-3">
                            <div id="map"></div>
                        </div>
                    </div>

                    <!-- Active Tasks -->
                    <div class="bg-gray-800 rounded-2xl border border-gray-700/50 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-700/50">
                            <h3 class="font-bold text-white">Tugas Aktif</h3>
                        </div>
                        <div class="p-4">
                            @if($technician->currentTasks->count() > 0)
                                <div class="space-y-3">
                                    @foreach($technician->currentTasks as $index => $task)
                                    @php
                                        // Status color mapping
                                        $statusColors = [
                                            'registered' => ['bg' => 'gray', 'text' => 'gray'],
                                            'survey' => ['bg' => 'blue', 'text' => 'blue'],
                                            'approved' => ['bg' => 'indigo', 'text' => 'indigo'],
                                            'scheduled' => ['bg' => 'teal', 'text' => 'teal'],
                                            'installing' => ['bg' => 'amber', 'text' => 'amber'],
                                            'active' => ['bg' => 'emerald', 'text' => 'emerald'],
                                        ];
                                        $color = $statusColors[$task->status] ?? ['bg' => 'gray', 'text' => 'gray'];
                                    @endphp
                                    <div class="bg-gray-900/50 rounded-xl border border-gray-700/30 overflow-hidden hover:border-{{ $color['bg'] }}-500/30 transition-all">
                                        <!-- Status Header -->
                                        <div class="px-4 py-2 bg-{{ $color['bg'] }}-500/10 border-b border-{{ $color['bg'] }}-500/20 flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-{{ $color['bg'] }}-500 animate-pulse"></span>
                                                <span class="text-xs font-semibold text-{{ $color['text'] }}-400 uppercase tracking-wider">{{ $task->status_label }}</span>
                                            </div>
                                            <span class="text-[10px] text-gray-500 font-mono">#{{ $index + 1 }}</span>
                                        </div>
                                        
                                        <!-- Customer Info -->
                                        <div class="p-4">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <h4 class="font-bold text-white">{{ $task->name }}</h4>
                                                    <p class="text-xs text-gray-500 font-mono">{{ $task->customer_id }}</p>
                                                </div>
                                                @if($task->phone)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $task->phone) }}" target="_blank" class="p-2 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 transition-colors">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                </a>
                                                @endif
                                            </div>
                                            
                                            <!-- Address -->
                                            <p class="text-sm text-gray-400 mb-3 flex items-start gap-2">
                                                <svg class="w-4 h-4 text-gray-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                {{ Str::limit($task->full_address, 60) }}
                                            </p>
                                            
                                            <!-- Actions -->
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('customers.show', $task) }}" class="flex-1 text-center py-2 bg-gray-700/50 hover:bg-gray-700 text-gray-300 rounded-lg text-sm font-medium transition-colors">
                                                    Lihat Detail
                                                </a>
                                                @if($task->latitude && $task->longitude)
                                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $task->latitude }},{{ $task->longitude }}" target="_blank" class="p-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 rounded-lg transition-colors" title="Navigasi">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    <p class="font-medium">Tidak ada tugas aktif</p>
                                    <p class="text-sm text-gray-600 mt-1">Assign customer baru untuk memulai</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- History -->
                    <div class="bg-gray-800 rounded-2xl border border-gray-700/50 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-700/50">
                            <h3 class="font-bold text-white">Riwayat Terbaru</h3>
                        </div>
                        <div class="divide-y divide-gray-700/30">
                            @forelse($technician->assignedCustomers->take(5) as $customer)
                            @continue(in_array($customer->status, \App\Models\User::ACTIVE_CUSTOMER_STATUSES))
                            <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-700/20 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="font-medium text-gray-300">{{ Str::limit($customer->name, 25) }}</span>
                                </div>
                                <span class="text-xs text-gray-600">{{ $customer->updated_at->diffForHumans() }}</span>
                            </div>
                            @empty
                            <div class="px-5 py-8 text-center text-gray-600 text-sm">
                                Belum ada riwayat
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const defaultPos = [-6.9175, 107.6191];
            
            const techLat = {{ $technician->last_latitude ?? 'null' }};
            const techLng = {{ $technician->last_longitude ?? 'null' }};
            
            @php $currentCustomer = $technician->currentCustomer; @endphp
            const custLat = {{ $currentCustomer && $currentCustomer->latitude ? $currentCustomer->latitude : 'null' }};
            const custLng = {{ $currentCustomer && $currentCustomer->longitude ? $currentCustomer->longitude : 'null' }};

            const map = L.map('map').setView(techLat && techLng ? [techLat, techLng] : defaultPos, 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OSM'
            }).addTo(map);

            const markers = [];

            if (techLat && techLng) {
                const techIcon = L.divIcon({
                    className: '',
                    html: `<div class="w-6 h-6 bg-blue-600 rounded-full border-2 border-white shadow-lg flex items-center justify-center"><div class="w-2 h-2 bg-white rounded-full"></div></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });
                L.marker([techLat, techLng], {icon: techIcon}).addTo(map)
                    .bindPopup('<b>{{ $technician->name }}</b>');
                markers.push([techLat, techLng]);
            }

            if (custLat && custLng) {
                const custIcon = L.divIcon({
                    className: '',
                    html: `<div class="w-6 h-6 bg-red-600 rounded-full border-2 border-white shadow-lg flex items-center justify-center"><div class="w-2 h-2 bg-white rounded-full"></div></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });
                L.marker([custLat, custLng], {icon: custIcon}).addTo(map)
                    .bindPopup('<b>{{ $currentCustomer->name ?? "Target" }}</b>');
                markers.push([custLat, custLng]);

                if (techLat && techLng) {
                    L.polyline([[techLat, techLng], [custLat, custLng]], {
                        color: '#3b82f6',
                        weight: 2,
                        dashArray: '8, 8',
                        opacity: 0.7
                    }).addTo(map);
                }
            }

            if (markers.length > 0) {
                map.fitBounds(L.latLngBounds(markers), { padding: [40, 40] });
            }
        });
    </script>
</x-app-layout>
