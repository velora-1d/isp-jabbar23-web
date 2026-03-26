@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 to-yellow-400 bg-clip-text text-transparent">Tulis Artikel</h1>
            <p class="text-gray-400 mt-1">Buat artikel bantuan baru</p>
        </div>
        <a href="{{ route('knowledge-base.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('knowledge-base.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Judul *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Cara Setting Router...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori *</label>
                    <select name="category" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <option value="getting-started">Memulai</option>
                        <option value="billing">Billing</option>
                        <option value="technical">Teknis</option>
                        <option value="troubleshooting">Troubleshooting</option>
                        <option value="faq">FAQ</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Konten *</label>
                <textarea name="content" rows="15" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent font-mono text-sm" placeholder="Tulis konten artikel di sini... (Markdown didukung)">{{ old('content') }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-amber-500 focus:ring-amber-500">
                <label for="is_published" class="text-sm text-gray-300">Publish artikel ini</label>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('knowledge-base.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-600 to-yellow-600 hover:from-amber-500 hover:to-yellow-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-amber-500/25">Simpan Artikel</button>
            </div>
        </form>
    </div>
</div>
@endsection
