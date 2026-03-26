@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-teal-400 to-cyan-400 bg-clip-text text-transparent">Tambah Vendor</h1>
            <p class="text-gray-400 mt-1">Daftarkan vendor/supplier baru</p>
        </div>
        <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('vendors.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Vendor *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="PT. Supplier ABC">
                    @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kode *</label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent font-mono" placeholder="VND-001">
                    @error('code')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipe *</label>
                    <select name="type" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="equipment" {{ old('type') == 'equipment' ? 'selected' : '' }}>Peralatan</option>
                        <option value="consumable" {{ old('type') == 'consumable' ? 'selected' : '' }}>Consumable</option>
                        <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Jasa</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status *</label>
                    <select name="status" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Nama kontak">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="08xx-xxxx-xxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="vendor@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Jakarta">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                <textarea name="address" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Alamat lengkap vendor">{{ old('address') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="BCA">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">No. Rekening</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent font-mono" placeholder="1234567890">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">NPWP</label>
                    <input type="text" name="npwp" value="{{ old('npwp') }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent font-mono" placeholder="00.000.000.0-000.000">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('vendors.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-500 hover:to-cyan-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-teal-500/25">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
