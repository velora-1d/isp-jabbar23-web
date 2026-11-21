<x-app-layout>
    <x-slot name="header">
        <x-filter-bar :filters="$filters ?? []">
            <x-slot name="global">
                <x-filter-global :search-placeholder="'Cari Paket...'" />
            </x-slot>

            <x-slot name="filters">
                <x-filter-select name="status" label="Status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']"
                    :selected="request('status')" />
            </x-slot>

            <x-slot name="actions">
                <a href="{{ route('packages.create') }}"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/25">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Paket
                </a>
            </x-slot>
        </x-filter-bar>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">

            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 rounded-xl bg-gray-800 border border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-500/20">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-white">{{ $packages->count() }}</p>
                            <p class="text-gray-400 text-sm">Total Paket</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-gray-800 border border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-emerald-500/20">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-white">{{ $packages->where('is_active', true)->count() }}
                            </p>
                            <p class="text-gray-400 text-sm">Active</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-gray-800 border border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-amber-500/20">
                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-white">{{ $packages->max('speed_down') ?? 0 }} Mbps</p>
                            <p class="text-gray-400 text-sm">Fastest Package</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($packages as $package)
                    <div class="relative group">
                        <!-- Glow Effect -->
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-500">
                        </div>

                        <!-- Card -->
                        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $package->name }}</h3>
                                    <p class="text-cyan-400 font-semibold text-sm">{{ $package->formatted_speed }}</p>
                                </div>
                                @if($package->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                                {{ $package->description ?? 'No description' }}
                            </p>

                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">Monthly Price</p>
                                    <p class="text-2xl font-bold text-white">{{ $package->formatted_price }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('packages.edit', $package) }}"
                                        class="p-2 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('packages.destroy', $package) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus paket ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-lg hover:bg-red-500/20 transition-colors text-gray-400 hover:text-red-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12 bg-gray-800 rounded-2xl border border-gray-700">
                            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-400 mb-2">No Packages Yet</h3>
                            <p class="text-gray-500 mb-4">Create your first internet package to get started.</p>
                            <a href="{{ route('packages.create') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Package
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
