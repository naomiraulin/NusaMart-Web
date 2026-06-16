@props([
    'type' => 'button', // Default-nya adalah button jika tidak ditentukan
    'label' => '',
    'name' => '',
    'value' => '',
    'placeholder' => ''
])

{{-- 1. ELEMEN BUTTON --}}
@if($type === 'button')
    <button {{ $attributes->merge(['class' => 'bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md font-medium transition duration-200 text-sm']) }}>
        {{ $slot }}
    </button>

{{-- 2. ELEMEN INPUT TEXT / FORM --}}
@elseif($type === 'input')
    <div class="w-full">
        @if($label)
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
        @endif
        <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500 outline-none text-sm']) }}>
    </div>

{{-- 3. ELEMEN CARD DASHBOARD --}}
@elseif($type === 'card')
    <div {{ $attributes->merge(['class' => 'bg-white p-6 rounded-lg shadow-sm border border-gray-100']) }}>
        {{ $slot }}
    </div>

{{-- 4. ELEMEN BADGE / STATUS --}}
@elseif($type === 'badge')
    <span {{ $attributes->merge(['class' => 'px-2 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800']) }}>
        {{ $slot }}
    </span>
@endif