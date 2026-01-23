@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('billing.recurring') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">
                Detail Langganan
            </h1>
            <p class="text-gray-400 mt-1">{{ $customer->name }} - {{ $customer->customer_id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ $customer->name }}</h3>
                    <p class="text-gray-400">{{ $customer->phone }}</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-700/50">
                        <span class="text-gray-400">Paket</span>
                        <span class="text-white font-medium">{{ $customer->package?->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700/50">
                        <span class="text-gray-400">Harga</span>
                        <span class="text-emerald-400 font-medium">Rp {{ number_format($customer->package?->price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700/50">
                        <span class="text-gray-400">Status</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-emerald-500/20 text-emerald-400">{{ $customer->status_label }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-400">Skor Pembayaran</span>
                        <span class="font-medium text-{{ $customer->payment_label_color }}-400">{{ $customer->payment_label }}</span>
                    </div>
                </div>
            </div>

            <!-- Billing Date Card -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Tanggal Billing</h3>
                <form action="{{ route('billing.recurring.update-billing-date', $customer) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm text-gray-400 mb-2">Tanggal Jatuh Tempo Setiap Bulan</label>
                        <input type="date" name="billing_date" value="{{ $customer->billing_date?->format('Y-m-d') }}" 
                            class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-500 hover:to-teal-500 text-white font-semibold rounded-xl transition-all duration-200">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Invoice History -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white">Riwayat Invoice (12 Bulan Terakhir)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">No. Invoice</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @forelse($customer->invoices as $invoice)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-cyan-400 hover:text-cyan-300 font-medium">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-300">
                                    {{ $invoice->period_start->format('M Y') }}
                                </td>
                                <td class="px-6 py-4 text-white font-medium">
                                    {{ $invoice->formatted_amount }}
                                </td>
                                <td class="px-6 py-4 text-gray-300">
                                    {{ $invoice->due_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($invoice->status === 'paid')
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-emerald-500/20 text-emerald-400">Lunas</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-500/20 text-red-400">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($invoice->status === 'paid')
                                        <span class="text-{{ $invoice->is_on_time ? 'emerald' : 'amber' }}-400">
                                            {{ $invoice->payment_status_label }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    Belum ada invoice
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
