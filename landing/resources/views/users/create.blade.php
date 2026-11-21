<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('users.index') }}"
                class="p-2 rounded-lg hover:bg-gray-700/50 transition-colors text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('Tambah Karyawan Baru') }}
                </h2>
                <p class="text-gray-400 text-sm mt-1">Buat akun anggota tim baru dengan data lengkap</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="rounded-2xl bg-gradient-to-br from-gray-800/80 to-gray-900/80 border border-gray-700/50 backdrop-blur overflow-hidden shadow-2xl">
                <div class="p-8">

                    <form method="POST" action="{{ route('users.store') }}" class="space-y-8">
                        @csrf

                        <!-- Personal Information Section -->
                        <div>
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Pribadi
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                                    <input type="text" id="name" name="name"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="John Doe" required>
                                </div>

                                <!-- NIK -->
                                <div>
                                    <label for="nik" class="block text-sm font-semibold text-gray-300 mb-2">NIK / No. Induk Karyawan</label>
                                    <input type="text" id="nik" name="nik"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="KRY-2024-001">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">Email <span class="text-red-400">*</span></label>
                                    <input type="email" id="email" name="email"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="john@isp.com" required>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-300 mb-2">No. Telepon</label>
                                    <input type="text" id="phone" name="phone"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="08123456789">
                                </div>

                                <!-- Date of Birth -->
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-semibold text-gray-300 mb-2">Tanggal Lahir</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="gender" class="block text-sm font-semibold text-gray-300 mb-2">Jenis Kelamin</label>
                                    <select id="gender" name="gender"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none cursor-pointer">
                                        <option value="" class="bg-gray-800">-- Pilih --</option>
                                        <option value="male" class="bg-gray-800">Laki-laki</option>
                                        <option value="female" class="bg-gray-800">Perempuan</option>
                                    </select>
                                </div>

                                <!-- Address (Full width) -->
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-semibold text-gray-300 mb-2">Alamat Lengkap</label>
                                    <textarea id="address" name="address" rows="3"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Jl. Raya No. 123, Kec. ABC, Kota XYZ"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information Section -->
                        <div class="border-t border-gray-700/50 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Informasi Pekerjaan
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Role -->
                                <div>
                                    <label for="role" class="block text-sm font-semibold text-gray-300 mb-2">Role / Hak Akses <span class="text-red-400">*</span></label>
                                    <select id="role" name="role"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none cursor-pointer" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" class="bg-gray-800">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Position -->
                                <div>
                                    <label for="position" class="block text-sm font-semibold text-gray-300 mb-2">Jabatan</label>
                                    <input type="text" id="position" name="position"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Field Technician">
                                </div>

                                <!-- Department -->
                                <div>
                                    <label for="department" class="block text-sm font-semibold text-gray-300 mb-2">Divisi / Departemen</label>
                                    <input type="text" id="department" name="department"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Network Operations">
                                </div>

                                <!-- Join Date -->
                                <div>
                                    <label for="join_date" class="block text-sm font-semibold text-gray-300 mb-2">Tanggal Bergabung</label>
                                    <input type="date" id="join_date" name="join_date"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact Section -->
                        <div class="border-t border-gray-700/50 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Kontak Darurat
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Emergency Contact Name -->
                                <div>
                                    <label for="emergency_contact_name" class="block text-sm font-semibold text-gray-300 mb-2">Nama Kontak Darurat</label>
                                    <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Jane Doe (Istri)">
                                </div>

                                <!-- Emergency Contact Phone -->
                                <div>
                                    <label for="emergency_contact_phone" class="block text-sm font-semibold text-gray-300 mb-2">No. Telepon Darurat</label>
                                    <input type="text" id="emergency_contact_phone" name="emergency_contact_phone"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="08123456789">
                                </div>
                            </div>
                        </div>

                        <!-- Account Security Section -->
                        <div class="border-t border-gray-700/50 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Keamanan Akun
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div x-data="{ show: false }">
                                    <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">Password <span class="text-red-400">*</span></label>
                                    <div class="relative">
                                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                                            class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                                            placeholder="••••••••" required>
                                        <button type="button" @click="show = !show"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-300 transition-colors">
                                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Minimal 8 karakter</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-700/50">
                            <a href="{{ route('users.index') }}"
                                class="px-6 py-3 text-sm font-semibold text-gray-400 hover:text-white transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Karyawan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
