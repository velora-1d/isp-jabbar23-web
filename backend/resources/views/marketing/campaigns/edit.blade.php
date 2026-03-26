@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">Edit Campaign</h1>
            <p class="text-gray-400 mt-1">{{ $campaign->name }}</p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('campaigns.update', $campaign) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Campaign *</label>
                    <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required class="w-full bg-gray-700/50 border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent {{ $errors->has('name') ? 'border-red-500' : 'border-gray-600' }}">
                    @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Type *</label>
                    <select name="type" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500">
                        <option value="whatsapp" {{ $campaign->type === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="sms" {{ $campaign->type === 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="email" {{ $campaign->type === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="push" {{ $campaign->type === 'push' ? 'selected' : '' }}>Push Notification</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500">{{ old('description', $campaign->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Template Pesan *</label>
                <textarea name="message_template" rows="6" required class="w-full bg-gray-700/50 border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 font-mono text-sm {{ $errors->has('message_template') ? 'border-red-500' : 'border-gray-600' }}">{{ old('message_template', $campaign->message_template) }}</textarea>
                <p class="text-gray-500 text-sm mt-1">Gunakan placeholder: {name}, {invoice_number}, {amount}</p>
                @error('message_template')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Jadwalkan (Opsional)</label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i')) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500">
                <p class="text-gray-500 text-sm mt-1">Kosongkan untuk simpan sebagai draft</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-700">
                <a href="{{ route('campaigns.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-500 hover:to-teal-500 text-white font-semibold rounded-xl transition-all shadow-lg shadow-cyan-500/25">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
