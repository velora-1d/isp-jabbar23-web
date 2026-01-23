<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('customers.index') }}"
                class="p-2 rounded-lg hover:bg-gray-700/50 transition-colors text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">{{ __('Edit Pelanggan') }}</h2>
                <p class="text-gray-400 text-sm mt-1">Update "{{ $customer->name }}"</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden shadow-2xl">
                <div class="p-8">

                    <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Customer ID (readonly) -->
                        <div class="p-4 rounded-xl bg-gray-900/50 border border-gray-700">
                            <span class="text-xs text-gray-500">ID Pelanggan</span>
                            <p class="font-mono text-white">{{ $customer->customer_id }}</p>
                        </div>

                        <!-- Section: Personal Info -->
                        <div class="pb-6 border-b border-gray-700">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Personal Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nama
                                        Lengkap *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        required>
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-300 mb-2">No.
                                        Telepon</label>
                                    <input type="text" id="phone" name="phone"
                                        value="{{ old('phone', $customer->phone) }}"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="email"
                                        class="block text-sm font-semibold text-gray-300 mb-2">Email</label>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $customer->email) }}"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Section: Address -->
                        <div class="pb-6 border-b border-gray-700">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Informasi Alamat
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="address" class="block text-sm font-semibold text-gray-300 mb-2">Alamat
                                        Lengkap *</label>
                                    <textarea id="address" name="address" rows="2"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                        required>{{ old('address', $customer->address) }}</textarea>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <label for="rt_rw"
                                            class="block text-sm font-semibold text-gray-300 mb-2">RT/RW</label>
                                        <input type="text" id="rt_rw" name="rt_rw"
                                            value="{{ old('rt_rw', $customer->rt_rw) }}"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="kelurahan"
                                            class="block text-sm font-semibold text-gray-300 mb-2">Kelurahan</label>
                                        <input type="text" id="kelurahan" name="kelurahan"
                                            value="{{ old('kelurahan', $customer->kelurahan) }}"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="kecamatan"
                                            class="block text-sm font-semibold text-gray-300 mb-2">Kecamatan</label>
                                        <input type="text" id="kecamatan" name="kecamatan"
                                            value="{{ old('kecamatan', $customer->kecamatan) }}"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="kode_pos"
                                            class="block text-sm font-semibold text-gray-300 mb-2">Kode Pos</label>
                                        <input type="text" id="kode_pos" name="kode_pos"
                                            value="{{ old('kode_pos', $customer->kode_pos) }}"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="kabupaten"
                                            class="block text-sm font-semibold text-gray-300 mb-2">Kabupaten/Kota</label>
                                        <input type="text" id="kabupaten" name="kabupaten"
                                            value="{{ old('kabupaten', $customer->kabupaten) }}"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="provinsi"
                                            class="block text-sm font-semibold text-gray-300 mb-2">Provinsi</label>
                                        <input type="text" id="provinsi" name="provinsi"
                                            value="{{ old('provinsi', $customer->provinsi) }}"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="latitude"
                                            class="block text-sm font-semibold text-gray-300 mb-2">Latitude</label>
                                        <input type="text" id="latitude" name="latitude"
                                            value="{{ old('latitude', $customer->latitude) }}"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="longitude"
                                            class="block text-sm font-semibold text-gray-300 mb-2">Longitude</label>
                                        <input type="text" id="longitude" name="longitude"
                                            value="{{ old('longitude', $customer->longitude) }}"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Subscription -->
                        <div class="pb-6 border-b border-gray-700">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Detail Langganan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="package_id" class="block text-sm font-semibold text-gray-300 mb-2">Paket
                                        Internet *</label>
                                    <select id="package_id" name="package_id" required
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id }}" {{ old('package_id', $customer->package_id) == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }} - {{ $package->formatted_price }}/bulan
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-300 mb-2">Status
                                        *</label>
                                    <select id="status" name="status" required
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        @foreach($statuses as $key => $label)
                                            <option value="{{ $key }}" {{ old('status', $customer->status) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="installation_date"
                                        class="block text-sm font-semibold text-gray-300 mb-2">Tanggal Instalasi</label>
                                    <input type="date" id="installation_date" name="installation_date"
                                        value="{{ old('installation_date', $customer->installation_date?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label for="billing_date"
                                        class="block text-sm font-semibold text-gray-300 mb-2">Tanggal Tagihan</label>
                                    <input type="date" id="billing_date" name="billing_date"
                                        value="{{ old('billing_date', $customer->billing_date?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Section: Installation Team -->
                        <div class="pb-6 border-b border-gray-700">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Tim Instalasi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="assigned_to" class="block text-sm font-semibold text-gray-300 mb-2">PIC
                                        / Teknisi</label>
                                    <select id="assigned_to" name="assigned_to"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">-- Belum Ditentukan --</option>
                                        @foreach($technicians as $tech)
                                            <option value="{{ $tech->id }}" {{ old('assigned_to', $customer->assigned_to) == $tech->id ? 'selected' : '' }}>
                                                {{ $tech->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="team_size" class="block text-sm font-semibold text-gray-300 mb-2">Jumlah
                                        Tim</label>
                                    <input type="number" id="team_size" name="team_size"
                                        value="{{ old('team_size', $customer->team_size) }}" min="1" max="10"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Contoh: 2">
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-300 mb-2">Catatan</label>
                            <textarea id="notes" name="notes" rows="2"
                                class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none">{{ old('notes', $customer->notes) }}</textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-700">
                            <a href="{{ route('customers.index') }}"
                                class="px-6 py-3 text-sm font-semibold text-gray-400 hover:text-white transition-colors">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Perbarui Pelanggan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
