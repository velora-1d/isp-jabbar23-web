@props([
    'action' => '',
    'method' => 'GET',
    'resetUrl' => null,
    'collapsible' => true,
])

<div
    x-data="{
        expanded: window.innerWidth >= 768,
        filterCount: 0,
        init() {
            const params = new URLSearchParams(window.location.search);
            let count = 0;
            params.forEach((value, key) => {
                if (value && key !== 'page') count++;
            });
            this.filterCount = count;
        }
    }"
    class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden mb-6"
>
    <!-- Mobile Toggle Header -->
    @if($collapsible)
    <button
        type="button"
        @click="expanded = !expanded"
        class="md:hidden w-full flex items-center justify-between p-4 text-left"
    >
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <span class="font-semibold text-white">Filters & Actions</span>
            <span
                x-show="filterCount > 0"
                x-text="filterCount"
                class="px-2 py-0.5 text-xs font-bold rounded-full bg-cyan-500 text-white"
            ></span>
        </div>
        <svg
            class="w-5 h-5 text-gray-400 transition-transform duration-200"
            :class="expanded ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    @endif

    <!-- Filter Content -->
    <form
        action="{{ $action ?: request()->url() }}"
        method="{{ $method }}"
        x-show="expanded || window.innerWidth >= 768"
        x-collapse
        class="p-4 md:p-6 space-y-6"
    >
        <!-- Top Section: Global Filters & Actions -->
        @if(isset($global) || isset($actions))
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <!-- Global Search -->
            <div class="flex-1 w-full sm:max-w-4xl flex items-end gap-4">
                {{ $global ?? '' }}
            </div>

            <!-- Actions (Add New, etc) -->
            <div class="flex-shrink-0">
                {{ $actions ?? '' }}
            </div>
        </div>
        @endif

        <!-- Secondary Filters -->
        @if(isset($filters) || !$slot->isEmpty())
        <div class="pt-4 border-t border-gray-700/50">
            <div class="flex flex-wrap gap-4 items-end">
                {{ $filters ?? '' }}
                {{ $slot }}

                <!-- Filter Actions -->
                <div class="flex gap-2 ml-auto">
                    @if($resetUrl)
                    <a
                        href="{{ $resetUrl }}"
                        class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-xl transition-all duration-200 border border-gray-600"
                    >
                        Reset
                    </a>
                    @endif
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-cyan-500/25"
                    >
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </span>
                    </button>
                </div>
            </div>
        </div>
        @endif
    </form>
</div>
