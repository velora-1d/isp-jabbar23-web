@props([
    'title' => '',
    'value' => '0',
    'subtitle' => '',
    'subtitleClass' => 'text-gray-500',
    'valueClass' => 'text-white',
    'colorFrom' => 'blue-500',
    'colorTo' => 'cyan-500',
    'href' => null
])

       
    
@php
    $tag = $href ? 'a' : 'div';
    $attrs = $href ? "href=\"{$href}\"" : '';
@endphp


           
        <{{ $tag }} {{ $attrs }} class="relative group cursor-pointer block">
    <!-- Animated Glow Effect -->
    <div class="absolute -inset-0.5 bg-gradient-to-r from-{{ $colorFrom }} to-{{ $colorTo }} rounded-2xl blur opacity-30 group-hover:opacity-70 transition-all duration-500 group-hover:duration-200"></div>

    <!-- Card with Scale & Border Animation -->
    <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50 transform transition-all duration-300 group-hover:scale-[1.02] group-hover:border-{{ $colorFrom }}/50 overflow-hidden">

        <!-- Shimmer Effect on Hover -->
        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/5 to-transparent pointer-events-none"></div>

        <div class="relative flex items-center justify-between">
            <div
                class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-400 truncate">{{ $title }}</p>
                <p class="text-2xl font-bold {{ $valueClass }} mt-1 truncate">{{ $value }}</p>
                @if($subtitle)
                    <p class="text-xs {{ $subtitleClass }} mt-2 truncate">{{ $subtitle }}</p>
                @endif

            </div>

            <!-- Icon Container with Pulse on Hover -->
            <div class="p-3 rounded-xl bg-gradient-to-br from-{{ $colorFrom }} to-{{ $colorTo }} shadow-lg shadow-{{ $colorFrom }}/30 group-hover:shadow-{{ $colorFrom }}/50 transition-all duration-300 group-hover:scale-110 flex-shrink-0 ml-4">
                {{ $icon }}
            </div>
        </div>
    </div>
</{{ $tag }}>
