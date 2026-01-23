@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('billing.proforma') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-fuchsia-400 to-pink-400 bg-clip-text text-transparent">
                Buat Proforma Invoice
            </h1>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('billing.proforma.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Pelanggan *</label>
                    <select name="customer_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent">
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-price="{{ $customer->package?->price ?? 0 }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->package?->name ?? 'No Package' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Jumlah (Rp) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" required min="0" step="1000"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent"
                        placeholder="Masukkan jumlah">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Berlaku (Hari) *</label>
                    <input type="number" name="valid_days" value="{{ old('valid_days', 14) }}" required min="1" max="90"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent"
                        placeholder="14">
                    <p class="mt-1 text-sm text-gray-500">Proforma akan kadaluarsa setelah jumlah hari ini</p>
                    @error('valid_days')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent"
                        placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-700/50">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-fuchsia-600 to-pink-600 hover:from-fuchsia-500 hover:to-pink-500 text-white font-semibold rounded-xl transition-all duration-200">
                    Buat Proforma
                </button>
                <a href="{{ route('billing.proforma') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelector('select[name="customer_id"]').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const price = option.dataset.price || 0;
    document.querySelector('input[name="amount"]').value = price;
});
</script>
@endpush
@endsection
