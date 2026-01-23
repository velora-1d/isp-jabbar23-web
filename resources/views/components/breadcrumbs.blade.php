@props([
    'items' => [], // Array of ['label' => 'Home', 'url' => '/']
    'separator' => 'chevron', // chevron, slash, arrow
])

@php
$separators = [
    'chevron' => '<svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
    'slash' => '<span class="text-gray-600">/</span>',
    'arrow' => '<svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>',
];
$sep = $separators[$separator] ?? $separators['chevron'];
@endphp

<nav class="flex items-center gap-2 text-sm" aria-label="Breadcrumb">
    <!-- Home Link -->
    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-300 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
    </a>

    @foreach($items as $index => $item)
        {!! $sep !!}

        @if($loop->last)
            <span class="text-gray-300 font-medium">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] }}" class="text-gray-500 hover:text-gray-300 transition-colors">
                {{ $item['label'] }}
            </a>
        @endif
    @endforeach
</nav>
