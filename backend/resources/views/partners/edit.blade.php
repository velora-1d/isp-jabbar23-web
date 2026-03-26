@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Partner: {{ $partner->name }}</h1>
            <p class="text-gray-400 mt-1">Perbarui data reseller atau partner kerjasama</p>
        </div>
        <a href="{{ route('partners.show', $partner) }}" class="text-gray-400 hover:text-white transition">
            &larr; Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
        <form action="{{ route('partners.update', $partner) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Partner *</label>
                    <input type="text" name="name" value="{{ old('name', $partner->name) }}" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500">
                    @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kode Partner *</label>
                    <input type="text" name="code" value="{{ old('code', $partner->code) }}" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500">
                    @error('code') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $partner->email) }}" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $partner->phone) }}" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Commission Rate -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tingkat Komisi (%)</label>
                    <input type="number" step="0.01" name="commission_rate" value="{{ old('commission_rate', $partner->commission_rate) }}" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500">
                        <option value="active" {{ (old('status', $partner->status) == 'active') ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ (old('status', $partner->status) == 'inactive') ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                <textarea name="address" rows="2" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500">{{ old('address', $partner->address) }}</textarea>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan Internal</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500">{{ old('notes', $partner->notes) }}</textarea>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('partners.show', $partner) }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Batal</a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-600 transition shadow-lg shadow-emerald-500/25">Update Partner</button>
            </div>
        </form>
    </div>
</div>
@endsection
