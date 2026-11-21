@extends('layouts.landing')

@section('title', 'Cek Jangkauan Area')

@section('content')
<div class="pt-32 pb-20 bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl font-extrabold text-white mb-6">Cek Ketersediaan <span class="gradient-text">Jaringan</span></h1>
            <p class="text-gray-400 text-lg mb-10">
                Masukkan nama kecamatan, kelurahan, atau nama jalan Anda untuk mengecek ketersediaan jaringan Fiber Optik kami.
            </p>

            <!-- Search Form -->
            <form action="{{ route('landing.coverage') }}" method="GET" class="relative max-w-2xl mx-auto mb-16">
                <input type="text" name="q" value="{{ $query }}" 
                    placeholder="Contoh: Cikadut, Antapani, Jl. Merdeka..." 
                    class="w-full px-6 py-4 bg-gray-800 border border-gray-700 rounded-full text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all font-medium text-lg placeholder-gray-500 shadow-xl shadow-blue-900/10">
                <button type="submit" class="absolute right-2 top-2 px-6 py-2 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white rounded-full font-semibold transition-all shadow-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cek Area
                </button>
            </form>

            @if($query)
                <div class="bg-gray-800/50 backdrop-blur border border-gray-700 rounded-3xl p-8 mb-10">
                    <h3 class="text-xl font-bold text-white mb-6 text-left">Hasil Pencarian untuk "{{ $query }}"</h3>
                    
                    @if(count($results) > 0)
                        <div class="space-y-4">
                            @foreach($results as $odp)
                            <div class="flex items-center justify-between p-4 bg-gray-800 rounded-xl border border-gray-700 hover:border-emerald-500/50 transition-colors group">
                                <div class="flex items-center text-left">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center mr-4 group-hover:bg-emerald-500/20 transition-colors">
                                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white">{{ $odp->name }}</h4>
                                        <p class="text-sm text-gray-400">{{ $odp->address ?? $odp->description ?? 'Lokasi Tercover' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if(($odp->port_capacity - $odp->port_used) > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                            Penuh
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 p-4 bg-blue-900/20 border border-blue-500/30 rounded-2xl flex items-center justify-between">
                            <div class="text-left">
                                <h4 class="font-bold text-blue-400">Area Anda Tercover!</h4>
                                <p class="text-sm text-gray-400">Segera daftar sebelum port penuh.</p>
                            </div>
                            <a href="https://wa.me/6281234567890?text=Halo%20saya%20mau%20pasang%20internet%20di%20area%20{{ urlencode($query) }}" target="_blank" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors">
                                Daftar Sekarang
                            </a>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-gray-400 font-medium">Maaf, area tersebut belum ditemukan.</p>
                            <p class="text-gray-500 text-sm mt-2">Coba gunakan nama kecamatan atau kelurahan lain.</p>
                            
                            <div class="mt-8">
                                <a href="https://wa.me/6281234567890?text=Halo%20apakah%20area%20{{ urlencode($query) }}%20sudah%20tercover?" target="_blank" class="text-blue-400 hover:text-blue-300 font-medium text-sm">
                                    Request Perluasan Jaringan &rarr;
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Map Placeholder -->
            <div class="rounded-3xl overflow-hidden border border-gray-700 relative h-96 bg-gray-800">
                <div class="absolute inset-0 flex items-center justify-center bg-gray-800/80 backdrop-blur-sm z-10">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012 2v2.634M9 21l3-3m0 0l3 3m-3-3V15.75"></path></svg>
                        <p class="text-gray-400 font-medium">Peta Jangkauan Interaktif</p>
                        <p class="text-gray-600 text-sm mt-1">(Akan segera hadir)</p>
                    </div>
                </div>
                <!-- You can embed Google Maps here later -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d107.57311687144542!3d-6.903444341687889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1647484964648!5m2!1sen!2sid" width="100%" height="100%" style="border:0; filter: grayscale(1) invert(1);" allowfullscreen="" loading="lazy"></iframe>
            </div>

        </div>
    </div>
</div>
@endsection
