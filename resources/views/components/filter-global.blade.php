@props([
    'showYear' => true,
    'showMonth' => true,
    'showDateRange' => false,
    'showSearch' => true,
    'searchPlaceholder' => 'Cari...',
    'searchName' => 'search',
    'yearName' => 'year',
    'monthName' => 'month',
    'startDateName' => 'start_date',
    'endDateName' => 'end_date',
])

@php
    $currentYear = date('Y');
    $years = range($currentYear, $currentYear - 5); // Last 5 years
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
@endphp

@if($showSearch)
<div class="flex-1 min-w-[200px]">
    <label class="block text-sm font-medium text-gray-400 mb-2">Pencarian</label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input
            type="text"
            name="{{ $searchName }}"
            value="{{ request($searchName) }}"
            placeholder="{{ $searchPlaceholder }}"
            class="w-full bg-gray-700/50 border border-gray-600 rounded-xl pl-12 pr-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
            x-on:input.debounce.500ms="$el.form.submit()"
        >
    </div>
</div>
@endif

@if($showYear)
<div class="min-w-[120px]">
    <label class="block text-sm font-medium text-gray-400 mb-2">Tahun</label>
    <select
        name="{{ $yearName }}"
        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
    >
        <option value="">Semua</option>
        @foreach($years as $year)
            <option value="{{ $year }}" {{ request($yearName) == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>
</div>
@endif

@if($showMonth)
<div class="min-w-[140px]">
    <label class="block text-sm font-medium text-gray-400 mb-2">Bulan</label>
    <select
        name="{{ $monthName }}"
        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
    >
        <option value="">Semua</option>
        @foreach($months as $num => $name)
            <option value="{{ $num }}" {{ request($monthName) == $num ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>
@endif

@if($showDateRange)
<div class="min-w-[150px]">
    <label class="block text-sm font-medium text-gray-400 mb-2">Dari Tanggal</label>
    <input
        type="date"
        name="{{ $startDateName }}"
        value="{{ request($startDateName) }}"
        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
    >
</div>
<div class="min-w-[150px]">
    <label class="block text-sm font-medium text-gray-400 mb-2">Sampai Tanggal</label>
    <input
        type="date"
        name="{{ $endDateName }}"
        value="{{ request($endDateName) }}"
        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
    >
</div>
@endif
