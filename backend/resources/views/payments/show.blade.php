@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-white">Detail Pembayaran</h1>
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-{{ $payment->status_color }}-500/20 text-{{ $payment->status_color }}-400 border border-{{ $payment->status_color }}-500/30">
                    {{ $payment->status_label }}
                </span>
            </div>
            <p class="text-gray-400 mt-1">#{{ $payment->payment_number }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('payments.index') }}" class="text-gray-400 hover:text-white transition px-3">Kembali</a>
            
            @if($payment->status === 'pending')
                <form action="{{ route('payments.verify', $payment) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600/20 text-blue-400 border border-blue-500/30 rounded-lg hover:bg-blue-600/30 transition">
                        Verifikasi
                    </button>
                </form>
            @endif

            @if(in_array($payment->status, ['pending', 'verified']))
                <form action="{{ route('payments.confirm', $payment) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            @endif

            @if($payment->status !== 'rejected' && $payment->status !== 'confirmed')
                <button onclick="document.getElementById('reject-modal').classList.remove('hidden')" class="px-4 py-2 bg-red-600/20 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-600/30 transition">
                    Tolak
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Informasi Transaksi</h3>
                <div class="grid grid-cols-2 gap-y-4 text-sm">
                    <div>
                        <div class="text-gray-500 mb-1">Customer</div>
                        <div class="font-medium text-white text-lg">{{ $payment->customer->name }}</div>
                        <div class="text-gray-400">{{ $payment->customer->customer_id }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-500 mb-1">Nominal</div>
                        <div class="font-bold text-emerald-400 text-2xl">Rp {{ number_format($payment->amount) }}</div>
                    </div>
                </div>
                
                <hr class="border-gray-700/50 my-6">

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-gray-500 mb-1">Invoice Terkait</div>
                        <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-blue-400 hover:underline font-medium">
                            {{ $payment->invoice->invoice_number }}
                        </a>
                    </div>
                    <div>
                        <div class="text-gray-500 mb-1">Waktu Pembayaran</div>
                        <div class="text-white font-medium">{{ $payment->paid_at->format('d F Y, H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 mb-1">Metode Bayar</div>
                        <div class="text-white font-medium">{{ $payment->payment_method_label }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 mb-1">No. Referensi</div>
                        <div class="text-white font-medium font-mono">{{ $payment->reference_number ?: '-' }}</div>
                    </div>
                </div>

                @if($payment->notes)
                <div class="mt-6 bg-gray-900/50 rounded-lg p-4 border border-gray-700/50">
                    <div class="text-gray-500 text-xs uppercase tracking-wider mb-2">Catatan</div>
                    <p class="text-gray-300">{{ $payment->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Proof Display -->
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Bukti Pembayaran</h3>
                @if($payment->proof_file)
                    @php
                        $ext = pathinfo($payment->proof_file, PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                        <div class="rounded-lg overflow-hidden border border-gray-700">
                            <img src="{{ Storage::url($payment->proof_file) }}" alt="Bukti Transfer" class="w-full h-auto object-contain max-h-[500px]">
                        </div>
                    @elseif($ext === 'pdf')
                        <div class="bg-gray-900 rounded-lg p-8 text-center border border-gray-700">
                            <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <p class="text-gray-300 mb-4">File Bukti dalam format PDF</p>
                            <a href="{{ Storage::url($payment->proof_file) }}" target="_blank" class="text-blue-400 hover:text-blue-300 hover:underline">Lihat PDF</a>
                        </div>
                    @else
                        <div class="bg-gray-900 rounded-lg p-4 text-center">
                            <a href="{{ Storage::url($payment->proof_file) }}" target="_blank" class="text-blue-400 hover:underline">Download Bukti ({{ strtoupper($ext) }})</a>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-900/50 rounded-lg p-8 text-center border border-gray-700 border-dashed">
                        <p class="text-gray-500">Tidak ada bukti pembayaran yang diupload.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Timeline Log -->
        <div class="space-y-6">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Riwayat Proses</h3>
                <div class="space-y-6 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-700 before:to-transparent">
                    
                    <!-- Timeline Items -->
                    @if($payment->verified_at)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-5 h-5 ml-0 rounded-full border border-white/30 bg-{{ $payment->status === 'rejected' ? 'red' : 'green' }}-500 shadow z-10"></div>
                        <div class="ml-4 pl-4 md:ml-0 md:pl-0 md:px-6 w-full text-left">
                            <div class="font-medium text-white">{{ $payment->status === 'rejected' ? 'Ditolak' : 'Diverifikasi' }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->verified_at->format('d M H:i') }} oleh {{ $payment->verifiedBy->name ?? 'System' }}</div>
                        </div>
                    </div>
                    @endif

                    <!-- Created Item -->
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-5 h-5 ml-0 rounded-full border border-white/30 bg-blue-500 shadow z-10"></div>
                        <div class="ml-4 pl-4 md:ml-0 md:pl-0 md:px-6 w-full text-left">
                            <div class="font-medium text-white">Pembayaran Dibuat</div>
                            <div class="text-xs text-gray-500">{{ $payment->created_at->format('d M H:i') }} oleh {{ $payment->processedBy->name ?? 'System' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('reject-modal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-700">
            <form action="{{ route('payments.reject', $payment) }}" method="POST">
                @csrf
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Tolak Pembayaran</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-400">Apakah Anda yakin ingin menolak pembayaran ini? Silakan berikan alasan penolakan.</p>
                        <textarea name="notes" rows="3" class="mt-3 w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tolak Pembayaran
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('reject-modal').classList.add('hidden')">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
