@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                Customer Reports
            </h1>
            <p class="text-gray-400 mt-1">Analisis data pelanggan</p>
        </div>
        <form action="{{ route('reports.customers') }}" method="GET" class="flex items-center gap-3">
            <input type="date" name="start_date" value="{{ $startDate }}" class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white text-sm focus:ring-2 focus:ring-blue-500">
            <span class="text-gray-500">-</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white text-sm focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-colors">Filter</button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Pelanggan</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($totalCustomers) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Aktif</p>
                    <p class="text-2xl font-bold text-emerald-400">{{ number_format($activeCustomers) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-teal-500 to-pink-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Baru (Periode)</p>
                    <p class="text-2xl font-bold text-teal-400">{{ number_format($newCustomers) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Churn (Periode)</p>
                    <p class="text-2xl font-bold text-red-400">{{ number_format($churnedCustomers) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Customer Growth -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Pertumbuhan Pelanggan (12 Bulan)</h3>
            <div class="h-64 flex items-end justify-between gap-2">
                @php
                    $maxGrowth = $customerGrowth->max('total') ?: 1;
                @endphp
                @foreach($customerGrowth as $data)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-gradient-to-t from-blue-500 to-indigo-400 rounded-t-lg transition-all duration-300 hover:from-blue-400 hover:to-indigo-300" style="height: {{ ($data->total / $maxGrowth) * 100 }}%"></div>
                    <span class="text-xs text-gray-400 mt-2">{{ $data->month }}/{{ substr($data->year, 2) }}</span>
                </div>
                @endforeach
                @if($customerGrowth->isEmpty())
                <div class="w-full text-center text-gray-500">Belum ada data</div>
                @endif
            </div>
        </div>

        <!-- Customers by Package -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Distribusi per Paket</h3>
            <div class="space-y-4">
                @forelse($customersByPackage as $pkg)
                <div class="flex items-center justify-between p-4 bg-gray-700/30 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ substr($pkg->package->name ?? 'P', 0, 2) }}</span>
                        </div>
                        <span class="text-white font-medium">{{ $pkg->package->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-blue-400">{{ $pkg->total }}</p>
                        <p class="text-sm text-gray-400">pelanggan</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">Belum ada data distribusi</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white">Top 10 Pelanggan (Revenue)</h3>
        </div>
        <table class="w-full">
            <thead class="bg-gray-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Pelanggan</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Total Bayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50">
                @forelse($topCustomers as $index => $customer)
                <tr class="hover:bg-gray-700/30">
                    <td class="px-6 py-4 text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ strtoupper(substr($customer->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="text-white font-medium">{{ $customer->name }}</p>
                                <p class="text-sm text-gray-400">{{ $customer->customer_id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right text-emerald-400 font-bold">Rp {{ number_format($customer->total_paid ?? 0, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
