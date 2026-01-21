<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')

  <!-- Header Animation Styles -->
  <style>
    /* Hide Scrollbar but Keep Scroll Functionality */
    html,
    body {
      scrollbar-width: none;
      /* Firefox */
      -ms-overflow-style: none;
      /* IE/Edge */
    }

    html::-webkit-scrollbar,
    body::-webkit-scrollbar,
    *::-webkit-scrollbar {
      display: none;
      /* Chrome, Safari, Opera */
    }

    /* Floating Orb Animation */
    @keyframes float-slow {

      0%,
      100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.1;
      }

      50% {
        transform: translate(-50%, -50%) scale(1.2);
        opacity: 0.2;
      }
    }

    @keyframes float-slow-reverse {

      0%,
      100% {
        transform: translate(50%, -50%) scale(1.2);
        opacity: 0.2;
      }

      50% {
        transform: translate(50%, -50%) scale(1);
        opacity: 0.1;
      }
    }

    .animate-float-slow {
      animation: float-slow 6s ease-in-out infinite;
    }

    .animate-float-slow-reverse {
      animation: float-slow-reverse 6s ease-in-out infinite;
    }

    /* Wave Animation */
    @keyframes wave-flow-1 {
      0% {
        stroke-dashoffset: 0;
        opacity: 0.3;
      }

      50% {
        opacity: 0.6;
      }

      100% {
        stroke-dashoffset: -50;
        opacity: 0.3;
      }
    }

    @keyframes wave-flow-2 {
      0% {
        stroke-dashoffset: 0;
        opacity: 0.2;
      }

      50% {
        opacity: 0.4;
      }

      100% {
        stroke-dashoffset: -30;
        opacity: 0.2;
      }
    }

    .animate-wave-1 {
      stroke-dasharray: 10 5;
      animation: wave-flow-1 3s linear infinite;
    }

    .animate-wave-2 {
      stroke-dasharray: 8 4;
      animation: wave-flow-2 4s linear infinite;
    }

    /* Particle Animation */
    @keyframes particle-float-1 {

      0%,
      100% {
        transform: translateY(0) scale(1);
        opacity: 0.6;
      }

      50% {
        transform: translateY(-8px) scale(1.2);
        opacity: 1;
      }
    }

    @keyframes particle-float-2 {

      0%,
      100% {
        transform: translateY(0) scale(1);
        opacity: 0.4;
      }

      50% {
        transform: translateY(-6px) scale(1.1);
        opacity: 0.8;
      }
    }

    @keyframes particle-float-3 {

      0%,
      100% {
        transform: translateY(0) scale(1);
        opacity: 0.5;
      }

      50% {
        transform: translateY(-10px) scale(1.3);
        opacity: 0.9;
      }
    }

    .animate-particle-1 {
      animation: particle-float-1 2.5s ease-in-out infinite;
    }

    .animate-particle-2 {
      animation: particle-float-2 3s ease-in-out infinite 0.5s;
    }

    .animate-particle-3 {
      animation: particle-float-3 2s ease-in-out infinite 1s;
    }
  </style>

<body class="font-sans antialiased bg-gray-900 text-white">

  <!-- Toast Notifications -->
  <x-toast />

  <!-- Page Loading Bar -->
  <x-page-loader />

  <!-- Navbar Fixed Top -->
  <nav class="fixed top-0 z-50 w-full bg-gray-900/95 backdrop-blur-xl border-b border-gray-800/50 overflow-hidden">
    <!-- Animated Background Ornaments -->
    <div class="absolute inset-0 pointer-events-none">
      <!-- Left Side Ornaments -->
      <div class="absolute left-0 top-0 h-full w-1/3 overflow-hidden">
        <!-- Floating Orb 1 -->
        <div
          class="absolute -left-4 top-1/2 -translate-y-1/2 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl animate-float-slow">
        </div>
        <!-- Signal Wave Lines -->
        <svg class="absolute left-8 top-1/2 -translate-y-1/2 w-48 h-12 opacity-30" viewBox="0 0 200 50">
          <path class="animate-wave-1" d="M0,25 Q25,10 50,25 T100,25 T150,25 T200,25" fill="none"
            stroke="url(#gradient-left)" stroke-width="1.5" />
          <path class="animate-wave-2" d="M0,25 Q25,40 50,25 T100,25 T150,25 T200,25" fill="none"
            stroke="url(#gradient-left)" stroke-width="1" />
          <defs>
            <linearGradient id="gradient-left" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="transparent" />
              <stop offset="50%" stop-color="#22d3ee" />
              <stop offset="100%" stop-color="transparent" />
            </linearGradient>
          </defs>
        </svg>
        <!-- Floating Particles -->
        <div class="absolute left-16 top-3 w-1.5 h-1.5 bg-cyan-400/60 rounded-full animate-particle-1"></div>
        <div class="absolute left-24 top-8 w-1 h-1 bg-blue-400/40 rounded-full animate-particle-2"></div>
        <div class="absolute left-32 top-2 w-1 h-1 bg-teal-400/50 rounded-full animate-particle-3"></div>
      </div>

      <!-- Right Side Ornaments (Mirrored) -->
      <div class="absolute right-0 top-0 h-full w-1/3 overflow-hidden">
        <!-- Floating Orb 2 -->
        <div
          class="absolute -right-4 top-1/2 -translate-y-1/2 w-32 h-32 bg-teal-500/10 rounded-full blur-2xl animate-float-slow-reverse">
        </div>
        <!-- Signal Wave Lines -->
        <svg class="absolute right-8 top-1/2 -translate-y-1/2 w-48 h-12 opacity-30 transform scale-x-[-1]"
          viewBox="0 0 200 50">
          <path class="animate-wave-1" d="M0,25 Q25,10 50,25 T100,25 T150,25 T200,25" fill="none"
            stroke="url(#gradient-right)" stroke-width="1.5" />
          <path class="animate-wave-2" d="M0,25 Q25,40 50,25 T100,25 T150,25 T200,25" fill="none"
            stroke="url(#gradient-right)" stroke-width="1" />
          <defs>
            <linearGradient id="gradient-right" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="transparent" />
              <stop offset="50%" stop-color="#2dd4bf" />
              <stop offset="100%" stop-color="transparent" />
            </linearGradient>
          </defs>
        </svg>
        <!-- Floating Particles -->
        <div class="absolute right-16 top-3 w-1.5 h-1.5 bg-teal-400/60 rounded-full animate-particle-1"></div>
        <div class="absolute right-24 top-8 w-1 h-1 bg-cyan-400/40 rounded-full animate-particle-2"></div>
        <div class="absolute right-32 top-2 w-1 h-1 bg-blue-400/50 rounded-full animate-particle-3"></div>
      </div>
    </div>

    <div class="px-3 py-2.5 lg:px-5 relative z-10">
      <div class="flex items-center justify-between">
        <!-- Left: Hamburger Menu (Mobile) -->
        <div class="flex items-center">
          <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
            type="button"
            class="inline-flex items-center p-2 text-gray-400 rounded-xl sm:hidden hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 transition-colors">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>

        <!-- Center: Logo & Branding with Wave Animations -->
        <div class="flex items-center justify-center absolute left-1/2 -translate-x-1/2">
          <!-- Left Wave Animation (near J) -->
          <div class="hidden sm:block mr-3">
            <svg class="w-24 h-10 opacity-70" viewBox="0 0 100 40">
              <path class="animate-wave-1" d="M100,20 Q85,8 70,20 T40,20 T10,20 T0,20" fill="none"
                stroke="url(#wave-grad-left)" stroke-width="2.5" />
              <path class="animate-wave-2" d="M100,20 Q85,32 70,20 T40,20 T10,20 T0,20" fill="none"
                stroke="url(#wave-grad-left)" stroke-width="1.5" />
              <circle class="animate-particle-1" cx="85" cy="12" r="2" fill="#22d3ee" opacity="0.8" />
              <circle class="animate-particle-2" cx="70" cy="28" r="1.5" fill="#3b82f6" opacity="0.6" />
              <circle class="animate-particle-3" cx="55" cy="10" r="1.5" fill="#2dd4bf" opacity="0.7" />
              <defs>
                <linearGradient id="wave-grad-left" x1="100%" y1="0%" x2="0%" y2="0%">
                  <stop offset="0%" stop-color="#22d3ee" />
                  <stop offset="100%" stop-color="transparent" />
                </linearGradient>
              </defs>
            </svg>
          </div>

          <!-- Logo & Text -->
          <a href="{{ route('dashboard') }}" class="flex items-center group">
            <!-- WiFi Logo with Glow -->
            <div class="relative">
              <div
                class="absolute -inset-1.5 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 rounded-2xl blur-md opacity-50 group-hover:opacity-80 transition duration-500 animate-pulse">
              </div>
              <div
                class="relative p-2.5 rounded-xl bg-gradient-to-br from-cyan-500 via-blue-600 to-teal-600 shadow-lg shadow-blue-500/30">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M12 21l-1.5-1.5c-2.8-2.8-2.8-7.2 0-10l1.5 1.5c-2 2-2 5.2 0 7.2L12 21zm-3-3l-1.5-1.5c-1.7-1.7-1.7-4.3 0-6L9 12c-1 1-1 2.5 0 3.5L9 18zm6 0l1.5-1.5c1-1 1-2.5 0-3.5L15 12l1.5-1.5c1.7 1.7 1.7 4.3 0 6L15 18zm3-3l1.5-1.5c2-2 2-5.2 0-7.2L18 7.8c2 2 2 5.2 0 7.2L18 15zM12 13c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" />
                </svg>
              </div>
            </div>
            <!-- Brand Text -->
            <div class="ml-3 flex items-baseline flex-wrap justify-center">
              <span
                class="text-lg sm:text-xl font-black tracking-tight bg-gradient-to-r from-cyan-400 via-blue-400 to-teal-400 bg-clip-text text-transparent">
                JABBAR23
              </span>
              <span class="ml-1.5 text-sm sm:text-base font-bold text-gray-300">
                ISP
              </span>
              <span class="hidden sm:inline ml-1.5 text-xs text-gray-500">
                (Internet Service Provider)
              </span>
            </div>
          </a>

          <!-- Right Wave Animation (near )) -->
          <div class="hidden sm:block ml-3">
            <svg class="w-24 h-10 opacity-70" viewBox="0 0 100 40">
              <path class="animate-wave-1" d="M0,20 Q15,8 30,20 T60,20 T90,20 T100,20" fill="none"
                stroke="url(#wave-grad-right)" stroke-width="2.5" />
              <path class="animate-wave-2" d="M0,20 Q15,32 30,20 T60,20 T90,20 T100,20" fill="none"
                stroke="url(#wave-grad-right)" stroke-width="1.5" />
              <circle class="animate-particle-1" cx="15" cy="12" r="2" fill="#2dd4bf" opacity="0.8" />
              <circle class="animate-particle-2" cx="30" cy="28" r="1.5" fill="#22d3ee" opacity="0.6" />
              <circle class="animate-particle-3" cx="45" cy="10" r="1.5" fill="#3b82f6" opacity="0.7" />
              <defs>
                <linearGradient id="wave-grad-right" x1="0%" y1="0%" x2="100%" y2="0%">
                  <stop offset="0%" stop-color="#2dd4bf" />
                  <stop offset="100%" stop-color="transparent" />
                </linearGradient>
              </defs>
            </svg>
          </div>
        </div>

        <!-- Right: User Menu -->
        <div class="flex items-center">
          <div class="flex items-center ms-3">
            <div>
              <button type="button"
                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                <span class="sr-only">Open user menu</span>
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                  {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
              </button>
            </div>
            <!-- Dropdown menu -->
            <div
              class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
              id="dropdown-user">
              <div class="px-4 py-3" role="none">
                <p class="text-sm text-gray-900 dark:text-white" role="none">
                  {{ Auth::user()->name ?? 'User' }}
                </p>
                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                  {{ Auth::user()->email ?? 'email@isp.com' }}
                </p>
              </div>
              <ul class="py-1" role="none">
                <li>
                  <a href="{{ route('dashboard') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                    role="menuitem">Dashboard</a>
                </li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">Sign
                      out</a>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  @include('layouts.sidebar')

  <!-- Main Content -->
  <div class="p-4 sm:ml-64 mt-14">
    @isset($header)
      <div class="mb-4">
        {{ $header }}
      </div>
    @endisset

    @hasSection('content')
      @yield('content')
    @else
      {{ $slot ?? '' }}
    @endif
  </div>

  @stack('scripts')
</body>

</html>
