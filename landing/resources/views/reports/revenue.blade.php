@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">
                Revenue Reports
            </h1>
            <p class="text-gray-400 mt-1">Analisis pendapatan dan pembayaran</p>
        </div>
        <form action="{{ route('reports.revenue') }}" method="GET" class="flex items-center gap-3">
            <input type="date" name="start_date" value="{{ $startDate }}" class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white text-sm focus:ring-2 focus:ring-emerald-500">
            <span class="text-gray-500">-</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white text-sm focus:ring-2 focus:ring-emerald-500">
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-colors">Filter</button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM12 6v2m0 8v2m-6-6h2m8 0h2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Pending Invoice</p>
                    <p class="text-2xl font-bold text-amber-400">Rp {{ number_format($pendingInvoices, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Invoice Lunas</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $paidCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Invoice Belum Bayar</p>
                    <p class="text-2xl font-bold text-red-400">{{ $unpaidCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Trend Pendapatan (12 Bulan)</h3>
            <div class="h-64 flex items-end justify-between gap-2">
                @php
                    $maxRevenue = $revenueByMonth->max('total') ?: 1;
                @endphp
                @foreach($revenueByMonth as $data)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-gradient-to-t from-emerald-500 to-teal-400 rounded-t-lg transition-all duration-300 hover:from-emerald-400 hover:to-teal-300" style="height: {{ ($data->total / $maxRevenue) * 100 }}%"></div>
                    <span class="text-xs text-gray-400 mt-2">{{ $data->month }}/{{ substr($data->year, 2) }}</span>
                </div>
                @endforeach
                @if($revenueByMonth->isEmpty())
                <div class="w-full text-center text-gray-500">Belum ada data</div>
                @endif
            </div>
        </div>

        <!-- Revenue by Method -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Pendapatan per Metode</h3>
            <div class="space-y-4">
                @forelse($revenueByMethod as $method)
                @php
                    $colors = [
                        'cash' => 'emerald',
                        'transfer' => 'blue',
                        'qris' => 'teal',
                        'va' => 'indigo',
                        'default' => 'gray'
                    ];
                    $color = $colors[$method->payment_method] ?? $colors['default'];
                @endphp
                <div class="flex items-center justify-between p-4 bg-gray-700/30 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-{{ $color }}-500"></div>
                        <span class="text-white font-medium capitalize">{{ $method->payment_method ?? 'Lainnya' }}</span>
                    </div>
                    <div class="text-right">
                        <p class="text-white font-bold">Rp {{ number_format($method->total, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-400">{{ $method->count }} transaksi</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">Belum ada data pembayaran</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
