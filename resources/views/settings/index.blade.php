@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Pengaturan Aplikasi</h1>
                <p class="text-gray-400 text-sm mt-1">Kelola identitas ISP, integrasi pembayaran, dan notifikasi.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center text-emerald-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Identity & Logo -->
                <div class="space-y-8 lg:col-span-2">
                    
                    <!-- Section: Identitas -->
                    <div class="bg-gray-800 rounded-2xl border border-gray-700/50 p-6 shadow-xl">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <span class="p-2 bg-blue-500/10 text-blue-400 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </span>
                            Identitas Perusahaan
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-400 mb-2">Nama ISP / Aplikasi</label>
                                <input type="text" name="app_name" value="{{ $settings['app_name'] ?? config('app.name') }}" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Jabbar23 Net">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-400 mb-2">Logo Aplikasi</label>
                                <div class="flex items-center space-x-6">
                                    <div class="shrink-0">
                                        @if(isset($settings['app_logo']))
                                            <img class="h-16 w-16 object-cover rounded-xl border border-gray-700" src="{{ $settings['app_logo'] }}" alt="Logo">
                                        @else
                                            <div class="h-16 w-16 rounded-xl bg-gray-700 flex items-center justify-center text-gray-500 text-xs">No Logo</div>
                                        @endif
                                    </div>
                                    <label class="block">
                                        <span class="sr-only">Choose logo</span>
                                        <input type="file" name="app_logo" class="block w-full text-sm text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-500/10 file:text-blue-400
                                          hover:file:bg-blue-500/20
                                        "/>
                                    </label>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-400 mb-2">Alamat Kantor</label>
                                <textarea name="app_address" rows="3" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Alamat lengkap kantor pusat">{{ $settings['app_address'] ?? '' }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Email Resmi</label>
                                <input type="email" name="company_email" value="{{ $settings['company_email'] ?? '' }}" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500" placeholder="admin@isp.com">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">No. HP / WhatsApp Admin</label>
                                <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '' }}" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500" placeholder="0812...">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Pembayaran -->
                     <div class="bg-gray-800 rounded-2xl border border-gray-700/50 p-6 shadow-xl">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </span>
                            Info Pembayaran (Manual Transfer)
                        </h2>
                         <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Info Rekening Bank</label>
                                <textarea name="bank_account" rows="3" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: BCA 1234567890 a.n PT ISP Maju Jaya">{{ $settings['bank_account'] ?? '' }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Info ini akan muncul di footer invoice untuk pembayaran manual.</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: API Integrations -->
                <div class="space-y-8">
                     <!-- Section: Midtrans -->
                     <div class="bg-gray-800 rounded-2xl border border-gray-700/50 p-6 shadow-xl">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <span class="p-2 bg-indigo-500/10 text-indigo-400 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </span>
                            Midtrans Payment Gateway
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Merchant ID</label>
                                <input type="text" name="midtrans_merchant_id" value="{{ $settings['midtrans_merchant_id'] ?? '' }}" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Client Key</label>
                                <input type="text" name="midtrans_client_key" value="{{ $settings['midtrans_client_key'] ?? '' }}" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Server Key</label>
                                <input type="password" name="midtrans_server_key" value="{{ $settings['midtrans_server_key'] ?? '' }}" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section: WA Gateway -->
                     <div class="bg-gray-800 rounded-2xl border border-gray-700/50 p-6 shadow-xl">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <span class="p-2 bg-green-500/10 text-green-400 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </span>
                            Whatsapp Gateway
                        </h2>
                         <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">API URL</label>
                                <input type="text" name="wa_api_url" value="{{ $settings['wa_api_url'] ?? '' }}" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500" placeholder="https://api.fonnte.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">API Key (Token)</label>
                                <input type="password" name="wa_api_key" value="{{ $settings['wa_api_key'] ?? '' }}" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>
@endsection
