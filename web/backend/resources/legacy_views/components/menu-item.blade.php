@props(['route', 'label', 'icon', 'active' => false, 'soon' => false, 'colorFrom' => 'blue-500', 'colorTo' => 'cyan-500'])

@php
    $isActive = $active ?: request()->routeIs($route);
    $baseColor = explode('-', $colorFrom)[0];
@endphp

<li class="relative">
    <!-- Active Indicator Bar -->
    <div
        class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-gradient-to-b from-{{ $colorFrom }} to-{{ $colorTo }} rounded-r-full transition-all duration-300 {{ $isActive ? 'opacity-100' : 'opacity-0' }}">
    </div>

    <a href="{{ $soon ? '#' : route($route) }}"
        class="flex items-center p-3 ml-1 rounded-xl transition-all duration-200 group
          {{ $isActive
    ? "bg-gradient-to-r from-{$baseColor}-600/20 to-{$colorTo}/20 text-white border border-{$baseColor}-500/30 shadow-lg shadow-{$baseColor}-500/10"
    : ($soon ? 'text-gray-500 cursor-not-allowed opacity-50' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white hover:translate-x-1') }}">

        <!-- Icon Container -->
        <div class="p-2 rounded-lg bg-gradient-to-br from-{{ $colorFrom }} to-{{ $colorTo }}
                    {{ $isActive ? 'shadow-lg shadow-' . $baseColor . '-500/30' : ($soon ? 'opacity-40' : 'opacity-60 group-hover:opacity-100') }}
                    transition-all duration-200 group-hover:scale-110">
            {!! $icon !!}
        </div>

        <span class="flex-1 ms-3 font-semibold">{{ $label }}</span>

        @if($soon)
            <span class="px-1.5 py-0.5 text-[10px] bg-gray-700 text-gray-500 rounded">Soon</span>
        @endif

        @if($isActive)
            <div class="w-2 h-2 rounded-full bg-{{ $colorFrom }} animate-pulse"></div>
        @endif
    </a>
</li>
