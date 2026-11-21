@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Edit Asset</h1>
            <p class="text-gray-400 mt-1">{{ $asset->name }} ({{ $asset->code }})</p>
        </div>
        <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('assets.update', $asset) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Asset *</label>
                    <input type="text" name="name" value="{{ old('name', $asset->name) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kode *</label>
                    <input type="text" name="code" value="{{ old('code', $asset->code) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono">
                    @error('code')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Vendor</label>
                    <select name="vendor_id" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id', $asset->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori *</label>
                    <select name="category" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="network" {{ old('category', $asset->category) == 'network' ? 'selected' : '' }}>Jaringan</option>
                        <option value="computer" {{ old('category', $asset->category) == 'computer' ? 'selected' : '' }}>Komputer</option>
                        <option value="office" {{ old('category', $asset->category) == 'office' ? 'selected' : '' }}>Perkantoran</option>
                        <option value="vehicle" {{ old('category', $asset->category) == 'vehicle' ? 'selected' : '' }}>Kendaraan</option>
                        <option value="tools" {{ old('category', $asset->category) == 'tools' ? 'selected' : '' }}>Alat</option>
                        <option value="other" {{ old('category', $asset->category) == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kondisi *</label>
                    <select name="condition" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="new" {{ old('condition', $asset->condition) == 'new' ? 'selected' : '' }}>Baru</option>
                        <option value="good" {{ old('condition', $asset->condition) == 'good' ? 'selected' : '' }}>Baik</option>
                        <option value="fair" {{ old('condition', $asset->condition) == 'fair' ? 'selected' : '' }}>Cukup</option>
                        <option value="poor" {{ old('condition', $asset->condition) == 'poor' ? 'selected' : '' }}>Kurang</option>
                        <option value="broken" {{ old('condition', $asset->condition) == 'broken' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status *</label>
                    <select name="status" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="available" {{ old('status', $asset->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="in_use" {{ old('status', $asset->status) == 'in_use' ? 'selected' : '' }}>Digunakan</option>
                        <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="disposed" {{ old('status', $asset->status) == 'disposed' ? 'selected' : '' }}>Dibuang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Harga Beli</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', $asset->purchase_price) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Beli</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Garansi Sampai</label>
                    <input type="date" name="warranty_until" value="{{ old('warranty_until', $asset->warranty_until?->format('Y-m-d')) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $asset->location) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Assigned To</label>
                    <input type="text" name="assigned_to" value="{{ old('assigned_to', $asset->assigned_to) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $asset->notes) }}</textarea>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('assets.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/25">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
