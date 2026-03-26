@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">Buat Campaign</h1>
            <p class="text-gray-400 mt-1">Kampanye marketing baru</p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('campaigns.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Campaign *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white" placeholder="Promo Awal Tahun">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipe *</label>
                    <select name="type" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                        <option value="whatsapp">WhatsApp</option>
                        <option value="sms">SMS</option>
                        <option value="email">Email</option>
                        <option value="push">Push Notification</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Template Pesan *</label>
                <textarea name="message_template" rows="5" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white font-mono text-sm" placeholder="Halo {customer_name}, kami punya promo spesial untuk Anda!">{{ old('message_template') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Gunakan {customer_name}, {package_name}, {amount} untuk placeholder</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Jadwal (Opsional)</label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('campaigns.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-500 hover:to-teal-500 text-white font-semibold rounded-xl transition-all shadow-lg shadow-cyan-500/25">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
