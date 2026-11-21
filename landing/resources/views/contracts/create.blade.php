@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Buat Kontrak Baru</h1>
            <p class="text-gray-400 mt-1">Daftarkan kontrak baru untuk pelanggan</p>
        </div>
        <a href="{{ route('contracts.index') }}" class="text-gray-400 hover:text-white transition">
            &larr; Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
        <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pelanggan *</label>
                    <select name="customer_id" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->customer_number }})</option>
                        @endforeach
                    </select>
                    @error('customer_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Contract Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">No. Kontrak *</label>
                    <input type="text" name="contract_number" value="{{ old('contract_number', 'CTR-' . strtoupper(uniqid())) }}" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500">
                    @error('contract_number') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Dates -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika kontrak tidak terbatas</p>
                </div>
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    </select>
                </div>
            </div>

            <!-- Terms -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Syarat & Ketentuan / Detail Kontrak</label>
                <textarea name="terms" rows="4" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500" placeholder="Syarat khusus, detail layanan, dll...">{{ old('terms') }}</textarea>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Upload Scan Kontrak (PDF/Image)</label>
                <input type="file" name="scanned_copy" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition">
                <p class="text-xs text-gray-500 mt-1">Max: 5MB (PDF, JPG, PNG)</p>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('contracts.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Batal</a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-600 transition shadow-lg shadow-blue-500/25">Simpan Kontrak</button>
            </div>
        </form>
    </div>
</div>
@endsection
