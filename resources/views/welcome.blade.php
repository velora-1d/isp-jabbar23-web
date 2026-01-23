<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="JABBAR23 ISP - Layanan Internet Cepat & Stabil untuk Rumah dan Bisnis Anda">

    <title>{{ config('app.name', 'JABBAR23 ISP') }} - Internet Cepat & Stabil</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .gradient-text {
            background: linear-gradient(135deg, #60A5FA 0%, #22D3EE 50%, #10B981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-gradient {
            background: radial-gradient(ellipse at top, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-pulse-slow {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-900 text-white">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-gray-900/80 backdrop-blur-xl border-b border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center group">
                    <div class="relative">
                        <div
                            class="absolute -inset-1.5 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 rounded-2xl blur-md opacity-50 group-hover:opacity-80 transition duration-500">
                        </div>
                        <div
                            class="relative p-2.5 rounded-xl bg-gradient-to-br from-cyan-500 via-blue-600 to-teal-600 shadow-lg shadow-blue-500/30">
                            <!-- WiFi Icon -->
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21l-1.5-1.5c-2.8-2.8-2.8-7.2 0-10l1.5 1.5c-2 2-2 5.2 0 7.2L12 21zm-3-3l-1.5-1.5c-1.7-1.7-1.7-4.3 0-6L9 12c-1 1-1 2.5 0 3.5L9 18zm6 0l1.5-1.5c1-1 1-2.5 0-3.5L15 12l1.5-1.5c1.7 1.7 1.7 4.3 0 6L15 18zm3-3l1.5-1.5c2-2 2-5.2 0-7.2L18 7.8c2 2 2 5.2 0 7.2L18 15zM12 13c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex items-baseline">
                        <span
                            class="text-lg sm:text-xl font-black tracking-tight bg-gradient-to-r from-cyan-400 via-blue-400 to-teal-400 bg-clip-text text-transparent">
                            JABBAR23
                        </span>
                        <span class="ml-1.5 text-[10px] sm:text-xs font-bold text-gray-400 tracking-[0.2em] uppercase">
                            ISP
                        </span>
                    </div>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-400 hover:text-white transition-colors">Fitur</a>
                    <a href="#packages" class="text-gray-400 hover:text-white transition-colors">Paket</a>
                    <a href="#coverage" class="text-gray-400 hover:text-white transition-colors">Jangkauan</a>
                    <a href="#contact" class="text-gray-400 hover:text-white transition-colors">Kontak</a>
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl hover:from-blue-700 hover:to-cyan-600 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-5 py-2.5 text-sm font-semibold text-white border border-gray-700 hover:bg-gray-800 rounded-xl transition-all duration-200">
                                Member Login
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center hero-gradient overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse-slow">
            </div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl animate-pulse-slow"
                style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl animate-pulse-slow"
                style="animation-delay: 4s;"></div>
        </div>

        <!-- Grid Pattern -->
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMwLTkuOTQtOC4wNi0xOC0xOC0xOFMwIDguMDYgMCAxOHM4LjA2IDE4IDE4IDE4IDEwLjA2LTggMTgtMTh6IiBzdHJva2U9IiMzNzQxNTEiIHN0cm9rZS13aWR0aD0iMC41Ii8+PC9nPjwvc3ZnPg==')] opacity-20">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <div
                        class="inline-flex items-center px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full mb-8">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-sm text-blue-400 font-medium">Internet Super Cepat hingga 100 Mbps</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        Internet <span class="gradient-text">Cepat & Stabil</span> untuk Rumah dan Bisnis
                    </h1>

                    <p class="text-lg text-gray-400 mb-8 max-w-xl mx-auto lg:mx-0">
                        Nikmati koneksi internet fiber optik berkualitas tinggi dengan harga terjangkau.
                        Dukungan teknis 24/7 dan tanpa FUP!
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#packages"
                            class="px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl hover:from-blue-700 hover:to-cyan-600 transition-all duration-200 shadow-xl shadow-blue-500/25 inline-flex items-center justify-center group">
                            Lihat Paket
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <a href="#contact"
                            class="px-8 py-4 text-lg font-semibold text-white bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-2xl transition-all duration-200 inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            Hubungi Kami
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-12 flex flex-wrap items-center justify-center lg:justify-start gap-8">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-white">{{ $stats['customers'] }}+</p>
                            <p class="text-sm text-gray-500">Pelanggan Aktif</p>
                        </div>
                        <div class="w-px h-12 bg-gray-700"></div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-white">{{ $stats['uptime'] }}</p>
                            <p class="text-sm text-gray-500">Uptime</p>
                        </div>
                        <div class="w-px h-12 bg-gray-700"></div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-white">{{ $stats['support'] }}</p>
                            <p class="text-sm text-gray-500">Support</p>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Hero Illustration -->
                <div class="hidden lg:block relative">
                    <div class="animate-float">
                        <div class="relative">
                            <!-- Main Card -->
                            <div
                                class="bg-gray-800/80 backdrop-blur-xl rounded-3xl p-8 border border-gray-700/50 shadow-2xl">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <p class="text-sm text-gray-500">Kecepatan Saat Ini</p>
                                        <p class="text-4xl font-bold gradient-text">98.5 Mbps</p>
                                    </div>
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Speed Graph Bars -->
                                <div class="flex items-end gap-1 h-32 mb-4">
                                    @php $heights = [40, 60, 45, 80, 65, 90, 75, 95, 85, 98, 88, 92]; @endphp
                                    @foreach($heights as $h)
                                        <div class="flex-1 bg-gradient-to-t from-blue-500 to-cyan-400 rounded-t-sm transition-all duration-300 hover:from-blue-400 hover:to-cyan-300"
                                            style="height: {{ $h }}%"></div>
                                    @endforeach
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Download</span>
                                    <span class="text-emerald-400 font-semibold">● Online</span>
                                </div>
                            </div>

                            <!-- Floating Badge -->
                            <div
                                class="absolute -top-4 -right-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                No FUP
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3">
                </path>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Mengapa Memilih <span
                        class="gradient-text">JABBAR23 ISP?</span></h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Kami berkomitmen memberikan layanan internet terbaik dengan
                    teknologi fiber optik terkini</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-gray-800/50 backdrop-blur rounded-2xl p-6 border border-gray-700/50">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Super Cepat</h3>
                    <p class="text-gray-400">Kecepatan hingga 100 Mbps dengan teknologi fiber optik untuk streaming dan
                        gaming tanpa lag.</p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover bg-gray-800/50 backdrop-blur rounded-2xl p-6 border border-gray-700/50">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Stabil & Aman</h3>
                    <p class="text-gray-400">Uptime 99.9% dengan jaringan yang dimonitor 24 jam dan proteksi keamanan
                        terbaik.</p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover bg-gray-800/50 backdrop-blur rounded-2xl p-6 border border-gray-700/50">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-teal-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-teal-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Support 24/7</h3>
                    <p class="text-gray-400">Tim support siap membantu Anda kapanpun melalui WhatsApp, telepon, atau
                        langsung ke lokasi.</p>
                </div>

                <!-- Feature 4 -->
                <div class="card-hover bg-gray-800/50 backdrop-blur rounded-2xl p-6 border border-gray-700/50">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-amber-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Harga Terjangkau</h3>
                    <p class="text-gray-400">Paket internet mulai dari Rp 150.000/bulan tanpa biaya tersembunyi dan
                        tanpa FUP.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <!-- Packages Section -->
    <section id="packages" class="py-24 bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Pilih Paket <span class="gradient-text">Internet
                        Anda</span></h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Semua paket termasuk instalasi gratis, router WiFi, dan tanpa
                    FUP</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($packages as $package)
                <div class="card-hover relative bg-gray-800/50 backdrop-blur rounded-3xl p-8 border border-gray-700/50 flex flex-col">
                    @if($package->is_featured ?? false)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full text-sm font-semibold shadow-lg">
                        Paling Populer
                    </div>
                    @endif
                    
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold mb-2">{{ $package->name }}</h3>
                        <p class="text-gray-500">{{ $package->description ?? 'Internet cepat stabil' }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold gradient-text">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                        <span class="text-gray-500">/bulan</span>
                    </div>

                    <ul class="space-y-4 mb-8 flex-grow">
                        <!-- Speed -->
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-emerald-400 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            Up to {{ $package->download_speed }} Mbps
                        </li>
                        
                        <!-- Dynamic Features (if stored in json or description) -->
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-emerald-400 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            Tanpa FUP (Unlimited)
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-emerald-400 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            Support 24/7
                        </li>
                    </ul>

                    <a href="https://wa.me/6281234567890?text=Halo%20saya%20tertarik%20pasang%20paket%20{{ urlencode($package->name) }}" target="_blank"
                        class="block w-full py-3 px-6 text-center font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 rounded-xl transition-all shadow-lg shadow-blue-500/25">
                        Pilih Paket
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="py-24 bg-gray-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-transparent to-cyan-500/10"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-6">Siap Menikmati Internet <span
                    class="gradient-text">Super Cepat?</span></h2>
            <p class="text-lg text-gray-400 mb-8">Hubungi kami sekarang untuk konsultasi gratis dan survey lokasi</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-xl shadow-emerald-500/25 inline-flex items-center justify-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Chat WhatsApp
                </a>
                <a href="tel:+6281234567890"
                    class="px-8 py-4 text-lg font-semibold text-white bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-2xl transition-all duration-200 inline-flex items-center justify-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                    0812-3456-7890
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-gray-950 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Logo & Description -->
                <div class="md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="p-2.5 rounded-xl bg-gradient-to-br from-cyan-500 via-blue-600 to-teal-600">
                            <!-- WiFi Icon -->
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21l-1.5-1.5c-2.8-2.8-2.8-7.2 0-10l1.5 1.5c-2 2-2 5.2 0 7.2L12 21zm-3-3l-1.5-1.5c-1.7-1.7-1.7-4.3 0-6L9 12c-1 1-1 2.5 0 3.5L9 18zm6 0l1.5-1.5c1-1 1-2.5 0-3.5L15 12l1.5-1.5c1.7 1.7 1.7 4.3 0 6L15 18zm3-3l1.5-1.5c2-2 2-5.2 0-7.2L18 7.8c2 2 2 5.2 0 7.2L18 15zM12 13c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex items-baseline">
                            <span
                                class="text-xl font-black bg-gradient-to-r from-cyan-400 via-blue-400 to-teal-400 bg-clip-text text-transparent">
                                JABBAR23
                            </span>
                            <span class="ml-1.5 text-xs font-bold text-gray-400 tracking-[0.2em] uppercase">
                                ISP
                            </span>
                        </div>
                    </div>
                    <p class="text-gray-500 max-w-md">
                        Penyedia layanan internet fiber optik terpercaya untuk rumah dan bisnis Anda.
                        Kecepatan tinggi, harga terjangkau.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="#features" class="hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#packages" class="hover:text-white transition-colors">Paket</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Kontak</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login Admin</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            0812-3456-7890
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            info@jabbar23.com
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} JABBAR23 ISP. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>

</html>
