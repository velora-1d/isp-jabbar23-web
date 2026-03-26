@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Laba Rugi & PPN</h1>
                <p class="text-gray-400 mt-1">Laporan kesehatan keuangan ISP (Pendapatan vs Pengeluaran)</p>
            </div>
            <form action="" method="GET" class="flex gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition text-sm">Filter</button>
            </form>
        </div>

        <!-- Profit & Loss Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stat-card title="Total Pendapatan" 
                value="Rp {{ number_format($totalIncome, 0, ',', '.') }}"
                subtitle="Bruto (Termasuk PPN)"
                valueClass="text-emerald-400" colorFrom="emerald-500" colorTo="teal-500">
                <x-slot:icon>
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:icon>
            </x-stat-card>

            <x-stat-card title="Estimasi PPN (11%)" 
                value="Rp {{ number_format($totalTax, 0, ',', '.') }}"
                subtitle="Pajak Terkumpul"
                valueClass="text-amber-400" colorFrom="amber-500" colorTo="orange-500">
                <x-slot:icon>
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </x-slot:icon>
            </x-stat-card>

            <x-stat-card title="Total Pengeluaran" 
                value="Rp {{ number_format($totalExpenses, 0, ',', '.') }}"
                subtitle="Biaya Operasional"
                valueClass="text-rose-400" colorFrom="rose-500" colorTo="pink-500">
                <x-slot:icon>
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:icon>
            </x-stat-card>

            <x-stat-card title="Laba Bersih" 
                value="Rp {{ number_format($netProfit, 0, ',', '.') }}"
                subtitle="Pendapatan - Pengeluaran"
                valueClass="{{ $netProfit >= 0 ? 'text-indigo-400' : 'text-red-500' }}" 
                colorFrom="indigo-500" colorTo="blue-500">
                <x-slot:icon>
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </x-slot:icon>
            </x-stat-card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Expense Breakdown -->
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-6">Rincian Pengeluaran berdasarkan Kategori</h3>
                <div class="space-y-4">
                    @foreach ($expensesByCategory as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                <span class="text-gray-300">{{ $categories[$item->category] ?? $item->category }}</span>
                            </div>
                            <span class="text-white font-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-1.5">
                            @php
                                $percent = $totalExpenses > 0 ? ($item->total / $totalExpenses) * 100 : 0;
                            @endphp
                            <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    @endforeach
                    @if($expensesByCategory->isEmpty())
                        <p class="text-gray-500 text-center py-8">Belum ada pengeluaran di periode ini.</p>
                    @endif
                </div>
            </div>

            <!-- Helpful Information -->
            <div class="bg-gradient-to-br from-indigo-900/20 to-purple-900/20 border border-indigo-500/20 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Informasi Laba Rugi</h3>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li class="flex gap-2">
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><strong>Total Pendapatan</strong> dihitung dari semua pembayaran (Payment) yang berstatus <em>confirmed</em> dalam periode tersebut.</span>
                    </li>
                    <li class="flex gap-2">
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><strong>Estimasi PPN</strong> adalah 11% dari nilai subtotal tagihan yang sudah dibayar.</span>
                    </li>
                    <li class="flex gap-2">
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><strong>Laba Bersih</strong> adalah selisih antara Pendapatan Bruto dengan Total Biaya Operasional.</span>
                    </li>
                </ul>
                <div class="mt-6 p-4 bg-gray-900/50 rounded-xl border border-gray-700">
                    <p class="text-xs text-gray-500 uppercase font-bold mb-2">Saran Keuangan:</p>
                    @if($netProfit > 0)
                        <p class="text-emerald-400">ISP Berjalan Positif! Anda memiliki surplus Rp {{ number_format($netProfit, 0, ',', '.') }} untuk pengembangan infrastruktur.</p>
                    @else
                        <p class="text-rose-400">Perhatian: Pengeluaran melebihi pendapatan di periode ini. Harap tinjau kembali rincian biaya.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
