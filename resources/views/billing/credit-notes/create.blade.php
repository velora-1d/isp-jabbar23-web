@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('billing.credit-notes') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-rose-400 to-red-400 bg-clip-text text-transparent">
                Buat Credit Note
            </h1>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('billing.credit-notes.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Pelanggan *</label>
                    <select name="customer_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->customer_id }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Jumlah (Rp) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" required min="1" step="1000"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="Masukkan jumlah kredit">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Alasan *</label>
                    <select name="reason" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        <option value="">Pilih Alasan</option>
                        <option value="overpayment" {{ old('reason') == 'overpayment' ? 'selected' : '' }}>Kelebihan Bayar</option>
                        <option value="refund" {{ old('reason') == 'refund' ? 'selected' : '' }}>Refund</option>
                        <option value="discount" {{ old('reason') == 'discount' ? 'selected' : '' }}>Diskon</option>
                        <option value="adjustment" {{ old('reason') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                        <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-700/50">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white font-semibold rounded-xl transition-all duration-200">
                    Buat Credit Note
                </button>
                <a href="{{ route('billing.credit-notes') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
