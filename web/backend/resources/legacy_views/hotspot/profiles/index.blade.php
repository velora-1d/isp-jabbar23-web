@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Hotspot Profiles</h1>
                <p class="text-gray-400 mt-1">Manage voucher types, pricing, and validity</p>
            </div>
            <button onclick="document.getElementById('create-profile-modal').classList.remove('hidden')"
                class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold rounded-lg hover:from-cyan-600 hover:to-blue-600 transition shadow-lg shadow-cyan-500/25">
                + New Profile
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($profiles as $profile)
                <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-2xl p-6 hover:border-cyan-500/50 transition group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-cyan-500/10 rounded-xl">
                            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white">Rp {{ number_format($profile->price) }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-1 group-hover:text-cyan-400 transition">{{ $profile->display_name }}</h3>
                    <p class="text-sm text-gray-400 mb-4">MikroTik Profile: <span class="text-gray-300 font-mono">{{ $profile->name }}</span></p>
                    
                    <div class="space-y-2 border-t border-gray-700 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Validity:</span>
                            <span class="text-gray-300 font-medium">{{ $profile->validity_hours }} Hours</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Data Limit:</span>
                            <span class="text-gray-300 font-medium">{{ $profile->data_limit_mb ? $profile->data_limit_mb . ' MB' : 'Unlimited' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Vouchers:</span>
                            <span class="text-gray-300 font-medium">{{ $profile->vouchers_count }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-gray-800/30 border border-dashed border-gray-700 rounded-2xl">
                    <p class="text-gray-500">No hotspot profiles found. Create one to start generating vouchers.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Profile Modal -->
    <div id="create-profile-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"
                onclick="document.getElementById('create-profile-modal').classList.add('hidden')"></div>
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700 shadow-xl rounded-2xl">
                <h3 class="text-lg font-bold text-white mb-4">New Hotspot Profile</h3>
                <form action="{{ route('hotspot.profiles.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">MikroTik Profile Name</label>
                            <input type="text" name="name" required placeholder="e.g. 2jam_2rb"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <p class="text-[10px] text-gray-500 mt-1">Must match the Profile Name in MikroTik Winbox/WebFig</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Display Name (Public)</label>
                            <input type="text" name="display_name" required placeholder="e.g. Voucher 2 Jam"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Price (Rp)</label>
                                <input type="number" name="price" required placeholder="2000"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Validity (Hours)</label>
                                <input type="number" name="validity_hours" required placeholder="2"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Data Limit (MB) - Optional</label>
                            <input type="number" name="data_limit_mb" placeholder="Unlimited if empty"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('create-profile-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-600 transition">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-lg transition">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
