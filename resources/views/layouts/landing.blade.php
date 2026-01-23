<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ISP Jabbar 23') - Internet Cepat & Stabil</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:400,500,700,800" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, #111827, #030712);
        }
    </style>
</head>

<body class="antialiased bg-gray-950 text-white">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-gray-900/80 backdrop-blur-md border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('landing.home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-xl flex items-center justify-center transform group-hover:rotate-6 transition-transform shadow-lg shadow-blue-900/20">
                            <span class="text-white font-bold text-xl">J</span>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-white group-hover:text-blue-400 transition-colors">Jabbar<span
                                class="text-blue-500">23</span></span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('landing.home') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Home</a>
                    <a href="{{ route('landing.home') }}#packages" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Paket Internet</a>
                    <a href="{{ route('landing.coverage') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors {{ request()->routeIs('landing.coverage') ? 'text-blue-400 font-bold' : '' }}">Cek Area</a>
                    <a href="{{ route('landing.contact') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors {{ request()->routeIs('landing.contact') ? 'text-blue-400 font-bold' : '' }}">Hubungi Kami</a>
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        <div class="hidden md:block">
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-gray-950 border-t border-gray-900 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <a href="#" class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">J</span>
                        </div>
                        <span class="font-bold text-lg text-white">Jabbar<span class="text-blue-500">23</span></span>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Penyedia layanan internet fiber optik berkualitas untuk kebutuhan digital Anda. Cepat, Stabil, dan Terjangkau.
                    </p>
                </div>
                <div>
                    <h3 class="font-bold text-white mb-6">Layanan</h3>
                    <ul class="space-y-4 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Internet Rumah</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Internet Bisnis</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Dedicated Server</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">VPN Corporate</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-white mb-6">Dukungan</h3>
                    <ul class="space-y-4 text-sm text-gray-500">
                        <li><a href="{{ route('landing.coverage') }}" class="hover:text-blue-400 transition-colors">Cek Area</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Panduan Pembayaran</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Lapor Gangguan</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-white mb-6">Hubungi Kami</h3>
                    <ul class="space-y-4 text-sm text-gray-500">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Jl. Telekomunikasi No. 1, Bandung
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            support@jabbar23.com
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            +62 812-3456-7890
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-gray-900 border-dashed text-center">
                <p class="text-sm text-gray-600">&copy; 2024 ISP Jabbar 23. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
