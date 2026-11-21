@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-teal-400 to-cyan-400 bg-clip-text text-transparent">{{ $vendor->name }}</h1>
            <p class="text-gray-400 mt-1">Kode: {{ $vendor->code }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('vendors.edit', $vendor) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600/20 hover:bg-amber-600/40 text-amber-400 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Informasi Vendor</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-500 text-sm">Nama</p>
                    <p class="text-white font-medium">{{ $vendor->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Kode</p>
                    <p class="text-white font-mono">{{ $vendor->code }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Tipe</p>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $vendor->type_color }}-500/20 text-{{ $vendor->type_color }}-400">{{ $vendor->type_label }}</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Status</p>
                    @if($vendor->status === 'active')
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-emerald-500/20 text-emerald-400">Aktif</span>
                    @else
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-500/20 text-gray-400">Inactive</span>
                    @endif
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500 text-sm">Alamat</p>
                    <p class="text-white">{{ $vendor->address ?: '-' }}</p>
                    @if($vendor->city)
                    <p class="text-gray-400">{{ $vendor->city }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Kontak</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-500 text-sm">Contact Person</p>
                    <p class="text-white">{{ $vendor->contact_person ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Telepon</p>
                    <p class="text-white">{{ $vendor->phone ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Email</p>
                    <p class="text-white">{{ $vendor->email ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Bank Info -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Informasi Bank</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-500 text-sm">Nama Bank</p>
                    <p class="text-white">{{ $vendor->bank_name ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">No. Rekening</p>
                    <p class="text-white font-mono">{{ $vendor->bank_account ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">NPWP</p>
                    <p class="text-white font-mono">{{ $vendor->npwp ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="lg:col-span-2 bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Catatan</h2>
            <p class="text-gray-300">{{ $vendor->notes ?: 'Tidak ada catatan.' }}</p>
        </div>
    </div>
</div>
@endsection
