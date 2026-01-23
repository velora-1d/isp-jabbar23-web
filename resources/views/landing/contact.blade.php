@extends('layouts.landing')

@section('title', 'Hubungi Kami')

@section('content')
<div class="pt-32 pb-20 bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl font-extrabold text-white mb-6">Hubungi <span class="gradient-text">Tim Support</span></h1>
            <p class="text-gray-400 text-lg">
                Punya pertanyaan atau kendala? Kami siap membantu Anda 24/7. Hubungi kami melalui kanal di bawah ini.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Info & Map -->
            <div class="space-y-8">
                <!-- Info Cards -->
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="bg-gray-800/50 p-6 rounded-2xl border border-gray-700">
                        <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Telepon</h3>
                        <p class="text-gray-400">+62 812-3456-7890</p>
                        <p class="text-sm text-gray-500 mt-1">Senin - Minggu, 24 Jam</p>
                    </div>
                    <div class="bg-gray-800/50 p-6 rounded-2xl border border-gray-700">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Email</h3>
                        <p class="text-gray-400">support@jabbar23.com</p>
                        <p class="text-sm text-gray-500 mt-1">Respon maks. 24 jam</p>
                    </div>
                </div>

                <!-- Map -->
                <div class="bg-gray-800 rounded-3xl overflow-hidden border border-gray-700 h-80 relative">
                     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d107.57311687144542!3d-6.903444341687889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1647484964648!5m2!1sen!2sid" width="100%" height="100%" style="border:0; filter: grayscale(1) invert(1);" allowfullscreen="" loading="lazy"></iframe>
                     <div class="absolute bottom-4 left-4 bg-gray-900/90 backdrop-blur px-4 py-2 rounded-lg border border-gray-700 text-sm font-medium text-white shadow-xl">
                        📍 Kantor Pusat Jabbar23
                     </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700 rounded-3xl p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Kirim Pesan</h3>
                <form action="#" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nama Lengkap</label>
                            <input type="text" class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="Nama Anda">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nomor WhatsApp</label>
                            <input type="text" class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="0812...">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Subjek</label>
                        <select class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                            <option>Tanya Paket Baru</option>
                            <option>Lapor Gangguan Teknis</option>
                            <option>Konfirmasi Pembayaran</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Pesan</label>
                        <textarea rows="4" class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="Tulis pesan Anda di sini..."></textarea>
                    </div>
                    <button type="button" onclick="alert('Fitur ini akan segera aktif! Silakan hubungi WA kami.')" class="w-full py-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                        Kirim Pesan
                    </button>
                    <p class="text-center text-sm text-gray-500">
                        Atau chat langsung via 
                        <a href="https://wa.me/6281234567890" class="text-emerald-400 hover:underline">WhatsApp</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
