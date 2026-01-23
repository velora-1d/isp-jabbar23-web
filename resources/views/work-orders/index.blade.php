@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Perintah Kerja</h1>
                <p class="text-gray-400 mt-1">Operasi Lapangan & Monitoring Tiket</p>
            </div>
            <a href="{{ route('work-orders.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-cyan-600 transition shadow-lg shadow-blue-500/25">
                + Order Baru
            </a>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
                class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Menunggu / Pending</div>
                    <div class="text-2xl font-bold text-white">{{ $stats['pending'] }} Tiket</div>
                </div>
                <div class="p-3 bg-gray-700/50 rounded-lg text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div
                class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <div class="text-xs text-orange-400 uppercase tracking-wider mb-1">Sedang Dikerjakan</div>
                    <div class="text-2xl font-bold text-white">{{ $stats['in_progress'] }} Aktif</div>
                </div>
                <div class="p-3 bg-orange-500/20 rounded-lg text-orange-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
            </div>
            <div
                class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <div class="text-xs text-emerald-400 uppercase tracking-wider mb-1">Selesai Hari Ini</div>
                    <div class="text-2xl font-bold text-white">{{ $stats['completed'] }} Selesai</div>
                </div>
                <div class="p-3 bg-emerald-500/20 rounded-lg text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4">
            <form action="" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <select name="status"
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan
                        </option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Dikerjakan
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="flex-1">
                    <select name="technician_id"
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Teknisi</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" {{ request('technician_id') == $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">Filter</button>
            </form>
        </div>

        <!-- WO List -->
        <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tiket
                                / Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipe
                                / Prioritas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Jadwal / Teknisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Target ODP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($workOrders as $wo)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-white">{{ $wo->ticket_number }}</div>
                                    <div class="text-sm text-gray-400">{{ $wo->customer->name ?? 'Tanpa Pelanggan' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-300 capitalize">{{ $wo->type }}</div>
                                    <span
                                        class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $wo->priority === 'critical' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : ($wo->priority === 'high' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : 'bg-gray-600 text-gray-300') }}">
                                        {{ ucfirst($wo->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">
                                        {{ $wo->scheduled_date ? $wo->scheduled_date->format('d M H:i') : '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $wo->technician->name ?? 'Belum Ditugaskan' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $wo->odp->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $wo->status_color }}-500/20 text-{{ $wo->status_color }}-400 border border-{{ $wo->status_color }}-500/30 capitalize">
                                        {{ str_replace('_', ' ', $wo->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('work-orders.show', $wo) }}"
                                        class="text-blue-400 hover:text-blue-300">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada work order yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $workOrders->links() }}
            </div>
        </div>
    </div>
@endsection
