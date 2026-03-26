<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | JABBAR23</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-lg">
        <!-- Illustration -->
        <div class="relative mx-auto w-64 h-64 mb-8">
            <!-- Background circles -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-48 h-48 rounded-full border-2 border-dashed border-gray-700 animate-spin"
                    style="animation-duration: 20s;"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-64 h-64 rounded-full border border-gray-800"></div>
            </div>

            <!-- 404 Text -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center">
                    <span
                        class="text-8xl font-black bg-gradient-to-br from-cyan-400 via-blue-500 to-teal-600 bg-clip-text text-transparent">404</span>
                </div>
            </div>

            <!-- Floating elements -->
            <div class="absolute top-4 right-8 w-4 h-4 rounded-full bg-cyan-500/50 animate-bounce"
                style="animation-delay: 0s;"></div>
            <div class="absolute bottom-8 left-4 w-3 h-3 rounded-full bg-teal-500/50 animate-bounce"
                style="animation-delay: 0.5s;"></div>
            <div class="absolute top-1/2 left-0 w-2 h-2 rounded-full bg-blue-500/50 animate-bounce"
                style="animation-delay: 1s;"></div>
        </div>

        <!-- Text -->
        <h1 class="text-3xl font-bold text-white mb-4">Halaman Tidak Ditemukan</h1>
        <p class="text-gray-400 mb-8">
            Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau dihapus.
        </p>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200 border border-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-cyan-500/25">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
        </div>
    </div>
</body>

</html>
