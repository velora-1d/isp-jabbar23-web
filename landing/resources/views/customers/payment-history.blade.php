@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="py-6">

        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('customers.show', $customer) }}" class="text-gray-400 hover:text-white flex items-center transition-colors mb-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Profil Customer
            </a>
            <h1 class="text-2xl font-bold text-white">Riwayat Pembayaran</h1>
            <p class="text-gray-400 text-sm mt-1">{{ $customer->name }} ({{ $customer->customer_id }})</p>
        </div>

        <!-- Payment Score Card - Dashboard Style -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php 
                $stats = $customer->payment_stats; 
                $scoreColor = $customer->payment_label_color;
                $colorGradients = [
                    'emerald' => 'from-emerald-500 to-teal-500',
                    'blue' => 'from-blue-500 to-cyan-500',
                    'amber' => 'from-amber-500 to-orange-500',
                    'red' => 'from-red-500 to-rose-500',
                    'gray' => 'from-gray-500 to-slate-500',
                ];
                $gradient = $colorGradients[$scoreColor] ?? $colorGradients['gray'];
            @endphp
            
            <!-- Score Badge -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r {{ $gradient }} rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50 flex flex-col items-center justify-center">
                    <div class="text-4xl font-bold text-{{ $scoreColor }}-400 mb-2">
                        {{ $customer->payment_score !== null ? $customer->payment_score . '%' : '-' }}
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $scoreColor }}-500/10 text-{{ $scoreColor }}-400 border border-{{ $scoreColor }}-500/20">
                        {{ $customer->payment_label }}
                    </span>
                </div>
            </div>

            <!-- Total Terbayar -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total Terbayar</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $stats['total_paid'] }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tepat Waktu -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Tepat Waktu</p>
                            <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $stats['on_time'] }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 shadow-lg shadow-emerald-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Telat Bayar -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Telat Bayar</p>
                            <p class="text-3xl font-bold text-red-400 mt-1">{{ $stats['late'] }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-500 shadow-lg shadow-red-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Year Filter -->
        <!-- Filters -->
        <form action="{{ route('customers.payment_history', $customer) }}" method="GET" class="mb-6">
            <div class="flex flex-wrap items-center gap-4 bg-gray-800 p-4 rounded-xl border border-gray-700/50">
                <div class="flex items-center gap-2">
                    <label class="text-gray-400 text-sm">Bulan:</label>
                    <select name="month" onchange="this.form.submit()" class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 w-32">
                        <option value="">Semua</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-center gap-2">
                    <label class="text-gray-400 text-sm">Tahun:</label>
                    <select name="year" onchange="this.form.submit()" class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 w-24">
                        <option value="">Semua</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-gray-400 text-sm">Status:</label>
                    <select name="status" onchange="this.form.submit()" class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 w-36">
                        <option value="all">Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                </div>
                
                @if(request()->hasAny(['month', 'year', 'status']))
                    <a href="{{ route('customers.payment_history', $customer) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-gray-700/30 hover:bg-gray-700 border border-gray-600/50 hover:border-gray-500 rounded-lg transition-all duration-200 ml-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>

        <!-- Invoice History Table -->
        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4">No. Invoice</th>
                            <th class="px-6 py-4">Periode</th>
                            <th class="px-6 py-4">Jatuh Tempo</th>
                            <th class="px-6 py-4">Tanggal Bayar</th>
                            <th class="px-6 py-4">Status Bayar</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-white">{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4">{{ $invoice->formatted_period }}</td>
                                <td class="px-6 py-4">{{ $invoice->due_date->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    {{ $invoice->payment_date ? $invoice->payment_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($invoice->status !== 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400 border border-gray-500/20">
                                            {{ $invoice->payment_status_label }}
                                        </span>
                                    @elseif($invoice->is_on_time)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            ✓ {{ $invoice->payment_status_label }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                            ⚠ {{ $invoice->payment_status_label }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-white">{{ $invoice->formatted_amount }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada riwayat pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($invoices->hasPages())
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-800">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>

    </div>
</div>


@endsection
