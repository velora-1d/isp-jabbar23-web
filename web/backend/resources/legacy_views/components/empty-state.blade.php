@props([
    'type' => 'default', // default, customers, invoices, payments, packages, tickets, search
    'title' => 'No Data Found',
    'description' => 'There are no items to display.',
    'actionText' => null,
    'actionUrl' => null,
])

@php
$illustrations = [
    'default' => '
        <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    ',
    'customers' => '
        <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none">
            <circle cx="50" cy="50" r="45" class="stroke-gray-700" stroke-width="2" stroke-dasharray="8 4"/>
            <circle cx="50" cy="35" r="12" class="fill-gray-700"/>
            <path d="M30 70c0-11 9-20 20-20s20 9 20 20" class="stroke-gray-600" stroke-width="3" stroke-linecap="round"/>
            <circle cx="75" cy="25" r="8" class="fill-cyan-500/30"/>
            <path d="M72 25h6M75 22v6" class="stroke-cyan-400" stroke-width="2" stroke-linecap="round"/>
        </svg>
    ',
    'invoices' => '
        <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none">
            <rect x="20" y="10" width="50" height="70" rx="4" class="stroke-gray-600" stroke-width="2"/>
            <path d="M30 30h30M30 42h25M30 54h20" class="stroke-gray-700" stroke-width="2" stroke-linecap="round"/>
            <circle cx="70" cy="70" r="20" class="fill-teal-500/20 stroke-teal-500/50" stroke-width="2"/>
            <path d="M65 70h10M70 65v10" class="stroke-teal-400" stroke-width="2" stroke-linecap="round"/>
        </svg>
    ',
    'payments' => '
        <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none">
            <rect x="15" y="30" width="60" height="40" rx="4" class="stroke-gray-600" stroke-width="2"/>
            <path d="M15 45h60" class="stroke-gray-700" stroke-width="2"/>
            <rect x="22" y="55" width="20" height="8" rx="2" class="fill-gray-700"/>
            <circle cx="75" cy="70" r="18" class="fill-emerald-500/20 stroke-emerald-500/50" stroke-width="2"/>
            <path d="M70 70l4 4 8-8" class="stroke-emerald-400" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    ',
    'packages' => '
        <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none">
            <path d="M50 15L85 35v30L50 85 15 65V35l35-20z" class="stroke-gray-600" stroke-width="2"/>
            <path d="M50 15v70M15 35l35 20 35-20" class="stroke-gray-700" stroke-width="2"/>
            <circle cx="50" cy="50" r="12" class="fill-blue-500/20"/>
            <path d="M50 44v12M44 50h12" class="stroke-blue-400" stroke-width="2" stroke-linecap="round"/>
        </svg>
    ',
    'tickets' => '
        <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none">
            <path d="M20 25h60v15c-5 0-10 5-10 10s5 10 10 10v15H20V60c5 0 10-5 10-10s-5-10-10-10V25z" class="stroke-gray-600" stroke-width="2"/>
            <path d="M35 40h30M35 50h20M35 60h25" class="stroke-gray-700" stroke-width="2" stroke-linecap="round"/>
            <circle cx="75" cy="25" r="15" class="fill-amber-500/20 stroke-amber-500/50" stroke-width="2"/>
            <path d="M72 25h6M75 22v6" class="stroke-amber-400" stroke-width="2" stroke-linecap="round"/>
        </svg>
    ',
    'search' => '
        <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none">
            <circle cx="42" cy="42" r="25" class="stroke-gray-600" stroke-width="2"/>
            <path d="M60 60l20 20" class="stroke-gray-600" stroke-width="3" stroke-linecap="round"/>
            <path d="M35 35l14 14M49 35l-14 14" class="stroke-gray-500" stroke-width="2" stroke-linecap="round"/>
        </svg>
    ',
];
@endphp

<div class="flex flex-col items-center justify-center py-12 px-4">
    <!-- Illustration -->
    <div class="relative mb-6">
        <!-- Decorative rings -->
        <div class="absolute inset-0 -m-4">
            <div class="w-32 h-32 rounded-full border border-gray-700/50 animate-pulse"></div>
        </div>
        <div class="absolute inset-0 -m-8">
            <div class="w-40 h-40 rounded-full border border-gray-800/30"></div>
        </div>

        <!-- Main illustration -->
        <div class="relative z-10">
            {!! $illustrations[$type] ?? $illustrations['default'] !!}
        </div>
    </div>

    <!-- Text -->
    <h3 class="text-lg font-semibold text-gray-300 mb-2 text-center">{{ $title }}</h3>
    <p class="text-gray-500 text-center max-w-sm mb-6">{{ $description }}</p>

    <!-- Action Button -->
    @if($actionText && $actionUrl)
    <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-cyan-500/25">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        {{ $actionText }}
    </a>
    @endif

    {{ $slot }}
</div>
