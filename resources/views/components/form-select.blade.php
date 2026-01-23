@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'value' => '',
    'placeholder' => '-- Pilih --',
    'required' => false,
    'disabled' => false,
    'hint' => '',
])

<div>
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-semibold text-gray-400 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-400 ml-1">*</span>
        @endif
    </label>
    @endif

    <div class="relative group">
        @if(isset($icon))
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none z-10">
            <div class="text-gray-600 group-focus-within:text-cyan-400 transition-colors duration-200">
                {{ $icon }}
            </div>
        </div>
        @endif

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full ' . (isset($icon) ? 'pl-12' : 'pl-4') . ' pr-10 py-3.5
                           bg-gray-900/80 border border-gray-700/50 rounded-xl
                           text-white appearance-none cursor-pointer
                           focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20
                           disabled:opacity-50 disabled:cursor-not-allowed
                           transition-all duration-300'
            ]) }}
        >
            @if($placeholder)
            <option value="">{{ $placeholder }}</option>
            @endif

            @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
            @endforeach

            {{ $slot }}
        </select>

        <!-- Custom Dropdown Arrow -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>

    @if($hint)
    <p class="mt-1.5 text-xs text-gray-500">{{ $hint }}</p>
    @endif

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
