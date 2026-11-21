@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Catat Pembayaran Baru</h1>
            <p class="text-gray-400 mt-1">Input manual pembayaran dari customer</p>
        </div>
        <a href="{{ route('payments.index') }}" class="text-gray-400 hover:text-white transition">
            &larr; Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden">
        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Invoice Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Invoice / Tagihan</label>
                <select name="invoice_id" id="invoice_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required onchange="updateAmount(this)">
                    <option value="">-- Pilih Invoice yang Belum Lunas --</option>
                    @foreach($invoices as $invoice)
                        <option value="{{ $invoice->id }}" data-amount="{{ $invoice->amount }}" data-customer="{{ $invoice->customer->name }}">
                            {{ $invoice->invoice_number }} - {{ $invoice->customer->name }} (Rp {{ number_format($invoice->amount) }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500" id="customer_info"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                        @foreach(\App\Models\Payment::PAYMENT_METHODS as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Paid At -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Bayar</label>
                    <input type="datetime-local" name="paid_at" value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nominal Bayar (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" class="w-full bg-gray-900 border border-gray-700 rounded-lg pl-12 pr-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                    </div>
                </div>

                <!-- Reference Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">No. Referensi / No. Transaksi</label>
                    <input type="text" name="reference_number" placeholder="Contoh: TRX-12345678" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan (Optional)</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Catatan tambahan..."></textarea>
            </div>

            <!-- Proof File -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Bukti Transfer (Gambar/PDF)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-lg hover:border-blue-500 transition cursor-pointer" onclick="document.getElementById('proof_file').click()">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-400">
                            <span class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-400 hover:text-blue-300">
                                Upload file
                            </span>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                    </div>
                </div>
                <input id="proof_file" name="proof_file" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf" onchange="previewFile(this)">
                <p id="file-name" class="mt-2 text-sm text-emerald-400 hidden"></p>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-gray-700/50 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/20">
                    Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateAmount(select) {
        const option = select.options[select.selectedIndex];
        const amount = option.getAttribute('data-amount');
        if (amount) {
            document.getElementById('amount').value = amount;
        }
        
        const customer = option.getAttribute('data-customer');
        const info = document.getElementById('customer_info');
        if (customer) {
            info.textContent = 'Customer: ' + customer;
        } else {
            info.textContent = '';
        }
    }

    function previewFile(input) {
        const fileName = input.files[0]?.name;
        const display = document.getElementById('file-name');
        if (fileName) {
            display.textContent = 'File terpilih: ' + fileName;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }
</script>
@endsection
