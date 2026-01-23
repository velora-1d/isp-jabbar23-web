@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">Kirim Pesan</h1>
            <p class="text-gray-400 mt-1">Kirim pesan ke pelanggan</p>
        </div>
        <a href="{{ route('messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6">
        <form action="{{ route('messages.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pelanggan *</label>
                    <select name="customer_id" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} - {{ $customer->phone }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Channel *</label>
                    <select name="channel" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="whatsapp" {{ old('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="sms" {{ old('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="email" {{ old('channel') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Pesan *</label>
                <textarea name="content" rows="5" required class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Ketik pesan Anda...">{{ old('content') }}</textarea>
                @error('content')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- Quick Templates -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Template Cepat</label>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="setTemplate('Halo, terima kasih telah menghubungi kami. Ada yang bisa kami bantu?')" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg text-sm">Greeting</button>
                    <button type="button" onclick="setTemplate('Tagihan Anda bulan ini sudah tersedia. Silakan lakukan pembayaran sebelum tanggal jatuh tempo.')" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg text-sm">Reminder Tagihan</button>
                    <button type="button" onclick="setTemplate('Pembayaran Anda telah kami terima. Terima kasih!')" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg text-sm">Konfirmasi Bayar</button>
                    <button type="button" onclick="setTemplate('Teknisi kami akan segera menuju lokasi Anda. Mohon menunggu.')" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg text-sm">Info Teknisi</button>
                </div>
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('messages.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-green-500/25">Kirim Pesan</button>
            </div>
        </form>
    </div>
</div>

<script>
function setTemplate(text) {
    document.querySelector('textarea[name="content"]').value = text;
}
</script>
@endsection
