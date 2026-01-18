@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Buat Slip Gaji</h1>
            <p class="text-gray-400 mt-1">Buat data penggajian baru</p>
        </div>
        <a href="{{ route('payroll.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('payroll.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Karyawan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-2">Informasi Dasar</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Karyawan *</label>
                        <select name="user_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Periode *</label>
                        <input type="month" name="period" value="{{ old('period', date('Y-m')) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        @error('period')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Komponen Gaji -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-2">Pendapatan</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Gaji Pokok *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400">Rp</span>
                            <input type="number" name="basic_salary" value="{{ old('basic_salary', 0) }}" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tunjangan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400">Rp</span>
                            <input type="number" name="allowances" value="{{ old('allowances', 0) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Lembur</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-400">Rp</span>
                                <input type="number" name="overtime" value="{{ old('overtime', 0) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Bonus</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-400">Rp</span>
                                <input type="number" name="bonus" value="{{ old('bonus', 0) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Potongan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-2">Potongan</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Potongan Lain</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-400">Rp</span>
                                <input type="number" name="deductions" value="{{ old('deductions', 0) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pajak (PPh 21)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-400">Rp</span>
                                <input type="number" name="tax" value="{{ old('tax', 0) }}" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white border-b border-gray-700 pb-2">Lainnya</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                        <textarea name="notes" rows="3" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-700">
                <a href="{{ route('payroll.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-emerald-500/25">Buat Payroll</button>
            </div>
        </form>
    </div>
</div>
@endsection
