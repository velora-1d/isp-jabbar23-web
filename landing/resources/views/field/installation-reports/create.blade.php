@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-red-400 bg-clip-text text-transparent">Buat Laporan</h1>
            <p class="text-gray-400 mt-1">Laporan instalasi baru</p>
        </div>
        <a href="{{ route('installation-reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('installation-reports.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Work Order *</label>
                    <select name="work_order_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">-- Pilih Work Order --</option>
                        @foreach($workOrders as $wo)
                        <option value="{{ $wo->id }}" {{ old('work_order_id', $workOrder?->id) == $wo->id ? 'selected' : '' }}>
                            WO #{{ $wo->id }} - {{ $wo->customer->name ?? 'Unknown' }} - {{ $wo->type }}
                        </option>
                        @endforeach
                    </select>
                    @error('work_order_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Instalasi *</label>
                    <input type="date" name="installation_date" value="{{ old('installation_date', date('Y-m-d')) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Waktu Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Waktu Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status *</label>
                    <select name="status" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="rescheduled" {{ old('status') == 'rescheduled' ? 'selected' : '' }}>Dijadwalkan Ulang</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Pekerjaan yang Dilakukan *</label>
                <textarea name="work_performed" rows="3" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Jelaskan pekerjaan yang dilakukan...">{{ old('work_performed') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Masalah Ditemukan</label>
                    <textarea name="issues_found" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Masalah yang ditemukan...">{{ old('issues_found') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Resolusi</label>
                    <textarea name="resolution" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Bagaimana masalah diselesaikan...">{{ old('resolution') }}</textarea>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan Tambahan</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Catatan...">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('installation-reports.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-500 hover:to-red-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-orange-500/25">Simpan Laporan</button>
            </div>
        </form>
    </div>
</div>
@endsection
