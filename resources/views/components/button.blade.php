@props([
    'variant' => 'primary',
    'disabled' => true,
])
<button type="submit" {{ $attributes->merge(['class' => ' d-flex align-items-center justify-content-center btn btn-' . $variant]) }} :processing="isProcessing"
    @disabled($disabled)>
    {{ $slot }}
</button>
