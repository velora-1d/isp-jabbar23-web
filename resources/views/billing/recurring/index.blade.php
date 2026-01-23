@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">
                Recurring Billing
            </h1>
            <p class="text-gray-400 mt-1">Kelola langganan dan siklus billing pelanggan aktif</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- MRR -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Monthly Recurring Revenue</p>
                    <p class="text-2xl font-bold text-white">Rp {{ number_format($stats['mrr'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Langganan Aktif</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total_active'] }}</p>
                </div>
            </div>
        </div>

        <!-- Due This Week -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Jatuh Tempo Minggu Ini</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['due_this_week'] }}</p>
                </div>
            </div>
        </div>

        <!-- Unpaid This Month -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Belum Bayar Bulan Ini</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['unpaid_this_month'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal Billing</label>
                <select name="billing_day" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    <option value="">Semua Tanggal</option>
                    @for($i = 1; $i <= 28; $i++)
                        <option value="{{ $i }}" {{ request('billing_day') == $i ? 'selected' : '' }}>Tanggal {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-400 mb-2">Paket</label>
                <select name="package_id" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    <option value="">Semua Paket</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-500 hover:to-teal-500 text-white font-semibold rounded-xl transition-all duration-200">
                Filter
            </button>
            <a href="{{ route('billing.recurring') }}" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200">
                Reset
            </a>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Paket</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tgl Billing</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Invoice Terakhir</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-white">{{ $customer->name }}</p>
                                    <p class="text-sm text-gray-400">{{ $customer->customer_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-500/20 text-blue-400">
                                {{ $customer->package?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-white font-medium">
                            Rp {{ number_format($customer->package?->price ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            @if($customer->billing_date)
                                Tanggal {{ $customer->billing_date->day }}
                            @else
                                <span class="text-gray-500">Belum diatur</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php $lastInvoice = $customer->invoices->first(); @endphp
                            @if($lastInvoice)
                                <div>
                                    <p class="text-white">{{ $lastInvoice->invoice_number }}</p>
                                    <p class="text-sm text-gray-400">{{ $lastInvoice->period_start->format('M Y') }}</p>
                                </div>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($lastInvoice)
                                @if($lastInvoice->status === 'paid')
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-emerald-500/20 text-emerald-400">Lunas</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-500/20 text-red-400">Belum Bayar</span>
                                @endif
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('billing.recurring.show', $customer) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-600/20 hover:bg-cyan-600/40 text-cyan-400 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Belum ada pelanggan aktif</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-700/50">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
