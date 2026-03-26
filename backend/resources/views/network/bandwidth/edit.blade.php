@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-amber-400 bg-clip-text text-transparent">Edit Bandwidth Plan</h1>
            <p class="text-gray-400 mt-1">{{ $bandwidth->name }} ({{ $bandwidth->code }})</p>
        </div>
        <a href="{{ route('network.bandwidth.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('network.bandwidth.update', $bandwidth) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Plan *</label>
                    <input type="text" name="name" value="{{ old('name', $bandwidth->name) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kode *</label>
                    <input type="text" name="code" value="{{ old('code', $bandwidth->code) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent font-mono">
                    @error('code')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Download Speed (Mbps) *</label>
                    <input type="number" name="download_speed" value="{{ old('download_speed', $bandwidth->download_speed) }}" required min="1" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Upload Speed (Mbps) *</label>
                    <input type="number" name="upload_speed" value="{{ old('upload_speed', $bandwidth->upload_speed) }}" required min="1" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Burst Download (Mbps)</label>
                    <input type="number" name="burst_download" value="{{ old('burst_download', $bandwidth->burst_download) }}" min="1" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Burst Upload (Mbps)</label>
                    <input type="number" name="burst_upload" value="{{ old('burst_upload', $bandwidth->burst_upload) }}" min="1" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Priority (1-8) *</label>
                    <input type="number" name="priority" value="{{ old('priority', $bandwidth->priority) }}" required min="1" max="8" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div class="flex items-center">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bandwidth->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded bg-gray-700 border-gray-600 text-orange-500 focus:ring-orange-500">
                        <span class="text-gray-300">Aktif</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ old('description', $bandwidth->description) }}</textarea>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('network.bandwidth.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-500 hover:to-amber-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-orange-500/25">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
