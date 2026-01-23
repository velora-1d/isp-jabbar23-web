@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Network Monitoring</h1>
            <p class="text-gray-400 mt-1">Real-time Status Perangkat (ICMP Ping)</p>
        </div>
        <div>
            <span class="text-xs text-gray-500">Auto-refresh not active</span>
        </div>
    </div>

    <!-- OLT Monitoring Card -->
    <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden">
        <div class="p-4 border-b border-gray-700 bg-gray-900/30">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                OLT Devices
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($olts as $olt)
                <div class="bg-gray-900 border border-gray-700 rounded-lg p-5 hover:border-blue-500/50 transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="text-lg font-bold text-white">{{ $olt->name }}</div>
                            <div class="text-sm text-gray-400 font-mono mt-1">{{ $olt->ip_address }}</div>
                        </div>
                        <div id="badge-{{ $olt->id }}" class="px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded-full font-mono">
                            Unknown
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-4">
                        <div class="text-xs text-gray-500" id="latency-{{ $olt->id }}">Latency: -</div>
                        <button onclick="pingDevice({{ $olt->id }}, '{{ $olt->ip_address }}')" class="flex items-center px-3 py-1.5 bg-blue-600/20 text-blue-400 text-sm rounded hover:bg-blue-600/30 transition">
                            <span id="btn-text-{{ $olt->id }}">Check Status</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500 py-8">
                    Tidak ada perangkat OLT yang terdaftar.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function pingDevice(id, ip) {
        const btnText = document.getElementById(`btn-text-${id}`);
        const badge = document.getElementById(`badge-${id}`);
        const latencyEl = document.getElementById(`latency-${id}`);
        
        // Set loading state
        btnText.innerText = 'Pinging...';
        badge.className = 'px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full font-mono animate-pulse';
        badge.innerText = 'Checking...';

        fetch('{{ route("network.monitoring.ping") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ip: ip })
        })
        .then(response => response.json())
        .then(data => {
            if (data.online) {
                badge.className = 'px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs rounded-full font-mono font-bold';
                badge.innerText = 'ONLINE';
                latencyEl.innerText = 'Latency: ' + (data.latency || '<1ms');
                latencyEl.className = 'text-xs text-emerald-400';
            } else {
                badge.className = 'px-2 py-1 bg-red-500/20 text-red-400 text-xs rounded-full font-mono font-bold';
                badge.innerText = 'OFFLINE';
                latencyEl.innerText = 'Latency: Timeout';
                latencyEl.className = 'text-xs text-red-400';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            badge.className = 'px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded-full font-mono';
            badge.innerText = 'Error';
        })
        .finally(() => {
            btnText.innerText = 'Check Status';
        });
    }
</script>
@endsection
