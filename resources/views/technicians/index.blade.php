<x-app-layout>
    <x-slot name="header">
        <x-slot name="header">
            <x-filter-bar :filters="$filters ?? []">
                <x-slot name="global">
                    <x-filter-global :search-placeholder="'Cari Teknisi...'" />
                </x-slot>

                <x-slot name="filters">
                    <x-filter-select name="status" label="Status" :options="$statuses" :selected="request('status')" />
                </x-slot>

                <x-slot name="actions">
                    <a href="{{ route('technicians.create') }}"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/25">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Tambah Teknisi
                    </a>
                </x-slot>
            </x-filter-bar>
        </x-slot>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 rounded-xl bg-gray-800 border border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-500/20">
                            <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 18">
                                <path
                                    d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                            <p class="text-gray-400 text-sm">Total Teknisi</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-gray-800 border border-emerald-500/30">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-emerald-500/20">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-emerald-400">{{ $stats['available'] }}</p>
                            <p class="text-gray-400 text-sm">Tersedia</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-gray-800 border border-amber-500/30">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-amber-500/20">
                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-amber-400">{{ $stats['on_task'] }}</p>
                            <p class="text-gray-400 text-sm">Sedang Tugas</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-gray-800 border border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-gray-500/20">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-400">{{ $stats['off_duty'] }}</p>
                            <p class="text-gray-400 text-sm">Tidak Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technician Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($technicians as $tech)
                    @php
                        $status = $tech->computed_status;
                        $statusColors = [
                            'available' => 'border-emerald-500/50 bg-emerald-500/5',
                            'on_task' => 'border-amber-500/50 bg-amber-500/5',
                            'off_duty' => 'border-gray-600 bg-gray-800',
                        ];
                        $badgeColors = [
                            'available' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                            'on_task' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                            'off_duty' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                        ];
                    @endphp
                    <div
                        class="rounded-2xl bg-gray-800 border {{ $statusColors[$status] ?? 'border-gray-700' }} overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="p-6">
                            <!-- Header with photo/avatar and status -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    @if($tech->photo)
                                        <img src="{{ $tech->photo_url }}" alt="{{ $tech->name }}"
                                            class="w-14 h-14 rounded-full object-cover ring-2 ring-gray-700">
                                    @else
                                        <div
                                            class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($tech->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-white text-lg">{{ $tech->name }}</h3>
                                        <p class="text-gray-500 text-sm">{{ $tech->phone ?? 'No phone' }}</p>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $badgeColors[$status] ?? $badgeColors['off_duty'] }}">
                                    @if($status === 'available')
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                                    @elseif($status === 'on_task')
                                        <span class="w-2 h-2 rounded-full bg-amber-400 mr-2 animate-pulse"></span>
                                    @endif
                                    {{ $tech->status_label }}
                                </span>
                            </div>

                            <!-- Current Task Info (if on task) -->
                            @if($status === 'on_task' && $tech->currentTasks->count() > 0)
                                <div class="mb-4 p-4 rounded-xl bg-gray-900/70 border border-amber-500/20">
                                    <p class="text-xs text-amber-400 font-semibold mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        SEDANG MENGERJAKAN
                                    </p>
                                    @foreach($tech->currentTasks->take(2) as $task)
                                        <div
                                            class="flex items-start justify-between {{ !$loop->last ? 'mb-3 pb-3 border-b border-gray-700' : '' }}">
                                            <div class="flex-1">
                                                <p class="font-medium text-white">{{ $task->name }}</p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ Str::limit($task->kelurahan . ', ' . $task->kecamatan, 30) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="ml-3">
                                                @php
                                                    $taskStatusColors = [
                                                        'survey' => 'bg-sky-500/20 text-sky-400',
                                                        'approved' => 'bg-cyan-500/20 text-cyan-400',
                                                        'scheduled' => 'bg-teal-500/20 text-teal-400',
                                                        'installing' => 'bg-amber-500/20 text-amber-400',
                                                    ];
                                                @endphp
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs font-medium {{ $taskStatusColors[$task->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                                                    {{ $task->status_label }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($tech->currentTasks->count() > 2)
                                        <p class="text-xs text-gray-500 mt-2">+{{ $tech->currentTasks->count() - 2 }} tugas lainnya
                                        </p>
                                    @endif
                                </div>
                            @elseif($status === 'available')
                                <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-center">
                                    <svg class="w-8 h-8 text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-emerald-400 font-medium">Siap menerima tugas baru</p>
                                </div>
                            @elseif($status === 'off_duty')
                                <div class="mb-4 p-4 rounded-xl bg-gray-700/50 border border-gray-600 text-center">
                                    <svg class="w-8 h-8 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                        </path>
                                    </svg>
                                    <p class="text-gray-400">Teknisi tidak aktif</p>
                                </div>
                            @endif

                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-2 text-center mb-4">
                                <div class="p-2 rounded-lg bg-gray-900/50">
                                    <p class="text-lg font-bold text-white">{{ $tech->current_tasks_count }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase">Aktif</p>
                                </div>
                                <div class="p-2 rounded-lg bg-gray-900/50">
                                    <p class="text-lg font-bold text-emerald-400">{{ $tech->completed_customers_count }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase">Selesai</p>
                                </div>
                                <div class="p-2 rounded-lg bg-gray-900/50">
                                    <p class="text-lg font-bold text-gray-400">{{ $tech->assigned_customers_count }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase">Total</p>
                                </div>
                            </div>

                            @if($tech->completedCustomers->count() > 0)
                                <div
                                    class="text-[11px] text-gray-500 mb-4 flex items-center bg-gray-900/30 px-3 py-1.5 rounded-lg border border-gray-700/50">
                                    <span class="mr-2">🏁</span>
                                    <span class="flex-1">Terakhir:
                                        <strong>{{ $tech->completedCustomers->first()->name }}</strong></span>
                                    <span
                                        class="text-[10px]">{{ $tech->completedCustomers->first()->updated_at->diffForHumans() }}</span>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('technicians.show', $tech) }}"
                                        class="p-2 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white"
                                        title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('technicians.edit', $tech) }}"
                                        class="p-2 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    @if($tech->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tech->phone) }}" target="_blank"
                                            class="p-2 rounded-lg hover:bg-emerald-500/20 transition-colors text-gray-400 hover:text-emerald-400"
                                            title="WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                <!-- Toggle Active -->
                                <form action="{{ route('technicians.toggleActive', $tech) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $tech->is_active ? 'bg-gray-700 text-gray-300 hover:bg-red-500/20 hover:text-red-400' : 'bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30' }}">
                                        {{ $tech->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="rounded-2xl bg-gray-800 border border-gray-700 p-12 text-center">
                            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-400 mb-2">Belum Ada Teknisi</h3>
                            <p class="text-gray-500 mb-4">Tambahkan teknisi lapangan pertama Anda.</p>
                            <a href="{{ route('technicians.create') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Teknisi
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
