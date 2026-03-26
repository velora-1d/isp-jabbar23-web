@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
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
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
            <div class="text-gray-600 group-focus-within:text-cyan-400 transition-colors duration-200">
                {{ $icon }}
            </div>
        </div>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full ' . (isset($icon) ? 'pl-12' : 'pl-4') . ' pr-4 py-3.5
                           bg-gray-900/80 border border-gray-700/50 rounded-xl
                           text-white placeholder-gray-600
                           focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20
                           disabled:opacity-50 disabled:cursor-not-allowed
                           transition-all duration-300'
            ]) }}
        >

        @if(isset($suffix))
        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
            <span class="text-gray-500">{{ $suffix }}</span>
        </div>
        @endif
    </div>

    @if($hint)
    <p class="mt-1.5 text-xs text-gray-500">{{ $hint }}</p>
    @endif

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
