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
            
            <!-- Admin Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Revenue This Month -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Revenue Bulan Ini</p>
                                <p class="text-2xl font-bold text-emerald-400 mt-1">Rp {{ number_format($revenueThisMonth ?? 0, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-2">Total: Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 shadow-lg shadow-emerald-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Total Pelanggan</p>
                                <p class="text-2xl font-bold text-white mt-1">{{ $totalCustomers ?? 0 }}</p>
                                <p class="text-xs text-emerald-400 mt-2">+{{ $newCustomersThisMonth ?? 0 }} bulan ini</p>
                            </div>
                            <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/30">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Open Tickets -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Tiket Open</p>
                                <p class="text-2xl font-bold text-amber-400 mt-1">{{ $openTickets ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $inProgressTickets ?? 0 }} in progress</p>
                            </div>
                            <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 shadow-lg shadow-amber-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Work Orders -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Work Order Pending</p>
                                <p class="text-2xl font-bold text-purple-400 mt-1">{{ $pendingWorkOrders ?? 0 }}</p>
                                <p class="text-xs text-emerald-400 mt-2">{{ $completedWorkOrders ?? 0 }} selesai bulan ini</p>
                            </div>
                            <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 shadow-lg shadow-purple-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice & Payment Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Unpaid Invoices Alert -->
                <div class="rounded-2xl bg-gradient-to-br from-red-900/30 to-orange-900/30 border border-red-500/30 p-6">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
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
                    <a href="{{ route('invoices.index') }}" class="mt-4 inline-flex items-center text-sm text-red-400 hover:text-red-300">
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

            {{-- ============================================== --}}
            {{-- SALES DASHBOARD --}}
            {{-- ============================================== --}}
            @elseif($userRole === 'sales')
            
            <!-- Sales Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Pelanggan Hari Ini</p>
                        <p class="text-3xl font-bold text-cyan-400 mt-1">{{ $newCustomersToday ?? 0 }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Pelanggan Minggu Ini</p>
                        <p class="text-3xl font-bold text-blue-400 mt-1">{{ $newCustomersThisWeek ?? 0 }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Pelanggan Bulan Ini</p>
                        <p class="text-3xl font-bold text-indigo-400 mt-1">{{ $newCustomersThisMonth ?? 0 }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
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
                    <div class="text-center p-4 rounded-xl bg-purple-500/10 border border-purple-500/30">
                        <p class="text-2xl font-bold text-purple-400">{{ $leadStats['approved'] ?? 0 }}</p>
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
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs bg-blue-500/20 text-blue-400">{{ $customer->status }}</span></td>
                                <td class="px-6 py-4 text-gray-400">{{ $customer->created_at->diffForHumans() }}</td>
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
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Revenue Hari Ini</p>
                        <p class="text-2xl font-bold text-emerald-400 mt-1">Rp {{ number_format($revenueToday ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Revenue Bulan Ini</p>
                        <p class="text-2xl font-bold text-blue-400 mt-1">Rp {{ number_format($revenueThisMonth ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Total Belum Dibayar</p>
                        <p class="text-2xl font-bold text-red-400 mt-1">Rp {{ number_format($totalUnpaid ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
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
                                <span class="text-emerald-400 font-bold">Rp {{ number_format($method->total, 0, ',', '.') }}</span>
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
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Total Items</p>
                        <p class="text-3xl font-bold text-amber-400 mt-1">{{ $totalItems ?? 0 }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Low Stock Alert</p>
                        <p class="text-3xl font-bold text-red-400 mt-1">{{ $lowStockItems ?? 0 }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">PO Pending</p>
                        <p class="text-3xl font-bold text-blue-400 mt-1">{{ $pendingPO ?? 0 }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Total Assets</p>
                        <p class="text-3xl font-bold text-purple-400 mt-1">{{ $totalAssets ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-2xl bg-gradient-to-br from-amber-900/20 to-orange-900/20 border border-amber-500/30 p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-amber-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                <h3 class="text-xl font-bold text-white mb-2">Modul Inventory</h3>
                <p class="text-gray-400">Fitur inventory management akan segera tersedia.</p>
            </div>

            {{-- ============================================== --}}
            {{-- HRD DASHBOARD --}}
            {{-- ============================================== --}}
            @elseif($userRole === 'hrd')
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Total Karyawan</p>
                        <p class="text-3xl font-bold text-blue-400 mt-1">{{ $totalEmployees ?? 0 }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Karyawan Aktif</p>
                        <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $activeEmployees ?? 0 }}</p>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <p class="text-sm font-medium text-gray-400">Total Roles</p>
                        <p class="text-3xl font-bold text-purple-400 mt-1">{{ isset($roleBreakdown) ? count($roleBreakdown) : 0 }}</p>
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
                <svg class="w-16 h-16 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="text-xl font-bold text-white mb-2">Selamat Datang!</h3>
                <p class="text-gray-400">Dashboard khusus untuk role Anda akan segera tersedia.</p>
            </div>
            
            @endif
        </div>
    </div>
</x-app-layout>
