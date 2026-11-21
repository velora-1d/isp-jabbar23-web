@props([
    'label' => '',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'rows' => 4,
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
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full px-4 py-3.5
                           bg-gray-900/80 border border-gray-700/50 rounded-xl
                           text-white placeholder-gray-600 resize-none
                           focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20
                           disabled:opacity-50 disabled:cursor-not-allowed
                           transition-all duration-300'
            ]) }}
        >{{ old($name, $value) }}</textarea>
    </div>

    @if($hint)
    <p class="mt-1.5 text-xs text-gray-500">{{ $hint }}</p>
    @endif

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
