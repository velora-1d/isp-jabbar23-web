@props([
    'type' => 'submit',
    'variant' => 'primary', // primary, secondary, danger, success
    'size' => 'md', // sm, md, lg
    'loading' => false,
    'disabled' => false,
    'fullWidth' => false,
])

@php
$variants = [
    'primary' => 'bg-gradient-to-r from-cyan-600 via-blue-600 to-teal-600 hover:from-cyan-500 hover:via-blue-500 hover:to-teal-500 shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/30',
    'secondary' => 'bg-gray-700 hover:bg-gray-600 border border-gray-600 hover:border-gray-500',
    'danger' => 'bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 shadow-lg shadow-red-500/20 hover:shadow-red-500/30',
    'success' => 'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30',
];

$sizes = [
    'sm' => 'px-4 py-2 text-sm',
    'md' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
];
@endphp

<button
    type="{{ $type }}"
    {{ $disabled || $loading ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => 'relative font-bold text-white rounded-xl overflow-hidden
                   transition-all duration-300
                   disabled:opacity-50 disabled:cursor-not-allowed
                   ' . $variants[$variant] . ' ' . $sizes[$size] . ' ' . ($fullWidth ? 'w-full' : '')
    ]) }}
>
    @if($loading)
    <span class="absolute inset-0 flex items-center justify-center bg-inherit">
        <x-loading-spinner size="sm" />
    </span>
    @endif

    <span class="relative flex items-center justify-center gap-2 {{ $loading ? 'invisible' : '' }}">
        {{ $slot }}
    </span>
</button>
