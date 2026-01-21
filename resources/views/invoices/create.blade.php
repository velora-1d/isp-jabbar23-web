@extends('layouts.app')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="py-6 max-w-2xl mx-auto">

            <div class="mb-6">
                <a href="{{ route('invoices.index') }}"
                    class="text-gray-400 hover:text-white flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Invoice
                </a>
                <h1 class="text-2xl font-bold text-white mt-2">Buat Invoice Manual</h1>
            </div>

            <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700/50 p-6">
                <form action="{{ route('invoices.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Customer Selection -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-300 mb-2">Pilih
                            Pelanggan</label>
                        <select name="customer_id" id="customer_id" required
                            class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_id }} - {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Period Start -->
                        <div>
                            <label for="period_start" class="block text-sm font-medium text-gray-300 mb-2">Periode
                                Mulai</label>
                            <input type="date" name="period_start" id="period_start" required
                                value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                                class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <!-- Period End -->
                        <div>
                            <label for="period_end" class="block text-sm font-medium text-gray-300 mb-2">Periode
                                Selesai</label>
                            <input type="date" name="period_end" id="period_end" required
                                value="{{ now()->endOfMonth()->format('Y-m-d') }}"
                                class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-300 mb-2">Jatuh Tempo</label>
                        <input type="date" name="due_date" id="due_date" required
                            value="{{ now()->addDays(7)->format('Y-m-d') }}"
                            class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <p class="mt-1 text-xs text-gray-500">Secara default 7 hari dari sekarang.</p>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-900/20 border border-blue-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-400">Info Tagihan</h3>
                                <div class="mt-1 text-sm text-blue-300">
                                    <p>Jumlah tagihan (Amount) akan dihitung otomatis berdasarkan Paket Langganan aktif
                                        customer tersebut.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                            Generate Invoice
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
