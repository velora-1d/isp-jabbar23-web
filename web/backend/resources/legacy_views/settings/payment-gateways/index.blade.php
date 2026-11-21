@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Payment Gateways
            </h1>
            <p class="text-gray-400 mt-1">Konfigurasi metode pembayaran dan gateway</p>
        </div>
    </div>

    <form action="{{ route('settings.payment-gateways.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Midtrans -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Midtrans</h3>
                        <p class="text-sm text-gray-400">Payment gateway utama Indonesia</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="midtrans_enabled" value="1" {{ $gateways['midtrans']['enabled'] ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-600 peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Server Key</label>
                    <input type="password" name="midtrans_server_key" value="{{ $gateways['midtrans']['server_key'] }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="SB-Mid-server-xxxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Client Key</label>
                    <input type="text" name="midtrans_client_key" value="{{ $gateways['midtrans']['client_key'] }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="SB-Mid-client-xxxxx">
                </div>
                <div class="md:col-span-2 flex items-center gap-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="midtrans_is_production" value="1" {{ $gateways['midtrans']['is_production'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-600 peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                    <span class="text-gray-300">Production Mode</span>
                    <span class="text-xs text-amber-400">(Sandbox jika tidak dicentang)</span>
                </div>
            </div>
        </div>

        <!-- Xendit -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-indigo-500 to-teal-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Xendit</h3>
                        <p class="text-sm text-gray-400">Virtual Account & E-Wallet</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="xendit_enabled" value="1" {{ $gateways['xendit']['enabled'] ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-600 peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Secret Key</label>
                    <input type="password" name="xendit_secret_key" value="{{ $gateways['xendit']['secret_key'] }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="xnd_development_xxxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Public Key</label>
                    <input type="text" name="xendit_public_key" value="{{ $gateways['xendit']['public_key'] }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="xnd_public_development_xxxxx">
                </div>
            </div>
        </div>

        <!-- Manual Transfer -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Transfer Manual</h3>
                        <p class="text-sm text-gray-400">Transfer bank manual dengan konfirmasi</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="manual_transfer_enabled" value="1" {{ $gateways['manual']['enabled'] ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-600 peer-focus:ring-2 peer-focus:ring-emerald-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                </label>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Nama Bank</label>
                    <input type="text" name="manual_bank_name" value="{{ $gateways['manual']['bank_name'] }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="BCA / Mandiri / BRI">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Nomor Rekening</label>
                    <input type="text" name="manual_account_number" value="{{ $gateways['manual']['account_number'] }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="1234567890">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Nama Pemilik Rekening</label>
                    <input type="text" name="manual_account_name" value="{{ $gateways['manual']['account_name'] }}"
                        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="PT. ISP Indonesia">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-cyan-500/25">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengaturan
                </span>
            </button>
        </div>
    </form>
</div>
@endsection
