<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customers.index') }}"
                    class="p-2 rounded-lg hover:bg-gray-700/50 transition-colors text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight">{{ $customer->name }}</h2>
                    <p class="text-gray-400 text-sm mt-1">{{ $customer->customer_id }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Payment Score Badge -->
                <span
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-{{ $customer->payment_label_color }}-500/10 text-{{ $customer->payment_label_color }}-400 border border-{{ $customer->payment_label_color }}-500/20">
                    {{ $customer->payment_label }}
                </span>

                <a href="{{ route('customers.paymentHistory', $customer) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Riwayat Bayar
                </a>
                <a href="{{ route('customers.edit', $customer) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Main Info Card -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Personal Info -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Informasi Personal</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Nama Lengkap</p>
                                <p class="text-white font-medium">{{ $customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Telepon</p>
                                <p class="text-white font-medium">{{ $customer->phone ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-white font-medium">{{ $customer->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Alamat</h3>
                        <p class="text-white">{{ $customer->full_address }}</p>
                        @if($customer->latitude && $customer->longitude)
                            <p class="text-gray-500 text-sm mt-2">
                                📍 {{ $customer->latitude }}, {{ $customer->longitude }}
                            </p>
                        @endif
                    </div>

                    <!-- Installation Team -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Tim Instalasi & Teknis
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">PIC / Teknisi</p>
                                <p class="text-white font-medium">
                                    {{ $customer->technician->name ?? 'Belum ditentukan' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jumlah Tim</p>
                                <p class="text-white font-medium">{{ $customer->team_size ?? '-' }} orang</p>
                            </div>
                            
                            <!-- OLT Info -->
                            @if($customer->olt_id)
                                <div class="col-span-2 pt-4 border-t border-gray-700 mt-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-xs text-gray-500">Jalur OLT</p>
                                            <p class="text-white font-mono text-sm">
                                                {{ $customer->olt->name }} 
                                                <span class="text-gray-500">({{ $customer->onu_index }})</span>
                                            </p>
                                        </div>
                                        <button onclick="checkSignal('{{ $customer->olt->id }}', '{{ $customer->onu_index }}')" 
                                            class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs rounded-lg flex items-center gap-1 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Cek Redaman
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Signal Modal -->
                    <div id="signalModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            
                            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                        Hasil Cek Redaman (Optical Signal)
                                    </h3>
                                    <div class="mt-4">
                                        <div id="signalLoading" class="text-center py-4 text-gray-400">
                                            <svg class="animate-spin h-8 w-8 text-purple-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Menghubungi OLT...
                                        </div>
                                        <div id="signalResult" class="hidden space-y-4">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="bg-gray-700/50 p-4 rounded-xl text-center">
                                                    <p class="text-gray-400 text-xs">Rx Power (Terima)</p>
                                                    <p class="text-2xl font-bold text-white" id="rxPower">-</p>
                                                </div>
                                                <div class="bg-gray-700/50 p-4 rounded-xl text-center">
                                                    <p class="text-gray-400 text-xs">Tx Power (Kirim)</p>
                                                    <p class="text-2xl font-bold text-white" id="txPower">-</p>
                                                </div>
                                            </div>
                                            <div id="signalStatus" class="p-3 rounded-lg text-center text-sm font-medium"></div>
                                        </div>
                                        <div id="signalError" class="hidden p-4 bg-red-900/20 border border-red-500/30 rounded-lg text-red-400 text-sm"></div>
                                    </div>
                                </div>
                                <div class="bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="button" onclick="closeSignalModal()" class="w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function checkSignal(oltId, onuIndex) {
                            const modal = document.getElementById('signalModal');
                            const loading = document.getElementById('signalLoading');
                            const result = document.getElementById('signalResult');
                            const error = document.getElementById('signalError');
                            
                            modal.classList.remove('hidden');
                            loading.classList.remove('hidden');
                            result.classList.add('hidden');
                            error.classList.add('hidden');

                            fetch(`/network/olts/${oltId}/check-signal?onu_index=${encodeURIComponent(onuIndex)}`)
                                .then(response => response.json())
                                .then(data => {
                                    loading.classList.add('hidden');
                                    if (data.status === 'error') {
                                        error.textContent = data.message;
                                        error.classList.remove('hidden');
                                    } else {
                                        document.getElementById('rxPower').innerText = data.rx_power + ' dBm';
                                        document.getElementById('txPower').innerText = data.tx_power + ' dBm';
                                        
                                        const statusDiv = document.getElementById('signalStatus');
                                        if (data.status === 'normal') {
                                            statusDiv.className = 'p-3 rounded-lg text-center text-sm font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                                            statusDiv.innerText = 'Sinyal Normal ✅';
                                        } else {
                                            statusDiv.className = 'p-3 rounded-lg text-center text-sm font-medium bg-red-500/20 text-red-400 border border-red-500/30';
                                            statusDiv.innerText = 'Sinyal Kritis / Los ⚠️';
                                        }
                                        
                                        result.classList.remove('hidden');
                                    }
                                })
                                .catch(err => {
                                    loading.classList.add('hidden');
                                    error.textContent = "Gagal menghubungi server.";
                                    error.classList.remove('hidden');
                                });
                        }

                        function closeSignalModal() {
                            document.getElementById('signalModal').classList.add('hidden');
                        }
                    </script>

                    <!-- Status History Timeline -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Riwayat Status
                        </h3>
                        @if($customer->statusLogs->count() > 0)
                            <div class="relative">
                                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-700"></div>
                                <div class="space-y-4">
                                    @foreach ($customer->statusLogs as $log)
                                        @php
                                            $logColors = [
                                                'active' => 'bg-emerald-500',
                                                'registered' => 'bg-blue-500',
                                                'survey' => 'bg-sky-500',
                                                'approved' => 'bg-cyan-500',
                                                'scheduled' => 'bg-teal-500',
                                                'installing' => 'bg-amber-500',
                                                'suspended' => 'bg-red-500',
                                                'terminated' => 'bg-gray-500',
                                            ];
                                        @endphp
                                        <div class="relative flex items-start pl-10">
                                            <span
                                                class="absolute left-2 w-4 h-4 rounded-full {{ $logColors[$log->status] ?? 'bg-gray-500' }} border-2 border-gray-800"></span>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <p class="font-semibold text-white">{{ $log->status_label }}</p>
                                                    <span
                                                        class="text-xs text-gray-500">{{ $log->changed_at->format('d M Y, H:i') }}</span>
                                                </div>
                                                <p class="text-sm text-gray-400">
                                                    @if($log->changedByUser)
                                                        oleh {{ $log->changedByUser->name }}
                                                    @else
                                                        oleh System
                                                    @endif
                                                </p>
                                                @if($log->notes)
                                                    <p class="text-xs text-gray-500 mt-1">{{ $log->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada riwayat status</p>
                        @endif
                    </div>

                    <!-- Notes -->
                    @if($customer->notes)
                        <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                            <h3 class="text-lg font-bold text-white mb-4">Catatan</h3>
                            <p class="text-gray-400">{{ $customer->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Info -->
                <div class="space-y-6">

                    <!-- Technician Actions -->
                    @if(auth()->user()->hasRole('noc') || auth()->user()->hasRole('super-admin'))
                        <div class="rounded-2xl bg-gray-800 border border-blue-500/30 p-6 shadow-lg shadow-blue-500/10">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Update Progres
                            </h3>
                            <form action="{{ route('customers.updateStatus', $customer) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Status Baru</label>
                                        <select name="status"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                            @foreach ($statuses as $key => $label)
                                                <option value="{{ $key }}" {{ $customer->status == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Catatan /
                                            Keterangan</label>
                                        <textarea name="notes" rows="2"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                            placeholder="Contoh: Kabel ditarik 50m, ODP aman."></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">
                                        Simpan & Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Status Card -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Status</h3>
                        @php
                            $statusColors = [
                                'active' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                'registered' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                'survey' => 'bg-sky-500/20 text-sky-400 border-sky-500/30',
                                'approved' => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30',
                                'scheduled' => 'bg-teal-500/20 text-teal-400 border-teal-500/30',
                                'installing' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                'suspended' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                'terminated' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                            ];
                        @endphp
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border {{ $statusColors[$customer->status] ?? $statusColors['terminated'] }}">
                            @if($customer->status === 'active')
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                            @endif
                            {{ $customer->status_label }}
                        </span>
                    </div>

                    <!-- Package Card -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Langganan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Paket</p>
                                <p class="text-white font-semibold">{{ $customer->package->name ?? 'N/A' }}</p>
                                <p class="text-cyan-400 text-sm">{{ $customer->package->formatted_speed ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Harga Bulanan</p>
                                <p class="text-2xl font-bold text-white">
                                    {{ $customer->package->formatted_price ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dates Card -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Tanggal Penting</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Tanggal Instalasi</p>
                                <p class="text-white font-medium">
                                    {{ $customer->installation_date?->format('d M Y') ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal Tagihan</p>
                                <p class="text-white font-medium">{{ $customer->billing_date?->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Terdaftar</p>
                                <p class="text-white font-medium">{{ $customer->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Installed Assets Card -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            Perangkat Terpasang (CPE)
                        </h3>
                        @if($customer->inventorySerials->count() > 0)
                            <div class="space-y-3">
                                @foreach ($customer->inventorySerials as $serial)
                                    <div class="p-3 bg-gray-900/50 rounded-xl border border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-white font-medium">{{ $serial->item->name }}</p>
                                                <p class="text-xs text-gray-500 font-mono">{{ $serial->serial_number }}</p>
                                            </div>
                                            <span class="px-2 py-0.5 text-[10px] bg-emerald-500/10 text-emerald-400 rounded-full border border-emerald-500/20">Installed</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500 text-sm mb-3">Belum ada perangkat yang terdaftar.</p>
                                @if(auth()->user()->hasRole('noc') || auth()->user()->hasRole('super-admin'))
                                    <button onclick="document.getElementById('assign-asset-modal').classList.remove('hidden')" 
                                        class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold rounded-lg transition">
                                        + Pasang Perangkat
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Assign Asset Modal -->
                    <div id="assign-asset-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                        <div class="flex items-center justify-center min-h-screen px-4">
                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
                            <div class="inline-block w-full max-w-md p-6 bg-gray-800 border border-gray-700 shadow-xl rounded-2xl transform transition-all z-10">
                                <h3 class="text-lg font-bold text-white mb-4">Pasang Perangkat Baru</h3>
                                <form action="{{ route('inventory.assign-serial') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400 mb-1">Pilih Perangkat (Tersedia)</label>
                                            <select name="inventory_serial_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white p-2.5 text-sm">
                                                @foreach ($availableSerials as $sn)
                                                    <option value="{{ $sn->id }}">{{ $sn->item->name }} - {{ $sn->serial_number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400 mb-1">Catatan Tambahan</label>
                                            <textarea name="notes" rows="2" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white p-2.5 text-sm" placeholder="Contoh: Terpasang di ruang tamu"></textarea>
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end gap-3">
                                        <button type="button" onclick="document.getElementById('assign-asset-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-600 transition">Batal</button>
                                        <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-lg transition">Simpan Pemasangan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Card -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                </path>
                            </svg>
                            QR Code Pembayaran
                        </h3>
                        <div class="flex flex-col items-center">
                            <div class="bg-white p-3 rounded-xl">
                                {!! $customer->qr_code !!}
                            </div>
                            <p class="text-xs text-gray-500 mt-3 text-center">Scan QR ini untuk pembayaran cash di
                                kantor</p>
                            <p class="text-xs text-gray-600 mt-1 font-mono">{{ $customer->payment_token }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
