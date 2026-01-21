<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('Customer Management') }}
                </h2>
                <p class="text-gray-400 text-sm mt-1">Manage your subscribers and their subscriptions</p>
            </div>
            <a href="{{ route('customers.create') }}"
                class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/25">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Customer
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">

            <!-- Stats Row - Dashboard Style -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Total -->
                <div class="relative group">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                    </div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Total Customer</p>
                                <p class="text-3xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/30">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active -->
                <div class="relative group">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                    </div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Status Aktif</p>
                                <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $stats['active'] }}</p>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 shadow-lg shadow-emerald-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="relative group">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                    </div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Pending</p>
                                <p class="text-3xl font-bold text-amber-400 mt-1">{{ $stats['pending'] }}</p>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 shadow-lg shadow-amber-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suspended -->
                <div class="relative group">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500">
                    </div>
                    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Suspended</p>
                                <p class="text-3xl font-bold text-red-400 mt-1">{{ $stats['suspended'] }}</p>
                            </div>
                            <div
                                class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-500 shadow-lg shadow-red-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div x-data="{ open: {{ request()->hasAny(['status', 'package_id', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi']) ? 'true' : 'false' }} }"
                class="mb-6">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-4 py-3 bg-gray-800 rounded-xl border border-gray-700/50 hover:bg-gray-750 transition-colors">
                    <span class="font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filter Customers
                    </span>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="{'rotate-180': open}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" class="mt-2 p-6 bg-gray-800 rounded-2xl border border-gray-700/50 shadow-xl"
                    style="display: none;">
                    <form action="{{ route('customers.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Status -->
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-400">Status</label>
                                <select name="status"
                                    class="w-full bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">Semua Status</option>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Package -->
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-400">Paket</label>
                                <select name="package_id"
                                    class="w-full bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">Semua Paket</option>
                                    @foreach($packages as $pkg)
                                        <option value="{{ $pkg->id }}" {{ request('package_id') == $pkg->id ? 'selected' : '' }}>{{ $pkg->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Locations -->
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-400">Desa / Kelurahan</label>
                                <select name="kelurahan"
                                    class="w-full bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">Semua Desa</option>
                                    @foreach($kelurahans as $kel)
                                        <option value="{{ $kel }}" {{ request('kelurahan') == $kel ? 'selected' : '' }}>
                                            {{ $kel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-400">Kecamatan</label>
                                <select name="kecamatan"
                                    class="w-full bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">Semua Kecamatan</option>
                                    @foreach($kecamatans as $kec)
                                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                                            {{ $kec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-400">Kabupaten</label>
                                <select name="kabupaten"
                                    class="w-full bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">Semua Kabupaten</option>
                                    @foreach($kabupatens as $kab)
                                        <option value="{{ $kab }}" {{ request('kabupaten') == $kab ? 'selected' : '' }}>
                                            {{ $kab }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-400">Provinsi</label>
                                <select name="provinsi"
                                    class="w-full bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">Semua Provinsi</option>
                                    @foreach($provinsis as $prov)
                                        <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>
                                            {{ $prov }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-700/50 space-x-4">
                            <a href="{{ route('customers.index') }}"
                                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-700/30 hover:bg-gray-700 border border-gray-600/50 hover:border-gray-500 rounded-xl transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset Filter
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card with Live Search -->
            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden shadow-2xl" x-data="{
                     search: '',
                     isSearching: false,
                     debounceTimer: null,
                     customers: [],
                     filteredCustomers: [],

                     init() {
                         this.customers = [...document.querySelectorAll('tbody tr[data-customer]')];
                     },

                     filterCustomers() {
                         this.isSearching = true;
                         clearTimeout(this.debounceTimer);

                         this.debounceTimer = setTimeout(() => {
                             const searchTerm = this.search.toLowerCase().trim();

                             document.querySelectorAll('tbody tr[data-customer]').forEach(row => {
                                 const name = row.dataset.name?.toLowerCase() || '';
                                 const phone = row.dataset.phone?.toLowerCase() || '';
                                 const customerId = row.dataset.customerId?.toLowerCase() || '';
                                 const pkg = row.dataset.package?.toLowerCase() || '';

                                 const matches = searchTerm === '' ||
                                     name.includes(searchTerm) ||
                                     phone.includes(searchTerm) ||
                                     customerId.includes(searchTerm) ||
                                     pkg.includes(searchTerm);

                                 row.style.display = matches ? '' : 'none';
                             });

                             this.isSearching = false;
                         }, 300);
                     }
                 }" x-init="init()">
                <div class="p-6 border-b border-gray-700/50">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-bold text-white">All Customers</h3>
                        <div class="relative w-full sm:w-auto">
                            <input type="text" x-model="search" @input="filterCustomers()"
                                placeholder="Search customers..."
                                class="w-full sm:w-64 pl-10 pr-10 py-2 bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <svg class="w-4 h-4 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>

                            <!-- Loading indicator -->
                            <div x-show="isSearching" class="absolute right-3 top-1/2 -translate-y-1/2">
                                <x-loading-spinner size="sm" color="blue" />
                            </div>

                            <!-- Clear button -->
                            <button x-show="search.length > 0 && !isSearching" @click="search = ''; filterCustomers()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table View -->
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-900/50 text-gray-500">
                            <tr>
                                <th scope="col" class="px-6 py-4">Customer</th>
                                <th scope="col" class="px-6 py-4">ID</th>
                                <th scope="col" class="px-6 py-4">Package</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Joined</th>
                                <th scope="col" class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-gray-800/50 transition-colors"
                                    data-customer="true"
                                    data-name="{{ $customer->name }}"
                                    data-phone="{{ $customer->phone ?? '' }}"
                                    data-customer-id="{{ $customer->customer_id }}"
                                    data-package="{{ $customer->package->name ?? '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-white">{{ $customer->name }}</p>
                                                <p class="text-gray-500 text-xs">{{ $customer->phone ?? 'No phone' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-xs text-gray-500">{{ $customer->customer_id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-medium text-white">{{ $customer->package->name ?? 'N/A' }}</p>
                                            <p class="text-gray-500 text-xs">{{ $customer->package->formatted_price ?? '' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'active' => 'bg-emerald-500/20 text-emerald-400',
                                                'registered' => 'bg-blue-500/20 text-blue-400',
                                                'survey' => 'bg-sky-500/20 text-sky-400',
                                                'approved' => 'bg-cyan-500/20 text-cyan-400',
                                                'scheduled' => 'bg-teal-500/20 text-teal-400',
                                                'installing' => 'bg-amber-500/20 text-amber-400',
                                                'suspended' => 'bg-red-500/20 text-red-400',
                                                'terminated' => 'bg-gray-500/20 text-gray-400',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$customer->status] ?? $statusColors['terminated'] }}">
                                            @if($customer->status === 'active')
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                            @endif
                                            {{ $customer->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $customer->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('customers.show', $customer) }}"
                                                class="p-2 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white"
                                                title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}"
                                                class="p-2 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus pelanggan ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 rounded-lg hover:bg-red-500/20 transition-colors text-gray-400 hover:text-red-400"
                                                    title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <h3 class="text-lg font-semibold text-gray-400 mb-2">No Customers Yet</h3>
                                        <p class="text-gray-500 mb-4">Add your first customer to get started.</p>
                                        <a href="{{ route('customers.create') }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add Customer
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden divide-y divide-gray-700/50">
                    @forelse($customers as $customer)
                        <div class="p-4 hover:bg-gray-800/50 transition-colors"
                             data-customer="true"
                             data-name="{{ $customer->name }}"
                             data-phone="{{ $customer->phone ?? '' }}"
                             data-customer-id="{{ $customer->customer_id }}"
                             data-package="{{ $customer->package->name ?? '' }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white">{{ $customer->name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $customer->phone ?? 'No phone' }}</p>
                                    </div>
                                </div>
                                @php
                                    $statusColors = [
                                        'active' => 'bg-emerald-500/20 text-emerald-400',
                                        'registered' => 'bg-blue-500/20 text-blue-400',
                                        'survey' => 'bg-sky-500/20 text-sky-400',
                                        'approved' => 'bg-cyan-500/20 text-cyan-400',
                                        'scheduled' => 'bg-teal-500/20 text-teal-400',
                                        'installing' => 'bg-amber-500/20 text-amber-400',
                                        'suspended' => 'bg-red-500/20 text-red-400',
                                        'terminated' => 'bg-gray-500/20 text-gray-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$customer->status] ?? $statusColors['terminated'] }}">
                                    @if($customer->status === 'active')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                    @endif
                                    {{ $customer->status_label }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                <div>
                                    <p class="text-gray-500 text-xs">ID Pelanggan</p>
                                    <p class="text-gray-300 font-mono text-xs">{{ $customer->customer_id }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Paket</p>
                                    <p class="text-white font-medium">{{ $customer->package->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-500">Bergabung {{ $customer->created_at->format('d M Y') }}</p>
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('customers.show', $customer) }}"
                                       class="p-2 rounded-lg bg-gray-700/50 hover:bg-gray-700 transition-colors text-gray-400 hover:text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}"
                                       class="p-2 rounded-lg bg-gray-700/50 hover:bg-gray-700 transition-colors text-gray-400 hover:text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-400 mb-2">No Customers Yet</h3>
                            <p class="text-gray-500 mb-4">Add your first customer to get started.</p>
                            <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Customer
                            </a>
                        </div>
                    @endforelse
                </div>

                <div class="px-6 py-4 border-t border-gray-700/50">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
