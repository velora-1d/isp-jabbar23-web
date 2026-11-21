@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Inventaris & Stok</h1>
                <p class="text-gray-400 mt-1">Kelola stok barang, sparepart, dan aset</p>
            </div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('adjust-modal').classList.remove('hidden')"
                    class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition border border-gray-600">
                    Atur Stok
                </button>
                <button onclick="document.getElementById('create-modal').classList.remove('hidden')"
                    class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-600 transition shadow-lg shadow-amber-500/25">
                    + Item Baru
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4">
                <div class="text-xs text-gray-400 mb-1">Total Item</div>
                <div class="text-2xl font-bold text-white">{{ $stats['total_items'] }} SKU</div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4">
                <div class="text-xs text-emerald-400 mb-1">Total Nilai Asset</div>
                <div class="text-2xl font-bold text-white">Rp {{ number_format($stats['total_value'] / 1000000, 1) }} Jt
                </div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4">
                <div class="text-xs text-red-400 mb-1">Stok Menipis</div>
                <div class="text-2xl font-bold text-white">{{ $stats['low_stock'] }} Item</div>
            </div>
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4">
                <div class="text-xs text-blue-400 mb-1">Kategori</div>
                <div class="text-2xl font-bold text-white">{{ $stats['categories'] }} Tipe</div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl overflow-hidden">
            <div class="p-4 border-b border-gray-700 flex gap-4">
                <form action="" method="GET" class="flex-1 flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama barang atau SKU..."
                        class="flex-1 bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                    <select name="category_id" class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filter</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Total Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Harga</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($items as $item)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-400 font-mono">{{ $item->sku ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-medium bg-gray-700 text-gray-300 rounded">{{ $item->category->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-bold {{ $item->total_stock <= $item->min_stock_alert ? 'text-red-400' : 'text-emerald-400' }}">
                                        {{ $item->total_stock }}
                                    </div>
                                    @if($item->total_stock <= $item->min_stock_alert)
                                        <div class="text-[10px] text-red-500">Stok Menipis!</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $item->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    Rp {{ number_format($item->selling_price) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="openAdjustModal({{ $item->id }}, '{{ $item->name }}')"
                                            class="text-blue-400 hover:text-blue-300 bg-blue-500/10 hover:bg-blue-500/20 px-2 py-1 rounded text-xs transition">
                                            Adjust
                                        </button>
                                        <button onclick="openEditModal({{ $item }})"
                                            class="text-yellow-400 hover:text-yellow-300 bg-yellow-500/10 hover:bg-yellow-500/20 px-2 py-1 rounded text-xs transition">
                                            Edit
                                        </button>
                                        <form action="{{ route('inventory.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus item ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-400 hover:text-red-300 bg-red-500/10 hover:bg-red-500/20 px-2 py-1 rounded text-xs transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    Belum ada data barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Create Item -->
    <div id="create-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"
                onclick="document.getElementById('create-modal').classList.add('hidden')"></div>
            <div
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700 shadow-xl rounded-2xl">
                <h3 class="text-lg font-medium leading-6 text-white mb-4">Tambah Item Baru</h3>
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Nama Barang</label>
                            <input type="text" name="name" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Kategori</label>
                                <select name="category_id" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Satuan</label>
                                <input type="text" name="unit" placeholder="Pcs/Roll/M" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">SKU / Kode Barang (Optional)</label>
                            <input type="text" name="sku"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Harga Beli</label>
                                <input type="number" name="purchase_price" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Harga Jual</label>
                                <input type="number" name="selling_price" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Min. Stock Alert</label>
                            <input type="number" name="min_stock_alert" value="5" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('create-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Item -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"
                onclick="document.getElementById('edit-modal').classList.add('hidden')"></div>
            <div
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700 shadow-xl rounded-2xl">
                <h3 class="text-lg font-medium leading-6 text-white mb-4">Edit Item</h3>
                <form id="edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Nama Barang</label>
                            <input type="text" name="name" id="edit_name" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Kategori</label>
                                <select name="category_id" id="edit_category_id" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Satuan</label>
                                <input type="text" name="unit" id="edit_unit" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">SKU / Kode Barang (Optional)</label>
                            <input type="text" name="sku" id="edit_sku"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Harga Beli</label>
                                <input type="number" name="purchase_price" id="edit_purchase_price" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Harga Jual</label>
                                <input type="number" name="selling_price" id="edit_selling_price" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Min. Stock Alert</label>
                            <input type="number" name="min_stock_alert" id="edit_min_stock_alert" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Adjust Stock -->
    <div id="adjust-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"
                onclick="document.getElementById('adjust-modal').classList.add('hidden')"></div>
            <div
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700 shadow-xl rounded-2xl">
                <h3 class="text-lg font-medium leading-6 text-white mb-4">Penyesuaian Stok</h3>
                <form action="{{ route('inventory.adjust') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Pilih Barang</label>
                            <select name="inventory_item_id" id="adjust_item_id" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->total_stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Tipe Mutasi</label>
                                <select name="type" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                                    <option value="in">Masuk (In)</option>
                                    <option value="out">Keluar (Out)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Lokasi Gudang</label>
                                <select name="location_id" required
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Jumlah (Qty)</label>
                            <input type="number" name="quantity" step="0.01" min="0.01" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Catatan / Referensi</label>
                            <textarea name="notes" rows="2" placeholder="Ex: Pembelian PO-001 or Pemakaian WO-123"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('adjust-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAdjustModal(itemId, itemName) {
            document.getElementById('adjust_item_id').value = itemId;
            document.getElementById('adjust-modal').classList.remove('hidden');
        }

        function openEditModal(item) {
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_category_id').value = item.category_id;
            document.getElementById('edit_unit').value = item.unit;
            document.getElementById('edit_sku').value = item.sku;
            document.getElementById('edit_purchase_price').value = item.purchase_price;
            document.getElementById('edit_selling_price').value = item.selling_price;
            document.getElementById('edit_min_stock_alert').value = item.min_stock_alert;

            // Update form action
            document.getElementById('edit-form').action = "{{ url('inventory') }}/" + item.id;

            document.getElementById('edit-modal').classList.remove('hidden');
        }
    </script>
@endsection
