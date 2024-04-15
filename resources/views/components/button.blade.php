@props([
    'variant' => 'primary',
])
<button {{ $attributes->merge(['class' => 'btn btn-' . $variant]) }} :processing="isProcessing">
    {{ $slot }}
</button>
