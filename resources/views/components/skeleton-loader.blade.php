{{-- Skeleton Loader Component --}}
{{-- Usage: <x-skeleton-loader type="card" /> --}}
{{-- Types: card, table-row, text, avatar, button --}}

@props([
    'type' => 'text',
    'count' => 1,
    'columns' => 4
])

@if($type === 'card')
    {{-- Stat Card Skeleton --}}
    <div class="relative group">
        <div class="absolute -inset-0.5 bg-gradient-to-r from-gray-700 to-gray-600 rounded-2xl blur opacity-30"></div>
        <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50 animate-pulse">
            <div class="flex items-center justify-between">
                <div class="space-y-3 flex-1">
                    <div class="h-3 bg-gray-700 rounded w-24"></div>
                    <div class="h-8 bg-gray-700 rounded w-20"></div>
                    <div class="h-2 bg-gray-700 rounded w-16"></div>
                </div>
                <div class="w-12 h-12 bg-gray-700 rounded-xl"></div>
            </div>
        </div>
    </div>

@elseif($type === 'table-row')
    {{-- Table Row Skeleton --}}
    @for($i = 0; $i < $count; $i++)
    <tr class="animate-pulse">
        <td class="px-6 py-4">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-gray-700 rounded-full"></div>
                <div class="space-y-2">
                    <div class="h-4 bg-gray-700 rounded w-32"></div>
                    <div class="h-3 bg-gray-700 rounded w-24"></div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="h-3 bg-gray-700 rounded w-20"></div>
        </td>
        <td class="px-6 py-4">
            <div class="space-y-2">
                <div class="h-4 bg-gray-700 rounded w-24"></div>
                <div class="h-3 bg-gray-700 rounded w-16"></div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="h-6 bg-gray-700 rounded-full w-20"></div>
        </td>
        <td class="px-6 py-4">
            <div class="h-3 bg-gray-700 rounded w-20"></div>
        </td>
        <td class="px-6 py-4">
            <div class="flex justify-end space-x-2">
                <div class="w-8 h-8 bg-gray-700 rounded-lg"></div>
                <div class="w-8 h-8 bg-gray-700 rounded-lg"></div>
                <div class="w-8 h-8 bg-gray-700 rounded-lg"></div>
            </div>
        </td>
    </tr>
    @endfor

@elseif($type === 'text')
    {{-- Text Lines Skeleton --}}
    <div class="animate-pulse space-y-3">
        @for($i = 0; $i < $count; $i++)
        <div class="h-4 bg-gray-700 rounded w-full"></div>
        @endfor
        <div class="h-4 bg-gray-700 rounded w-3/4"></div>
    </div>

@elseif($type === 'avatar')
    {{-- Avatar Skeleton --}}
    <div class="animate-pulse">
        <div class="w-10 h-10 bg-gray-700 rounded-full"></div>
    </div>

@elseif($type === 'button')
    {{-- Button Skeleton --}}
    <div class="animate-pulse">
        <div class="h-10 bg-gray-700 rounded-xl w-32"></div>
    </div>

@elseif($type === 'stat-cards')
    {{-- Multiple Stat Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $columns }} gap-6">
        @for($i = 0; $i < $columns; $i++)
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-gray-700 to-gray-600 rounded-2xl blur opacity-30"></div>
            <div class="relative p-6 bg-gray-800 rounded-2xl border border-gray-700/50 animate-pulse">
                <div class="flex items-center justify-between">
                    <div class="space-y-3 flex-1">
                        <div class="h-3 bg-gray-700 rounded w-24"></div>
                        <div class="h-8 bg-gray-700 rounded w-20"></div>
                        <div class="h-2 bg-gray-700 rounded w-16"></div>
                    </div>
                    <div class="w-12 h-12 bg-gray-700 rounded-xl"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>

@elseif($type === 'table')
    {{-- Full Table Skeleton --}}
    <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden animate-pulse">
        <div class="p-6 border-b border-gray-700/50">
            <div class="flex items-center justify-between">
                <div class="h-6 bg-gray-700 rounded w-32"></div>
                <div class="h-10 bg-gray-700 rounded-lg w-64"></div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900/50">
                    <tr>
                        @for($i = 0; $i < 6; $i++)
                        <th class="px-6 py-4">
                            <div class="h-3 bg-gray-700 rounded w-16"></div>
                        </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @for($j = 0; $j < 5; $j++)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gray-700 rounded-full"></div>
                                <div class="space-y-2">
                                    <div class="h-4 bg-gray-700 rounded w-32"></div>
                                    <div class="h-3 bg-gray-700 rounded w-24"></div>
                                </div>
                            </div>
                        </td>
                        @for($k = 0; $k < 4; $k++)
                        <td class="px-6 py-4">
                            <div class="h-4 bg-gray-700 rounded w-20"></div>
                        </td>
                        @endfor
                        <td class="px-6 py-4">
                            <div class="flex justify-end space-x-2">
                                <div class="w-8 h-8 bg-gray-700 rounded-lg"></div>
                                <div class="w-8 h-8 bg-gray-700 rounded-lg"></div>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
@endif
