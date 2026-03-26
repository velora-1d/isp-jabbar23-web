@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Voucher Manager</h1>
                <p class="text-gray-400 mt-1">Generate and monitor hotspot vouchers</p>
            </div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('generate-modal').classList.remove('hidden')"
                    class="px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold rounded-lg hover:from-purple-600 hover:to-indigo-600 transition shadow-lg shadow-purple-500/25">
                    Generate Batch
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Voucher Table -->
        <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden">
            <div class="p-4 border-b border-gray-700">
                <form action="" method="GET" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search code or user..."
                        class="flex-1 bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                    <select name="status" class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">Filter</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Voucher Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Profile</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Router</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($vouchers as $voucher)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-white tracking-widest font-mono">{{ $voucher->code }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $voucher->username }} / {{ $voucher->password }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $voucher->profile->display_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $voucher->router->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-white">
                                    Rp {{ number_format($voucher->profile->price) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'available' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'sold' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                            'used' => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                                            'expired' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full border {{ $statusClasses[$voucher->status] }}">
                                        {{ ucfirst($voucher->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $voucher->created_at->format('d M H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('hotspot.vouchers.print', ['ids' => [$voucher->id]]) }}" target="_blank"
                                        class="text-indigo-400 hover:text-indigo-300 bg-indigo-500/10 p-2 rounded-lg transition">
                                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 italic">No vouchers generated yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-700">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>

    <!-- Generate Batch Modal -->
    <div id="generate-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"
                onclick="document.getElementById('generate-modal').classList.add('hidden')"></div>
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700 shadow-xl rounded-2xl">
                <h3 class="text-lg font-bold text-white mb-4">Generate Voucher Batch</h3>
                <form action="{{ route('hotspot.vouchers.generate') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Select Router</label>
                            <select name="router_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                                @foreach ($routers as $router)
                                    <option value="{{ $router->id }}">{{ $router->name }} ({{ $router->ip_address }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Voucher Profile</label>
                            <select name="hotspot_profile_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                                @foreach ($profiles as $profile)
                                    <option value="{{ $profile->id }}">{{ $profile->display_name }} - Rp {{ number_format($profile->price) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Quantity (Max 500)</label>
                            <input type="number" name="count" required value="50" min="1" max="500"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('generate-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-600 transition">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold rounded-lg transition shadow-lg shadow-purple-500/25">Generate Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
