@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('leads.index') }}" class="p-2 bg-gray-800 border border-gray-700 rounded-lg text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $lead->name }}</h1>
                <p class="text-gray-400 mt-1">{{ $lead->lead_number }} &bull; <span class="text-{{ $lead->status_color }}-400">{{ $lead->status_label }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('leads.edit', $lead) }}" class="px-4 py-2 bg-gray-700 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                Edit Lead
            </a>
            @if(!in_array($lead->status, ['won', 'lost']))
            <form action="{{ route('leads.convert', $lead) }}" method="POST" onsubmit="return confirm('Konversi lead ini menjadi customer?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-600 transition shadow-lg shadow-emerald-500/25">
                    Konversi ke Customer
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM13 14a3 3 0 00-3-3H8a3 3 0 00-3 3v3h10v-3zM17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/></svg>
                    Informasi Lead
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <div class="text-sm text-gray-400">Email</div>
                        <div class="text-white">{{ $lead->email ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Telepon</div>
                        <div class="text-white">{{ $lead->phone ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Sumber</div>
                        <div class="text-white">
                            <span class="px-2 py-0.5 bg-gray-700 text-gray-300 text-xs rounded">{{ $lead->source_label }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Paket Diminati</div>
                        <div class="text-white">{{ $lead->interestedPackage->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Assigned To</div>
                        <div class="text-white">{{ $lead->assignedTo->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400">Tanggal Daftar</div>
                        <div class="text-white">{{ $lead->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Lokasi & Alamat
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-400">Alamat Lengkap</div>
                        <div class="text-white">{{ $lead->full_address }}</div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-sm text-gray-400">RT/RW</div>
                            <div class="text-white">{{ $lead->rt_rw ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Kelurahan</div>
                            <div class="text-white">{{ $lead->kelurahan ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Kecamatan</div>
                            <div class="text-white">{{ $lead->kecamatan ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Kode Pos</div>
                            <div class="text-white">{{ $lead->kode_pos ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Catatan Internal</h3>
                <div class="text-gray-300 text-sm whitespace-pre-line bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                    {{ $lead->notes ?: 'Tidak ada catatan.' }}
                </div>
            </div>

            @if($lead->customer_id)
            <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-emerald-400 mb-2 whitespace-nowrap">Sudah Dikonversi</h3>
                <p class="text-emerald-300/80 text-sm mb-4">Lead ini telah menjadi pelanggan tetap.</p>
                <a href="{{ route('customers.show', $lead->customer_id) }}" class="block text-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                    Lihat Profil Customer
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
