@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Pengeluaran & Biaya Operasional</h1>
                <p class="text-gray-400 mt-1">Catat dan monitor semua biaya operasional ISP</p>
            </div>
            <button onclick="document.getElementById('create-expense-modal').classList.remove('hidden')"
                class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-rose-700 transition shadow-lg shadow-red-500/25">
                + Catat Pengeluaran
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter & Table -->
        <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden">
            <div class="p-4 border-b border-gray-700">
                <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <select name="category" class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                        class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                        class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                    <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition text-sm">Filter</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Petugas</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($expenses as $expense)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $expense->date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-900 border border-gray-700 text-gray-300">
                                        {{ $categories[$expense->category] ?? $expense->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">
                                    {{ $expense->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-400">
                                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $expense->creator->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        @if($expense->receipt_path)
                                            <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank"
                                                class="text-cyan-400 hover:text-cyan-300 bg-cyan-500/10 p-2 rounded-lg transition" title="Lihat Struk">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        @endif
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-300 bg-rose-500/10 p-2 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">Belum ada catatan pengeluaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-700">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>

    <!-- Create Expense Modal -->
    <div id="create-expense-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"
                onclick="document.getElementById('create-expense-modal').classList.add('hidden')"></div>
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700 shadow-xl rounded-2xl">
                <h3 class="text-lg font-bold text-white mb-4">Catat Pengeluaran Baru</h3>
                <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Kategori</label>
                            <select name="category" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                                @foreach ($categories as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Jumlah (Rp)</label>
                            <input type="number" name="amount" required placeholder="0"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi / Keperluan</label>
                            <textarea name="description" required rows="3" placeholder="Contoh: Pembelian kabel FO 1 roll"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Upload Struk (Opsional)</label>
                            <input type="file" name="receipt" 
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                            <p class="text-[10px] text-gray-500 mt-1">Format: JPG, PNG. Max 2MB</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('create-expense-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-600 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition shadow-lg shadow-red-500/25">Simpan Pengeluaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
