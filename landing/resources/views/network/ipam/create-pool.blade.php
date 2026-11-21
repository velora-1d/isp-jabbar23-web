@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">
                Buat IP Pool
            </h1>
            <p class="text-gray-400 mt-1">Definisikan range IP baru</p>
        </div>
        <a href="{{ route('network.ipam.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('network.ipam.pools.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Pool *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                        placeholder="Contoh: Pool Public 1">
                    @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipe *</label>
                    <select name="type" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="private" {{ old('type') == 'private' ? 'selected' : '' }}>Private</option>
                        <option value="public" {{ old('type') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="cgnat" {{ old('type') == 'cgnat' ? 'selected' : '' }}>CGNAT</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Network Address *</label>
                    <input type="text" name="network" value="{{ old('network') }}" required
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent font-mono"
                        placeholder="192.168.1.0">
                    @error('network')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Prefix (CIDR) *</label>
                    <select name="prefix" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="24" {{ old('prefix', 24) == 24 ? 'selected' : '' }}>/24 (254 hosts)</option>
                        <option value="25" {{ old('prefix') == 25 ? 'selected' : '' }}>/25 (126 hosts)</option>
                        <option value="26" {{ old('prefix') == 26 ? 'selected' : '' }}>/26 (62 hosts)</option>
                        <option value="27" {{ old('prefix') == 27 ? 'selected' : '' }}>/27 (30 hosts)</option>
                        <option value="28" {{ old('prefix') == 28 ? 'selected' : '' }}>/28 (14 hosts)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Gateway</label>
                    <input type="text" name="gateway" value="{{ old('gateway') }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent font-mono"
                        placeholder="192.168.1.1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">DNS Primary</label>
                    <input type="text" name="dns_primary" value="{{ old('dns_primary', '8.8.8.8') }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent font-mono"
                        placeholder="8.8.8.8">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="2"
                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                    placeholder="Catatan tentang pool ini...">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('network.ipam.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-500 hover:to-teal-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-cyan-500/25">
                    Buat Pool
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
