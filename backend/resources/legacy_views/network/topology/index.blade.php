@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-400 to-teal-400 bg-clip-text text-transparent">
                Network Topology
            </h1>
            <p class="text-gray-400 mt-1">Visualisasi jaringan infrastruktur</p>
        </div>
        <div class="flex gap-2">
            <button onclick="fitNetwork()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                Fit View
            </button>
            <button onclick="refreshTopology()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-4">
        <div class="flex flex-wrap gap-6 justify-center">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-emerald-500"></div>
                <span class="text-gray-300 text-sm">Router (Online)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-red-500"></div>
                <span class="text-gray-300 text-sm">Router (Offline)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-blue-500"></div>
                <span class="text-gray-300 text-sm">OLT</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-cyan-500"></div>
                <span class="text-gray-300 text-sm">ODP</span>
            </div>
        </div>
    </div>

    <!-- Network Canvas -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden" style="height: 600px;">
        <div id="network-topology" class="w-full h-full"></div>
    </div>

    <!-- Node Info -->
    <div id="node-info" class="hidden bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <h3 class="text-lg font-semibold text-white mb-2" id="node-title">-</h3>
        <p class="text-gray-400" id="node-details">-</p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
<script>
let network = null;

async function loadTopology() {
    try {
        const response = await fetch('{{ route("network.topology.data") }}');
        const data = await response.json();
        
        const container = document.getElementById('network-topology');
        
        const nodes = new vis.DataSet(data.nodes);
        const edges = new vis.DataSet(data.edges);
        
        const options = {
            nodes: {
                font: {
                    color: '#fff',
                    size: 14,
                },
                borderWidth: 2,
                shadow: true,
            },
            edges: {
                width: 2,
                shadow: true,
                smooth: {
                    type: 'continuous',
                },
            },
            groups: {
                router: {
                    shape: 'box',
                    color: {
                        border: '#10B981',
                        background: '#065F46',
                    },
                },
                olt: {
                    shape: 'database',
                    color: {
                        border: '#3B82F6',
                        background: '#1E40AF',
                    },
                },
                odp: {
                    shape: 'dot',
                    color: {
                        border: '#8B5CF6',
                        background: '#5B21B6',
                    },
                },
            },
            physics: {
                enabled: true,
                barnesHut: {
                    gravitationalConstant: -3000,
                    springLength: 150,
                },
            },
            interaction: {
                hover: true,
                tooltipDelay: 100,
            },
        };
        
        network = new vis.Network(container, { nodes, edges }, options);
        
        network.on('selectNode', function(params) {
            const node = nodes.get(params.nodes[0]);
            if (node) {
                document.getElementById('node-info').classList.remove('hidden');
                document.getElementById('node-title').textContent = node.label;
                document.getElementById('node-details').textContent = node.title || 'No details';
            }
        });
        
        network.on('deselectNode', function() {
            document.getElementById('node-info').classList.add('hidden');
        });
        
    } catch (error) {
        console.error('Failed to load topology:', error);
    }
}

function fitNetwork() {
    if (network) {
        network.fit();
    }
}

function refreshTopology() {
    loadTopology();
}

document.addEventListener('DOMContentLoaded', loadTopology);
</script>
@endpush
