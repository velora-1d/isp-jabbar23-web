@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 to-orange-400 bg-clip-text text-transparent">
                Commission Reports
            </h1>
            <p class="text-gray-400 mt-1">Laporan komisi sales dan partner</p>
        </div>
        <form action="{{ route('reports.commissions') }}" method="GET" class="flex items-center gap-3">
            <input type="date" name="start_date" value="{{ $startDate }}" class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white text-sm focus:ring-2 focus:ring-amber-500">
            <span class="text-gray-500">-</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white text-sm focus:ring-2 focus:ring-amber-500">
            <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-xl transition-colors">Filter</button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM12 6v2m0 8v2m-6-6h2m8 0h2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Komisi</p>
                    <p class="text-2xl font-bold text-amber-400">Rp {{ number_format($totalCommissions, 0, ',', '.') }}</p>
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
                    <p class="text-sm text-gray-400">Total Partner</p>
                    <p class="text-2xl font-bold text-emerald-400">{{ count($commissionData) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Aktif Partner</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $topPerformers->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Performers -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Top Performers (by Customers)</h3>
            <div class="space-y-3">
                @forelse($topPerformers as $index => $performer)
                <div class="flex items-center justify-between p-3 bg-gray-700/30 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $index == 0 ? 'from-yellow-500 to-amber-600' : ($index == 1 ? 'from-gray-300 to-gray-400' : ($index == 2 ? 'from-orange-600 to-amber-700' : 'from-gray-500 to-gray-600')) }} flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                        </div>
                        <span class="text-white font-medium">{{ $performer->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="text-right">
                        <p class="text-amber-400 font-bold">{{ $performer->customers_count ?? 0 }} customer</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">Belum ada data</div>
                @endforelse
            </div>
        </div>

        <!-- Commission List -->
        <div class="lg:col-span-2 bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="p-6 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white">Komisi per Partner (Periode Ini)</h3>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-gray-900/50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Partner</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Rate</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Customer Payments</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Komisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($commissionData as $data)
                        <tr class="hover:bg-gray-700/30">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($data['partner']->name ?? 'P', 0, 1)) }}</span>
                                    </div>
                                    <span class="text-white font-medium">{{ $data['partner']->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right text-gray-300">{{ $data['partner']->commission_rate ?? 0 }}%</td>
                            <td class="px-6 py-3 text-right text-gray-300">Rp {{ number_format($data['customer_payments'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right text-amber-400 font-bold">Rp {{ number_format($data['amount'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada data komisi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
