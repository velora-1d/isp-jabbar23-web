<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('packages.index') }}"
                class="p-2 rounded-lg hover:bg-gray-700/50 transition-colors text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('Edit Paket') }}
                </h2>
                <p class="text-gray-400 text-sm mt-1">Update "{{ $package->name }}"</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden shadow-2xl">
                <div class="p-8">

                    <form method="POST" action="{{ route('packages.update', $package) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Package Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nama Paket</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <input type="text" id="name" name="name" value="{{ old('name', $package->name) }}"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Paket Hemat 10 Mbps" required>
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Speed Row -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Download Speed -->
                            <div>
                                <label for="speed_down" class="block text-sm font-semibold text-gray-300 mb-2">Download
                                    (Mbps)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    </div>
                                    <input type="number" id="speed_down" name="speed_down"
                                        value="{{ old('speed_down', $package->speed_down) }}"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="10" min="1" required>
                                </div>
                                @error('speed_down')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Speed -->
                            <div>
                                <label for="speed_up" class="block text-sm font-semibold text-gray-300 mb-2">Upload
                                    (Mbps)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                    </div>
                                    <input type="number" id="speed_up" name="speed_up"
                                        value="{{ old('speed_up', $package->speed_up) }}"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="5" min="1" required>
                                </div>
                                @error('speed_up')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-300 mb-2">Harga Bulanan
                                (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="text-gray-500 font-medium">Rp</span>
                                </div>
                                <input type="number" id="price" name="price" value="{{ old('price', $package->price) }}"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="150000" min="0" required>
                            </div>
                            @error('price')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-300 mb-2">Deskripsi
                                (Opsional)</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                placeholder="Cocok untuk streaming dan gaming...">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ $package->is_active ? 'checked' : '' }}
                                class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-800">
                            <label for="is_active" class="ms-2 text-sm text-gray-400">Aktifkan (tampil ke
                                pelanggan)</label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-700">
                            <a href="{{ route('packages.index') }}"
                                class="px-6 py-3 text-sm font-semibold text-gray-400 hover:text-white transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Perbarui Paket
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
