@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-yellow-400 to-lime-400 bg-clip-text text-transparent">Tambah SLA</h1>
            <p class="text-gray-400 mt-1">Buat policy SLA baru</p>
        </div>
        <a href="{{ route('sla.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('sla.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white" placeholder="SLA Critical">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Prioritas *</label>
                    <select name="priority" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                        <option value="low">Rendah</option>
                        <option value="medium" selected>Sedang</option>
                        <option value="high">Tinggi</option>
                        <option value="critical">Kritis</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">First Response (Jam) *</label>
                    <input type="number" name="first_response_hours" value="{{ old('first_response_hours', 4) }}" required min="1" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Resolution (Jam) *</label>
                    <input type="number" name="resolution_hours" value="{{ old('resolution_hours', 24) }}" required min="1" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400">{{ old('description') }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-yellow-500">
                <label for="is_active" class="text-sm text-gray-300">Aktifkan SLA ini</label>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('sla.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-lime-600 hover:from-yellow-500 hover:to-lime-500 text-white font-semibold rounded-xl transition-all shadow-lg shadow-yellow-500/25">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
