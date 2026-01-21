<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-white leading-tight">
                @if($userRole === 'super-admin' || $userRole === 'admin')
                    {{ __('Dashboard Overview') }}
                @elseif($userRole === 'sales')
                    {{ __('Sales Dashboard') }}
                @elseif($userRole === 'finance')
                    {{ __('Finance Dashboard') }}
                @elseif($userRole === 'warehouse')
                    {{ __('Inventory Dashboard') }}
                @elseif($userRole === 'hrd')
                    {{ __('HRD Dashboard') }}
                @else
                    {{ __('Dashboard') }}
                @endif
            </h2>
            <p class="text-gray-400 text-sm mt-1">Welcome back, {{ auth()->user()->name }}! Here's your overview.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">

            {{-- ============================================== --}}
            {{-- SUPER ADMIN & ADMIN DASHBOARD --}}
            {{-- ============================================== --}}
            @if($userRole === 'super-admin' || $userRole === 'admin')

                <!-- Dashboard Filter -->
                <div class="mb-6 bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 p-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3 flex-wrap">
                        <span class="text-sm text-gray-400 font-medium">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter:
                        </span>

                        <!-- Month Filter -->
                        <select name="month"
                            class="bg-gray-900/50 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all cursor-pointer hover:border-gray-500"
                            onchange="this.form.submit()">
                            <option value="">Semua Bulan</option>
                            @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] as $index => $bulan)
                                <option value="{{ $index + 1 }}" {{ request('month') == ($index + 1) ? 'selected' : '' }}>
                                    {{ $bulan }}</option>
                            @endforeach
                        </select>

                        <!-- Year Filter -->
                        <select name="year"
                            class="bg-gray-900/50 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all cursor-pointer hover:border-gray-500"
                            onchange="this.form.submit()">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}
                                </option>
                            @endfor
                        </select>

                        <!-- Reset Button -->
                        @if(request('month') || (request('year') && request('year') != date('Y')))
                            <a href="{{ route('dashboard') }}"
                                class="px-3 py-2 text-sm bg-gray-700/50 hover:bg-gray-600 text-gray-300 rounded-lg transition-colors inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Admin Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Revenue This Month -->
                    <x-stat-card title="Revenue Bulan Ini"
                        value="Rp {{ number_format($revenueThisMonth ?? 0, 0, ',', '.') }}"
                        subtitle="Total: Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}"
                        valueClass="text-emerald-400" colorFrom="emerald-500" colorTo="teal-500">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-stat-card>

                    <!-- Total Customers -->
                    <x-stat-card title="Total Pelanggan" value="{{ $totalCustomers ?? 0 }}"
                        subtitle="+{{ $newCustomersThisMonth ?? 0 }} bulan ini" subtitleClass="text-emerald-400"
                        colorFrom="blue-500" colorTo="cyan-500" href="{{ route('customers.index') }}">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-stat-card>

                    <!-- Open Tickets -->
                    <x-stat-card title="Tiket Open" value="{{ $openTickets ?? 0 }}"
                        subtitle="{{ $inProgressTickets ?? 0 }} in progress" valueClass="text-amber-400"
                        colorFrom="amber-500" colorTo="orange-500" href="{{ route('tickets.index') }}">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-stat-card>

                    <!-- Pending Work Orders -->
                    <x-stat-card title="Work Order Pending" value="{{ $pendingWorkOrders ?? 0 }}"
                        subtitle="{{ $completedWorkOrders ?? 0 }} selesai bulan ini" subtitleClass="text-emerald-400"
                        valueClass="text-teal-400" colorFrom="teal-500" colorTo="pink-500"
                        href="{{ route('work-orders.index') }}">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-stat-card>
                </div>

                <!-- Invoice & Payment Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Unpaid Invoices Alert -->
                    <div
                        class="rounded-2xl bg-gradient-to-br from-red-900/30 to-orange-900/30 border border-red-500/30 p-6">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Invoice Belum Dibayar
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 rounded-xl bg-gray-900/50">
                                <p class="text-3xl font-bold text-red-400">{{ $unpaidInvoices ?? 0 }}</p>
                                <p class="text-sm text-gray-400 mt-1">Total Unpaid</p>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-gray-900/50">
                                <p class="text-3xl font-bold text-orange-400">{{ $overdueInvoices ?? 0 }}</p>
                                <p class="text-sm text-gray-400 mt-1">Overdue</p>
                            </div>
                        </div>
                        <a href="{{ route('invoices.index') }}"
                            class="mt-4 inline-flex items-center text-sm text-red-400 hover:text-red-300">
                            Lihat semua invoice →
                        </a>
                    </div>

                    <!-- Customer Status -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700/50 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Status Pelanggan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
                                <p class="text-3xl font-bold text-emerald-400">{{ $activeCustomers ?? 0 }}</p>
                                <p class="text-sm text-gray-400 mt-1">Aktif</p>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-red-500/10 border border-red-500/30">
                                <p class="text-3xl font-bold text-red-400">{{ $suspendedCustomers ?? 0 }}</p>
                                <p class="text-sm text-gray-400 mt-1">Suspend</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Cash Payments -->
                    <x-stat-card title="Cash" value="Rp {{ number_format($paymentByCategory['cash'] ?? 0, 0, ',', '.') }}"
                        subtitle="{{ $paymentCountByCategory['cash'] ?? 0 }} transaksi" valueClass="text-emerald-400"
                        colorFrom="emerald-500" colorTo="green-500">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-stat-card>

                    <!-- Transfer Manual Payments -->
                    <x-stat-card title="Transfer Bank"
                        value="Rp {{ number_format($paymentByCategory['manual_transfer'] ?? 0, 0, ',', '.') }}"
                        subtitle="{{ $paymentCountByCategory['manual_transfer'] ?? 0 }} transaksi"
                        valueClass="text-blue-400" colorFrom="blue-500" colorTo="indigo-500">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-stat-card>

                    <!-- Payment Gateway Payments -->
                    <x-stat-card title="Gateway"
                        value="Rp {{ number_format($paymentByCategory['payment_gateway'] ?? 0, 0, ',', '.') }}"
                        subtitle="{{ $paymentCountByCategory['payment_gateway'] ?? 0 }} transaksi"
                        valueClass="text-cyan-400" colorFrom="cyan-500" colorTo="teal-500">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </x-slot:icon>
                    </x-stat-card>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Revenue Chart -->
                    <div class="rounded-2xl bg-gray-800/50 backdrop-blur-xl border border-gray-700/50 p-6">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                </path>
                            </svg>
                            Revenue 6 Bulan Terakhir
                        </h3>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Customer Growth Chart -->
                    <div class="rounded-2xl bg-gray-800/50 backdrop-blur-xl border border-gray-700/50 p-6">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Pertumbuhan Pelanggan
                        </h3>
                        <div class="h-64">
                            <canvas id="customerGrowthChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- ============================================== --}}
                {{-- SALES DASHBOARD --}}
                {{-- ============================================== --}}
            @elseif($userRole === 'sales')

                <!-- Sales Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Pelanggan Hari Ini</p>
                            <p class="text-3xl font-bold text-cyan-400 mt-1">{{ $newCustomersToday ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Pelanggan Minggu Ini</p>
                            <p class="text-3xl font-bold text-blue-400 mt-1">{{ $newCustomersThisWeek ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Pelanggan Bulan Ini</p>
                            <p class="text-3xl font-bold text-indigo-400 mt-1">{{ $newCustomersThisMonth ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Tiket Open</p>
                            <p class="text-3xl font-bold text-amber-400 mt-1">{{ $openTickets ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lead Funnel -->
                <div class="rounded-2xl bg-gray-800 border border-gray-700/50 p-6 mb-8">
                    <h3 class="text-lg font-bold text-white mb-4">Lead Conversion Funnel</h3>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="text-center p-4 rounded-xl bg-blue-500/10 border border-blue-500/30">
                            <p class="text-2xl font-bold text-blue-400">{{ $leadStats['registered'] ?? 0 }}</p>
                            <p class="text-sm text-gray-400 mt-1">Registered</p>
                        </div>
                        <div class="text-center p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/30">
                            <p class="text-2xl font-bold text-yellow-400">{{ $leadStats['survey'] ?? 0 }}</p>
                            <p class="text-sm text-gray-400 mt-1">Survey</p>
                        </div>
                        <div class="text-center p-4 rounded-xl bg-teal-500/10 border border-teal-500/30">
                            <p class="text-2xl font-bold text-teal-400">{{ $leadStats['approved'] ?? 0 }}</p>
                            <p class="text-sm text-gray-400 mt-1">Approved</p>
                        </div>
                        <div class="text-center p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
                            <p class="text-2xl font-bold text-emerald-400">{{ $leadStats['active'] ?? 0 }}</p>
                            <p class="text-sm text-gray-400 mt-1">Active</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Customers -->
                @if(isset($recentCustomers) && $recentCustomers->count() > 0)
                    <div class="rounded-2xl bg-gray-800 border border-gray-700/50 overflow-hidden">
                        <div class="p-6 border-b border-gray-700/50">
                            <h3 class="text-lg font-bold text-white">Pelanggan Baru Terbaru</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-900/50 text-gray-400 text-xs uppercase">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Nama</th>
                                        <th class="px-6 py-3 text-left">Paket</th>
                                        <th class="px-6 py-3 text-left">Status</th>
                                        <th class="px-6 py-3 text-left">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700/50">
                                    @foreach($recentCustomers as $customer)
                                        <tr class="hover:bg-gray-750">
                                            <td class="px-6 py-4 text-white font-medium">{{ $customer->name }}</td>
                                            <td class="px-6 py-4 text-gray-400">{{ $customer->package->name ?? '-' }}</td>
                                            <td class="px-6 py-4"><span
                                                    class="px-2 py-1 rounded-full text-xs bg-blue-500/20 text-blue-400">{{ $customer->status }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-400">{{ $customer->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- ============================================== --}}
                {{-- FINANCE DASHBOARD --}}
                {{-- ============================================== --}}
            @elseif($userRole === 'finance')

                <!-- Finance Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Revenue Hari Ini</p>
                            <p class="text-2xl font-bold text-emerald-400 mt-1">Rp
                                {{ number_format($revenueToday ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Revenue Bulan Ini</p>
                            <p class="text-2xl font-bold text-blue-400 mt-1">Rp
                                {{ number_format($revenueThisMonth ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Total Belum Dibayar</p>
                            <p class="text-2xl font-bold text-red-400 mt-1">Rp
                                {{ number_format($totalUnpaid ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Invoice Terlambat</p>
                            <p class="text-2xl font-bold text-orange-400 mt-1">{{ $overdueInvoices ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Invoice Overview -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="rounded-2xl bg-gray-800 border border-gray-700/50 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Status Invoice</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 rounded-xl bg-red-500/10 border border-red-500/30">
                                <p class="text-3xl font-bold text-red-400">{{ $unpaidInvoices ?? 0 }}</p>
                                <p class="text-sm text-gray-400 mt-1">Belum Bayar</p>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
                                <p class="text-3xl font-bold text-emerald-400">{{ $paidThisMonth ?? 0 }}</p>
                                <p class="text-sm text-gray-400 mt-1">Lunas Bulan Ini</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700/50 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Metode Pembayaran (Bulan Ini)</h3>
                        @if(isset($paymentMethods) && count($paymentMethods) > 0)
                            <div class="space-y-3">
                                @foreach($paymentMethods as $method)
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-900/50">
                                        <span class="text-gray-300 capitalize">{{ $method->payment_method ?? 'Other' }}</span>
                                        <div class="text-right">
                                            <span class="text-emerald-400 font-bold">Rp
                                                {{ number_format($method->total, 0, ',', '.') }}</span>
                                            <span class="text-gray-500 text-sm ml-2">({{ $method->count }}x)</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada data pembayaran bulan ini</p>
                        @endif
                    </div>
                </div>

                {{-- ============================================== --}}
                {{-- WAREHOUSE DASHBOARD --}}
                {{-- ============================================== --}}
            @elseif($userRole === 'warehouse')

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Total Items</p>
                            <p class="text-3xl font-bold text-amber-400 mt-1">{{ $totalItems ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Low Stock Alert</p>
                            <p class="text-3xl font-bold text-red-400 mt-1">{{ $lowStockItems ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">PO Pending</p>
                            <p class="text-3xl font-bold text-blue-400 mt-1">{{ $pendingPO ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-teal-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Total Assets</p>
                            <p class="text-3xl font-bold text-teal-400 mt-1">{{ $totalAssets ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl bg-gradient-to-br from-amber-900/20 to-orange-900/20 border border-amber-500/30 p-8 text-center">
                    <svg class="w-16 h-16 mx-auto text-amber-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                        </path>
                    </svg>
                    <h3 class="text-xl font-bold text-white mb-2">Modul Inventory</h3>
                    <p class="text-gray-400">Fitur inventory management akan segera tersedia.</p>
                </div>

                {{-- ============================================== --}}
                {{-- HRD DASHBOARD --}}
                {{-- ============================================== --}}
            @elseif($userRole === 'hrd')

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Total Karyawan</p>
                            <p class="text-3xl font-bold text-blue-400 mt-1">{{ $totalEmployees ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Karyawan Aktif</p>
                            <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $activeEmployees ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-teal-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                            <p class="text-sm font-medium text-gray-400">Total Roles</p>
                            <p class="text-3xl font-bold text-teal-400 mt-1">
                                {{ isset($roleBreakdown) ? count($roleBreakdown) : 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Role Breakdown -->
                @if(isset($roleBreakdown) && count($roleBreakdown) > 0)
                    <div class="rounded-2xl bg-gray-800 border border-gray-700/50 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Distribusi per Role</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($roleBreakdown as $role)
                                <div class="text-center p-4 rounded-xl bg-gray-900/50">
                                    <p class="text-2xl font-bold text-white">{{ $role->count }}</p>
                                    <p class="text-sm text-gray-400 mt-1 capitalize">{{ $role->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ============================================== --}}
                {{-- DEFAULT / FALLBACK DASHBOARD --}}
                {{-- ============================================== --}}
            @else

                <div class="rounded-2xl bg-gray-800 border border-gray-700/50 p-8 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-xl font-bold text-white mb-2">Selamat Datang!</h3>
                    <p class="text-gray-400">Dashboard khusus untuk role Anda akan segera tersedia.</p>
                </div>

            @endif
        </div>
    </div>

    @if(isset($revenueChart) || isset($customerGrowth))
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                    // Revenue Chart
                    @if(isset($revenueChart))
                        const revenueCtx = document.getElementById('revenueChart');
                        if (revenueCtx) {
                            const revenueData = @json($revenueChart);
                            new Chart(revenueCtx, {
                                type: 'bar',
                                data: {
                                    labels: revenueData.map(item => monthNames[item.month - 1] + ' ' + item.year),
                                    datasets: [{
                                        label: 'Revenue (Rp)',
                                        data: revenueData.map(item => item.total),
                                        backgroundColor: 'rgba(16, 185, 129, 0.5)',
                                        borderColor: 'rgb(16, 185, 129)',
                                        borderWidth: 2,
                                        borderRadius: 8,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            callbacks: {
                                                label: function (context) {
                                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: { color: 'rgba(255,255,255,0.1)' },
                                            ticks: {
                                                color: '#9CA3AF',
                                                callback: function (value) {
                                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                                }
                                            }
                                        },
                                        x: {
                                            grid: { display: false },
                                            ticks: { color: '#9CA3AF' }
                                        }
                                    }
                                }
                            });
                        }
                    @endif

                        // Customer Growth Chart
                        @if(isset($customerGrowth))
                            const customerCtx = document.getElementById('customerGrowthChart');
                            if (customerCtx) {
                                const customerData = @json($customerGrowth);
                                new Chart(customerCtx, {
                                    type: 'line',
                                    data: {
                                        labels: customerData.map(item => monthNames[item.month - 1] + ' ' + item.year),
                                        datasets: [{
                                            label: 'Pelanggan Baru',
                                            data: customerData.map(item => item.total),
                                            borderColor: 'rgb(59, 130, 246)',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            fill: true,
                                            tension: 0.4,
                                            pointBackgroundColor: 'rgb(59, 130, 246)',
                                            pointBorderColor: '#fff',
                                            pointBorderWidth: 2,
                                            pointRadius: 5,
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                grid: { color: 'rgba(255,255,255,0.1)' },
                                                ticks: { color: '#9CA3AF' }
                                            },
                                            x: {
                                                grid: { display: false },
                                                ticks: { color: '#9CA3AF' }
                                            }
                                        }
                                    }
                                });
                            }
                        @endif
                                    });
            </script>
        @endpush
    @endif

</x-app-layout>
