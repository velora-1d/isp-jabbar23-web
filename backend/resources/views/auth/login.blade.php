<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'JABBAR23 ISP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-card {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.6;
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Typing Animation for JABBAR23 */
        @keyframes typing-jabbar {
            0% {
                width: 0;
            }

            50%,
            100% {
                width: 100%;
            }
        }

        /* Typing Animation for ISP text */
        @keyframes typing-isp {

            0%,
            40% {
                width: 0;
            }

            90%,
            100% {
                width: 100%;
            }
        }

        @keyframes blink-cursor {

            0%,
            100% {
                border-color: transparent;
            }

            50% {
                border-color: #22D3EE;
            }
        }

        .typing-jabbar {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid #22D3EE;
            animation: typing-jabbar 2.5s steps(8) forwards, blink-cursor 0.7s step-end infinite;
        }

        .typing-isp {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid #8B5CF6;
            width: 0;
            animation: typing-isp 4s steps(25) forwards, blink-cursor 0.7s step-end infinite;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-950 text-white min-h-screen">

    <!-- Background Decorations -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <!-- Dark Gradient Orbs -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl animate-pulse-slow"
            style="animation-delay: 2s;"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-3xl">
        </div>

        <!-- Subtle Grid Pattern -->
        <div
            class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.01)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.01)_1px,transparent_1px)] bg-[size:60px_60px]">
        </div>
    </div>

    <div class="relative min-h-screen flex">

        <!-- Left Side - Branding (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 bg-gray-900/50">
            <div class="max-w-lg">
                <!-- Logo (No Float Animation) -->
                <div class="flex items-center mb-8">
                    <div class="relative">
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 rounded-3xl blur-lg opacity-40">
                        </div>
                        <div
                            class="relative p-4 rounded-2xl bg-gradient-to-br from-cyan-500 via-blue-600 to-teal-600 shadow-2xl shadow-cyan-500/20">
                            <!-- WiFi Icon -->
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21l-1.5-1.5c-2.8-2.8-2.8-7.2 0-10l1.5 1.5c-2 2-2 5.2 0 7.2L12 21zm-3-3l-1.5-1.5c-1.7-1.7-1.7-4.3 0-6L9 12c-1 1-1 2.5 0 3.5L9 18zm6 0l1.5-1.5c1-1 1-2.5 0-3.5L15 12l1.5-1.5c1.7 1.7 1.7 4.3 0 6L15 18zm3-3l1.5-1.5c2-2 2-5.2 0-7.2L18 7.8c2 2 2 5.2 0 7.2L18 15zM12 13c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1
                            class="text-4xl font-black bg-gradient-to-r from-cyan-400 via-blue-400 to-teal-400 bg-clip-text text-transparent">
                            <span class="typing-jabbar">JABBAR23</span>
                        </h1>
                        <p class="text-sm font-bold text-gray-500 tracking-[0.2em] uppercase">
                            <span class="typing-isp">Internet Service Provider</span>
                        </p>
                    </div>
                </div>

                <!-- Welcome Text -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-white mb-2">
                        Selamat Datang di
                    </h2>
                    <p class="text-xl text-cyan-400 font-semibold">Panel Admin ISP</p>
                </div>

                <!-- Features Preview -->
                <div class="space-y-4">
                    <div class="flex items-center text-gray-400 hover:text-gray-300 transition-colors">
                        <div
                            class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <span>Manajemen Pelanggan</span>
                    </div>
                    <div class="flex items-center text-gray-400 hover:text-gray-300 transition-colors">
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <span>Tagihan & Pembayaran</span>
                    </div>
                    <div class="flex items-center text-gray-400 hover:text-gray-300 transition-colors">
                        <div
                            class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <span>Laporan & Analytics</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">

                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center mb-8">
                    <div class="relative">
                        <div
                            class="absolute -inset-1.5 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 rounded-2xl blur-md opacity-50">
                        </div>
                        <div class="relative p-3 rounded-xl bg-gradient-to-br from-cyan-500 via-blue-600 to-teal-600">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21l-1.5-1.5c-2.8-2.8-2.8-7.2 0-10l1.5 1.5c-2 2-2 5.2 0 7.2L12 21zm-3-3l-1.5-1.5c-1.7-1.7-1.7-4.3 0-6L9 12c-1 1-1 2.5 0 3.5L9 18zm6 0l1.5-1.5c1-1 1-2.5 0-3.5L15 12l1.5-1.5c1.7 1.7 1.7 4.3 0 6L15 18zm3-3l1.5-1.5c2-2 2-5.2 0-7.2L18 7.8c2 2 2 5.2 0 7.2L18 15zM12 13c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <span
                            class="text-2xl font-black bg-gradient-to-r from-cyan-400 via-blue-400 to-teal-400 bg-clip-text text-transparent">
                            JABBAR23
                        </span>
                        <span class="ml-1 text-xs font-bold text-gray-500 tracking-[0.2em] uppercase">
                            ISP
                        </span>
                    </div>
                </div>

                <!-- Login Card -->
                <div class="relative">
                    <!-- Card Glow -->
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-cyan-500/10 via-blue-500/10 to-teal-500/10 rounded-3xl blur-xl">
                    </div>

                    <!-- Card -->
                    <div class="relative glass-card rounded-3xl border border-gray-800/50 p-8 sm:p-10">

                        <!-- Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Welcome Back</h2>
                            <p class="text-gray-500">Masuk ke dashboard admin Anda</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-400 mb-2">
                                    Email Address
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-600 group-focus-within:text-cyan-400 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="w-full pl-12 pr-4 py-3.5 bg-gray-900/80 border border-gray-700/50 rounded-xl text-white placeholder-gray-600 focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20 input-glow transition-all duration-300"
                                        placeholder="admin@jabbar23.com" required autofocus autocomplete="username">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div x-data="{ show: false }">
                                <label for="password" class="block text-sm font-semibold text-gray-400 mb-2">
                                    Password
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-600 group-focus-within:text-cyan-400 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input :type="show ? 'text' : 'password'" id="password" name="password"
                                        class="w-full pl-12 pr-12 py-3.5 bg-gray-900/80 border border-gray-700/50 rounded-xl text-white placeholder-gray-600 focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20 input-glow transition-all duration-300"
                                        placeholder="••••••••" required autocomplete="current-password">
                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-600 hover:text-cyan-400 transition-colors">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                                    <input id="remember_me" type="checkbox"
                                        class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-gray-900"
                                        name="remember">
                                    <span
                                        class="ms-2 text-sm text-gray-500 group-hover:text-gray-400 transition-colors">
                                        Ingat saya
                                    </span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors"
                                        href="{{ route('password.request') }}">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="relative w-full py-4 text-sm font-bold text-white rounded-xl overflow-hidden group transition-all duration-300 shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/30">
                                <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 via-blue-600 to-teal-600">
                                </div>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <span class="relative flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Masuk ke Dashboard
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer -->
                <p class="mt-8 text-center text-sm text-gray-600">
                    &copy; {{ date('Y') }} JABBAR23 ISP. All rights reserved.
                </p>
            </div>
        </div>
    </div>

</body>

</html>
