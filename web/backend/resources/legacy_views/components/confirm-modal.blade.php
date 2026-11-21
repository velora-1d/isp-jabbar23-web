@props([
    'id' => 'confirm-modal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin ingin melanjutkan?',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',
    'type' => 'danger', // danger, warning, info
])

@php
$colors = [
    'danger' => [
        'icon' => 'text-red-400',
        'iconBg' => 'bg-red-500/20',
        'button' => 'from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 shadow-red-500/25',
    ],
    'warning' => [
        'icon' => 'text-amber-400',
        'iconBg' => 'bg-amber-500/20',
        'button' => 'from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 shadow-amber-500/25',
    ],
    'info' => [
        'icon' => 'text-blue-400',
        'iconBg' => 'bg-blue-500/20',
        'button' => 'from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 shadow-blue-500/25',
    ],
];
$color = $colors[$type] ?? $colors['danger'];
@endphp

<!-- Backdrop -->
<div
    x-data="{ open: false }"
    x-show="open"
    x-on:open-confirm-{{ $id }}.window="open = true"
    x-on:close-confirm-{{ $id }}.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" x-on:click="open = false"></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative w-full max-w-md bg-gray-800 rounded-2xl border border-gray-700 shadow-2xl shadow-black/50"
            x-on:click.stop
        >
            <!-- Content -->
            <div class="p-6 text-center">
                <!-- Icon -->
                <div class="mx-auto w-16 h-16 rounded-full {{ $color['iconBg'] }} flex items-center justify-center mb-4">
                    @if($type === 'danger')
                    <svg class="w-8 h-8 {{ $color['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    @elseif($type === 'warning')
                    <svg class="w-8 h-8 {{ $color['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    @else
                    <svg class="w-8 h-8 {{ $color['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @endif
                </div>

                <!-- Title -->
                <h3 class="text-xl font-bold text-white mb-2">{{ $title }}</h3>

                <!-- Message -->
                <p class="text-gray-400 mb-6">{{ $message }}</p>

                <!-- Buttons -->
                <div class="flex gap-3 justify-center">
                    <button
                        type="button"
                        x-on:click="open = false"
                        class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200 border border-gray-600"
                    >
                        {{ $cancelText }}
                    </button>
                    <button
                        type="button"
                        x-on:click="$dispatch('confirm-{{ $id }}'); open = false"
                        class="px-5 py-2.5 bg-gradient-to-r {{ $color['button'] }} text-white font-semibold rounded-xl transition-all duration-200 shadow-lg"
                    >
                        {{ $confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
