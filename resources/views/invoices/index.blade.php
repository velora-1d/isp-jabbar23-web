@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
                    Invoices
                </h1>
                <p class="text-gray-400 mt-1">Billing & Financial Records</p>
            </div>
            <div class="flex gap-3">
                <form action="{{ route('invoices.generate') }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin generate invoice bulan ini untuk SEMUA customer aktif?');">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold rounded-xl transition-all border border-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Generate Bulk
                    </button>
                </form>
                <a href="{{ route('invoices.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-purple-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Invoice
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Invoices</p>
                        <p class="text-2xl font-bold text-white">{{ $invoices->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Unpaid Amount</p>
                        <p class="text-2xl font-bold text-red-400">Rp {{ number_format($stats['unpaid'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Paid This Month</p>
                        <p class="text-2xl font-bold text-emerald-400">Rp {{ number_format($stats['paid'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Overdue</p>
                        <p class="text-2xl font-bold text-amber-400">{{ $stats['overdue'] }} Invoices</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <form action="" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[250px]">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Invoice Number or Customer Name..."
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                    <select name="status"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-semibold rounded-xl transition-all duration-200">
                    Filter
                </button>
                <a href="{{ route('invoices.index') }}"
                    class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200">
                    Reset
                </a>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">

            <!-- Desktop Table -->
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Invoice #</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Due
                                Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">{{ $invoice->invoice_number }}</p>
                                            <p class="text-sm text-gray-400">{{ $invoice->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-white">{{ $invoice->customer->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-400">{{ $invoice->customer->cid ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-white">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                                    @if($invoice->status !== 'paid')
                                        <p class="text-sm text-red-400">Unpaid</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-300">{{ $invoice->due_date->format('d M Y') }}</p>
                                    @if($invoice->status !== 'paid' && $invoice->due_date->isPast())
                                        <span class="text-sm text-red-500 font-bold">Overdue!</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match ($invoice->status) {
                                            'paid' => 'emerald',
                                            'unpaid' => 'red',
                                            'partial' => 'amber',
                                            'overdue' => 'orange',
                                            'cancelled' => 'gray',
                                            default => 'blue'
                                        };
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-400">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-600/20 hover:bg-purple-600/40 text-purple-400 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View & Pay
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>No invoices found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-gray-700/50">
                @forelse($invoices as $invoice)
                    @php
                        $statusColor = match ($invoice->status) {
                            'paid' => 'emerald',
                            'unpaid' => 'red',
                            'partial' => 'amber',
                            'overdue' => 'orange',
                            'cancelled' => 'gray',
                            default => 'blue'
                        };
                    @endphp
                    <div class="p-4 hover:bg-gray-700/30 transition-colors">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-white">{{ $invoice->invoice_number }}</p>
                                    <p class="text-sm text-gray-400">{{ $invoice->customer->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-400">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div>
                                <p class="text-gray-500">Amount</p>
                                <p class="font-bold text-white">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Due Date</p>
                                <p class="text-gray-300">{{ $invoice->due_date->format('d M Y') }}</p>
                                @if($invoice->status !== 'paid' && $invoice->due_date->isPast())
                                    <span class="text-xs text-red-500 font-bold">Overdue!</span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-700/50">
                            <p class="text-xs text-gray-500">{{ $invoice->created_at->format('d M Y') }}</p>
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-600/20 hover:bg-purple-600/40 text-purple-400 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View & Pay
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>No invoices found.</p>
                    </div>
                @endforelse
            </div>

            @if($invoices->hasPages())
                <div class="px-6 py-4 border-t border-gray-700/50">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
