@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-pink-400 to-rose-400 bg-clip-text text-transparent">Laporan Keuangan</h1>
                <p class="text-gray-400 mt-1">Rekapitulasi pemasukan dan tagihan bulanan.</p>
            </div>
            <div class="flex gap-3">
                 <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all border border-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print / PDF
                </button>
                <button class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-emerald-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </button>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Card 1: Revenue -->
             <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total Pemasukan</p>
                            <p class="text-2xl font-bold text-emerald-400 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $countPaid }} Transaksi Lunas</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Card 2: Unpaid -->
             <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total Tunggakan</p>
                            <p class="text-2xl font-bold text-red-400 mt-1">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $countUnpaid }} Belum Bayar</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 shadow-lg shadow-red-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Card 3: Est. Omset -->
             <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Estimasi Omset</p>
                            <p class="text-2xl font-bold text-blue-400 mt-1">Rp {{ number_format($totalRevenue + $totalUnpaid, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-2">Potensi Total</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Card 4: Total Invoices -->
             <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-gray-600 to-gray-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total Invoice</p>
                            <p class="text-2xl font-bold text-white mt-1">{{ $countTotal }}</p>
                            <p class="text-xs text-gray-500 mt-2">Diterbitkan</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-gray-700 to-gray-600 shadow-lg shadow-gray-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                </div>
             </div>
        </div>

        <!-- Filter Bar -->
        <div class="mb-6 bg-gray-800 rounded-xl border border-gray-700/50 p-4">
             <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                 <div class="w-full md:w-48">
                    <label class="text-xs font-medium text-gray-400 mb-1 block">Bulan</label>
                    <select name="month" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month', date('n')) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-32">
                    <label class="text-xs font-medium text-gray-400 mb-1 block">Tahun</label>
                    <select name="year" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                 <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">
                    Tampilkan Laporan
                </button>
             </form>
        </div>

        <!-- Table -->
        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                 <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4">No. Invoice</th>
                            <th class="px-6 py-4">Tgl Tagihan</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-700/30">
                            <td class="px-6 py-4 font-mono text-white">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4">{{ $invoice->due_date->format('d/m/Y') }}</td>
                             <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $invoice->customer->name }}</div>
                                <div class="text-xs text-gray-500">{{ $invoice->customer->package->name ?? '-' }}</div>
                            </td>
                             <td class="px-6 py-4 text-right font-medium text-white">{{ $invoice->formatted_amount }}</td>
                             <td class="px-6 py-4 text-center">
                                 @if($invoice->status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">LUNAS</span>
                                @else
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">BELUM BAYAR</span>
                                @endif
                             </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Tidak ada data invoice untuk periode ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                 </table>
            </div>
        </div>

    </div>
</div>
@endsection
