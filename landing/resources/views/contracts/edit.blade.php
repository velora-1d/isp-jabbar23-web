@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Kontrak: {{ $contract->contract_number }}</h1>
            <p class="text-gray-400 mt-1">Perbarui data kontrak pelanggan</p>
        </div>
        <a href="{{ route('contracts.show', $contract) }}" class="text-gray-400 hover:text-white transition">
            &larr; Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
        <form action="{{ route('contracts.update', $contract) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pelanggan *</label>
                    <select name="customer_id" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $contract->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->customer_number }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Contract Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">No. Kontrak *</label>
                    <input type="text" name="contract_number" value="{{ old('contract_number', $contract->contract_number) }}" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Dates -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        <option value="draft" {{ $contract->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ $contract->status == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="expired" {{ $contract->status == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="terminated" {{ $contract->status == 'terminated' ? 'selected' : '' }}>Diputus</option>
                    </select>
                </div>
            </div>

            <!-- Terms -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Syarat & Ketentuan / Detail Kontrak</label>
                <textarea name="terms" rows="4" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">{{ old('terms', $contract->terms) }}</textarea>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Update Scan Kontrak (Biarkan kosong jika tidak berubah)</label>
                <input type="file" name="scanned_copy" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition">
                @if($contract->scanned_copy_path)
                <p class="text-xs text-emerald-400 mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    File sudah ada.
                </p>
                @endif
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('contracts.show', $contract) }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Batal</a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-600 transition shadow-lg shadow-blue-500/25">Update Kontrak</button>
            </div>
        </form>
    </div>
</div>
@endsection
