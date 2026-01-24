@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Mode Tabs -->
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('attendance.index', ['mode' => 'daily']) }}"
                class="px-4 py-2 rounded-xl font-medium transition-all bg-gray-700/50 text-gray-400 hover:bg-gray-700">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Harian
            </a>
            <a href="{{ route('attendance.index', ['mode' => 'range']) }}"
                class="px-4 py-2 rounded-xl font-medium transition-all bg-gray-700/50 text-gray-400 hover:bg-gray-700">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Range Tanggal
            </a>
            <a href="{{ route('attendance.history') }}"
                class="px-4 py-2 rounded-xl font-medium transition-all bg-emerald-600 text-white">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Bulanan
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">Bulan:</span>
                    <input type="month" name="month" value="{{ $month }}" id="monthPicker"
                        class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500"
                        onchange="applyFilters()">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">Karyawan:</span>
                    <select name="user_id" id="userFilter"
                        class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500"
                        onchange="applyFilters()">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $userId == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">Status:</span>
                    <select name="status" id="statusFilter"
                        class="bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2 text-white focus:ring-2 focus:ring-emerald-500"
                        onchange="applyFilters()">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <script>
            function applyFilters() {
                const month = document.getElementById('monthPicker').value;
                const userId = document.getElementById('userFilter').value;
                const status = document.getElementById('statusFilter').value;
                let url = '{{ route('attendance.history') }}?month=' + month;
                if (userId) url += '&user_id=' + userId;
                if (status) url += '&status=' + status;
                window.location.href = url;
            }
        </script>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Hari di Bulan Ini</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_days'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-violet-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Record</p>
                        <p class="text-2xl font-bold text-purple-400">{{ $stats['total_records'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Hadir</p>
                        <p class="text-2xl font-bold text-emerald-400">{{ $stats['present'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Terlambat</p>
                        <p class="text-2xl font-bold text-amber-400">{{ $stats['late'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Absen</p>
                        <p class="text-2xl font-bold text-red-400">{{ $stats['absent'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Karyawan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Clock In</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Clock Out</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jam Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 text-white font-medium">
                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                    <span class="text-gray-500 text-sm block">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($attendance->user->name ?? 'U', 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $attendance->user->name ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-400">{{ $attendance->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-white font-mono">
                                    {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-white font-mono">
                                    {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-300 font-mono">
                                    {{ $attendance->working_hours ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $attendance->status_color }}-500/20 text-{{ $attendance->status_color }}-400">
                                        {{ $attendance->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('attendance.edit', $attendance) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-600/20 hover:bg-amber-600/40 text-amber-400 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p>Belum ada data kehadiran untuk bulan ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($attendances->hasPages())
                <div class="px-6 py-4 border-t border-gray-700/50">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
