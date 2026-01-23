<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('Manajemen Karyawan') }}
                </h2>
                <p class="text-gray-400 text-sm mt-1">Kelola anggota tim dan jabatan mereka</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/25">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Karyawan
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">

            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div
                    class="p-4 rounded-xl bg-gradient-to-br from-gray-800 to-gray-800/80 border border-gray-700 backdrop-blur">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-500/20">
                            <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 18">
                                <path
                                    d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-white">{{ $users->count() }}</p>
                            <p class="text-gray-400 text-sm">Total Karyawan</p>
                        </div>
                    </div>
                </div>
                <div
                    class="p-4 rounded-xl bg-gradient-to-br from-gray-800 to-gray-800/80 border border-gray-700 backdrop-blur">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-emerald-500/20">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-white">{{ $users->count() }}</p>
                            <p class="text-gray-400 text-sm">Aktif</p>
                        </div>
                    </div>
                </div>
                <div
                    class="p-4 rounded-xl bg-gradient-to-br from-gray-800 to-gray-800/80 border border-gray-700 backdrop-blur">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-cyan-500/20">
                            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-white">8</p>
                            <p class="text-gray-400 text-sm">Jabatan Terdefinisi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Daftar Karyawan</h3>
                        <div class="relative">
                            <input type="text" placeholder="Cari karyawan..."
                                class="pl-10 pr-4 py-2 bg-gray-900/50 border border-gray-700 rounded-lg text-sm text-gray-300 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-4">Karyawan</th>
                                <th scope="col" class="px-6 py-4">Jabatan</th>
                                <th scope="col" class="px-6 py-4">Bergabung</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-white">{{ $user->name }}</div>
                                                <div class="text-gray-400 text-xs">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @foreach($user->roles as $role)
                                            @php
                                                $colors = [
                                                    'Super Admin' => 'from-red-500 to-rose-600',
                                                    'noc' => 'from-amber-500 to-orange-600',
                                                    'Sales & CS' => 'from-emerald-500 to-teal-600',
                                                    'Finance' => 'from-cyan-500 to-teal-600',
                                                    'Admin NOC' => 'from-cyan-500 to-blue-600',
                                                    'Admin Gudang' => 'from-lime-500 to-green-600',
                                                    'HRD Manager' => 'from-pink-500 to-rose-600',
                                                ];
                                                $gradient = $colors[$role->name] ?? 'from-gray-500 to-gray-600';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white bg-gradient-to-r {{ $gradient }} shadow-lg">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-gray-400">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-400">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="p-2 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white"
                                                title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <!-- Delete Button -->
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 rounded-lg hover:bg-red-500/20 transition-colors text-gray-400 hover:text-red-400"
                                                    title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
