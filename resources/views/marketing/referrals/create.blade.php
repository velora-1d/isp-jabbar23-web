@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-pink-400 to-rose-400 bg-clip-text text-transparent">Buat Kode Referral</h1>
            <p class="text-gray-400 mt-1">Generate kode referral untuk pelanggan</p>
        </div>
        <a href="{{ route('referrals.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('referrals.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Referrer (Pelanggan) *</label>
                    <select name="referrer_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Reward Amount (Rp) *</label>
                    <input type="number" name="reward_amount" value="{{ old('reward_amount', 50000) }}" required min="0" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white">
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('referrals.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white font-semibold rounded-xl transition-all shadow-lg shadow-pink-500/25">Generate Kode</button>
            </div>
        </form>
    </div>
</div>
@endsection
