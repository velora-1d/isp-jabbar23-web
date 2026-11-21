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
                {{ $proforma->proforma_number }}
            </h1>
            <p class="text-gray-400 mt-1">Detail Proforma Invoice</p>
        </div>
        @if($proforma->status === 'pending')
        <div class="flex gap-3">
            <form action="{{ route('billing.proforma.convert', $proforma) }}" method="POST" onsubmit="return confirm('Yakin ingin konversi ke Invoice?')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-semibold rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Konversi ke Invoice
                </button>
            </form>
            <form action="{{ route('billing.proforma.cancel', $proforma) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan proforma ini?')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600/20 hover:bg-red-600/40 text-red-400 font-semibold rounded-xl transition-all duration-200 border border-red-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batalkan
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Proforma Details -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-700/50">
                    <div>
                        <p class="text-sm text-gray-400">Nomor Proforma</p>
                        <p class="text-xl font-bold text-white">{{ $proforma->proforma_number }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-medium bg-{{ $proforma->status_color }}-500/20 text-{{ $proforma->status_color }}-400">
                        {{ $proforma->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-400">Tanggal Dibuat</p>
                        <p class="text-white font-medium">{{ $proforma->issue_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Berlaku Sampai</p>
                        <p class="text-white font-medium">{{ $proforma->valid_until->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-fuchsia-500/10 to-pink-500/10 rounded-xl p-6 border border-fuchsia-500/20">
                    <p class="text-sm text-gray-400 mb-2">Total Jumlah</p>
                    <p class="text-4xl font-bold text-white">{{ $proforma->formatted_amount }}</p>
                </div>

                @if($proforma->notes)
                <div class="mt-6 pt-6 border-t border-gray-700/50">
                    <p class="text-sm text-gray-400 mb-2">Catatan</p>
                    <p class="text-white">{{ $proforma->notes }}</p>
                </div>
                @endif

                @if($proforma->status === 'converted' && $proforma->convertedInvoice)
                <div class="mt-6 pt-6 border-t border-gray-700/50">
                    <p class="text-sm text-gray-400 mb-2">Dikonversi ke Invoice</p>
                    <a href="{{ route('invoices.show', $proforma->convertedInvoice) }}" class="inline-flex items-center gap-2 text-emerald-400 hover:text-emerald-300 font-medium">
                        {{ $proforma->convertedInvoice->invoice_number }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Info -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Informasi Pelanggan</h3>
                <div class="text-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-fuchsia-500 to-pink-600 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($proforma->customer->name ?? '-', 0, 1)) }}
                    </div>
                    <h4 class="text-lg font-bold text-white">{{ $proforma->customer->name ?? '-' }}</h4>
                    <p class="text-gray-400">{{ $proforma->customer->phone ?? '-' }}</p>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-700/50">
                        <span class="text-gray-400">ID Pelanggan</span>
                        <span class="text-white font-medium">{{ $proforma->customer->customer_id ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-700/50">
                        <span class="text-gray-400">Paket</span>
                        <span class="text-white font-medium">{{ $proforma->customer->package->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-400">Email</span>
                        <span class="text-white font-medium">{{ $proforma->customer->email ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
