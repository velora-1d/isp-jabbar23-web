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
                {{ $creditNote->credit_number }}
            </h1>
            <p class="text-gray-400 mt-1">Detail Credit Note</p>
        </div>
        @if($creditNote->status === 'pending')
        <form action="{{ route('billing.credit-notes.cancel', $creditNote) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan credit note ini?')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600/20 hover:bg-red-600/40 text-red-400 font-semibold rounded-xl transition-all duration-200 border border-red-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batalkan
            </button>
        </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Credit Note Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-700/50">
                    <div>
                        <p class="text-sm text-gray-400">Nomor Credit Note</p>
                        <p class="text-xl font-bold text-white">{{ $creditNote->credit_number }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-medium bg-{{ $creditNote->status_color }}-500/20 text-{{ $creditNote->status_color }}-400">
                        {{ $creditNote->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-400">Tanggal Dibuat</p>
                        <p class="text-white font-medium">{{ $creditNote->issue_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Alasan</p>
                        <p class="text-white font-medium">{{ $creditNote->reason_label }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-rose-500/10 to-red-500/10 rounded-xl p-6 border border-rose-500/20">
                    <p class="text-sm text-gray-400 mb-2">Jumlah Kredit</p>
                    <p class="text-4xl font-bold text-white">{{ $creditNote->formatted_amount }}</p>
                </div>

                @if($creditNote->notes)
                <div class="mt-6 pt-6 border-t border-gray-700/50">
                    <p class="text-sm text-gray-400 mb-2">Catatan</p>
                    <p class="text-white">{{ $creditNote->notes }}</p>
                </div>
                @endif

                @if($creditNote->status === 'applied' && $creditNote->appliedInvoice)
                <div class="mt-6 pt-6 border-t border-gray-700/50">
                    <p class="text-sm text-gray-400 mb-2">Diterapkan ke Invoice</p>
                    <a href="{{ route('invoices.show', $creditNote->appliedInvoice) }}" class="inline-flex items-center gap-2 text-emerald-400 hover:text-emerald-300 font-medium">
                        {{ $creditNote->appliedInvoice->invoice_number }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
                @endif
            </div>

            <!-- Apply to Invoice -->
            @if($creditNote->status === 'pending' && $unpaidInvoices->count() > 0)
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Terapkan ke Invoice</h3>
                <form action="{{ route('billing.credit-notes.apply', $creditNote) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm text-gray-400 mb-2">Pilih Invoice</label>
                        <select name="invoice_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            <option value="">Pilih Invoice</option>
                            @foreach($unpaidInvoices as $invoice)
                                <option value="{{ $invoice->id }}">
                                    {{ $invoice->invoice_number }} - {{ $invoice->formatted_amount }} ({{ $invoice->due_date->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-semibold rounded-xl transition-all duration-200">
                        Terapkan Credit
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Customer Info -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Informasi Pelanggan</h3>
                <div class="text-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($creditNote->customer->name ?? '-', 0, 1)) }}
                    </div>
                    <h4 class="text-lg font-bold text-white">{{ $creditNote->customer->name ?? '-' }}</h4>
                    <p class="text-gray-400">{{ $creditNote->customer->phone ?? '-' }}</p>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-700/50">
                        <span class="text-gray-400">ID Pelanggan</span>
                        <span class="text-white font-medium">{{ $creditNote->customer->customer_id ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-400">Email</span>
                        <span class="text-white font-medium">{{ $creditNote->customer->email ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
