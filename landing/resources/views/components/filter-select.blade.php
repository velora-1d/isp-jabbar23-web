@props([
    'name',
    'label',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Semua',
    'allowEmpty' => true,
])

<div class="min-w-[150px]">
    <label class="block text-sm font-medium text-gray-400 mb-2">{{ $label }}</label>
    <select
        name="{{ $name }}"
        class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
    >
        @if($allowEmpty)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}" {{ request($name, $selected) == $value ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
