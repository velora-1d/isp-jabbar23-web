@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Buat Tiket Baru</h1>
                    <p class="text-gray-400 text-sm mt-1">Catat laporan atau keluhan pelanggan.</p>
                </div>
                <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-white transition-colors">
                    Kembali
                </a>
            </div>

            <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700/50 overflow-hidden p-6 md:p-8">
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Customer Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pelanggan</label>
                            <select name="customer_id" class="w-full bg-gray-900 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 p-3" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->customer_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Judul Laporan</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="w-full bg-gray-900 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Contoh: Internet Mati Total, Lambat, dll" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Priority -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Prioritas</label>
                                <select name="priority" class="w-full bg-gray-900 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 p-3">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low (Rendah)</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium (Sedang)</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High (Tinggi)</option>
                                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical (Darurat)</option>
                                </select>
                            </div>

                            <!-- Assign Technician -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Assign Teknisi (Opsional)</label>
                                <select name="technician_id" class="w-full bg-gray-900 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 p-3">
                                    <option value="">-- Belum Ditentukan --</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}" {{ old('technician_id') == $tech->id ? 'selected' : '' }}>
                                            {{ $tech->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Detail Keluhan</label>
                            <textarea name="description" rows="4" class="w-full bg-gray-900 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Jelaskan detail masalahnya..." required>{{ old('description') }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-gray-700 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg transition-colors">
                                Buat Tiket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
