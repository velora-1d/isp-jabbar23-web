@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Buat Work Order Baru</h1>
            <p class="text-gray-400 mt-1">Buat tiket pekerjaan untuk teknisi</p>
        </div>
        <a href="{{ route('work-orders.index') }}" class="text-gray-400 hover:text-white transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden p-6">
        <form action="{{ route('work-orders.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Customer -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Customer</label>
                <select name="customer_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">-- Pilih Customer (Optional) --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->mid }} - {{ $customer->name }} ({{ $customer->address }})</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Kosongkan jika pekerjaan infrastruktur umum (maintenance backbone, dll)</p>
            </div>

            <!-- ODP Assignment -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Target ODP</label>
                <select name="odp_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">-- Pilih ODP (Optional) --</option>
                    @foreach($odps as $odp)
                        <option value="{{ $odp->id }}">{{ $odp->name }} ({{ $odp->status }}) - {{ $odp->total_ports }} Ports</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Lokasi ODP target untuk instalasi/perbaikan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipe Pekerjaan</label>
                    <select name="type" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="installation">Instalasi Baru</option>
                        <option value="repair">Perbaikan / Troubleshooting</option>
                        <option value="dismantling">Dismantling / Cabut</option>
                        <option value="maintenance">Maintenance Rutin</option>
                        <option value="survey">Survey Lokasi</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Prioritas</label>
                    <select name="priority" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical (Butuh Cepat)</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi Pekerjaan</label>
                <textarea name="description" rows="3" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Jelaskan detail pekerjaan yang harus dilakukan..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Assign Tech -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Assign Teknisi (Lead)</label>
                    <select name="technician_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Belum Ditentukan --</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Scheduled Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Jadwal Pengerjaan</label>
                    <input type="datetime-local" name="scheduled_date" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-700/50 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-cyan-700 transition shadow-lg shadow-blue-500/20">
                    Buat Ticket Work Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
