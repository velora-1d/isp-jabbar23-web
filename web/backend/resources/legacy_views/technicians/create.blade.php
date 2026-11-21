<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('technicians.index') }}" class="p-2 rounded-lg hover:bg-gray-700/50 transition-colors text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">{{ __('Tambah Teknisi Baru') }}</h2>
                <p class="text-gray-400 text-sm mt-1">Daftarkan karyawan lapangan baru</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden shadow-2xl">
                <div class="p-8">
                    
                    <!-- Info Box -->
                    <div class="mb-6 p-4 rounded-xl bg-blue-500/10 border border-blue-500/20">
                        <p class="text-sm text-blue-400">
                            <strong>ℹ️ Info:</strong> Status teknisi akan otomatis berubah sesuai customer yang ditangani. Teknisi tidak perlu login ke dashboard ini.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('technicians.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Photo Upload -->
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                <div id="photo-preview" class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-2xl font-bold overflow-hidden">
                                    <span id="initials">?</span>
                                    <img id="preview-img" src="" alt="" class="w-full h-full object-cover hidden">
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Foto Profil <span class="text-gray-500 font-normal">(Opsional)</span></label>
                                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg"
                                    onchange="previewPhoto(this)"
                                    class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500/20 file:text-blue-400 hover:file:bg-blue-500/30 cursor-pointer">
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG max. 2MB</p>
                                @error('photo')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Personal Info -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nama Lengkap *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    onkeyup="updateInitials(this.value)"
                                    class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    placeholder="Ahmad Teknisi" required>
                                @error('name')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">Email *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                        placeholder="teknisi@jabbar23.com" required>
                                    @error('email')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-300 mb-2">No. HP *</label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                        placeholder="08123456789" required>
                                    @error('phone')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <!-- Password with visibility toggle -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">Password *</label>
                                    <div class="relative">
                                        <input type="password" id="password" name="password"
                                            class="w-full px-4 py-3 pr-12 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                            placeholder="Min. 8 karakter" required>
                                        <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-white">
                                            <svg id="eyeIcon1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Untuk login nanti di app mobile</p>
                                    @error('password')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-300 mb-2">Konfirmasi Password *</label>
                                    <div class="relative">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="w-full px-4 py-3 pr-12 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                            placeholder="Ulangi password" required>
                                        <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-white">
                                            <svg id="eyeIcon2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-700">
                            <a href="{{ route('technicians.index') }}" class="px-6 py-3 text-sm font-semibold text-gray-400 hover:text-white transition-colors">Batal</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Teknisi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        function previewPhoto(input) {
            const preview = document.getElementById('preview-img');
            const initials = document.getElementById('initials');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    initials.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateInitials(name) {
            const initials = document.getElementById('initials');
            const preview = document.getElementById('preview-img');
            
            if (preview.classList.contains('hidden')) {
                if (name && name.trim()) {
                    const words = name.trim().split(' ');
                    initials.textContent = words.length > 1 
                        ? (words[0][0] + words[1][0]).toUpperCase()
                        : words[0].substring(0, 2).toUpperCase();
                } else {
                    initials.textContent = '?';
                }
            }
        }
    </script>
</x-app-layout>
