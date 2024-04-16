@props([
    'variant' => 'primary',
    'disabled' => true,
])
<button type="submit" {{ $attributes->merge(['class' => 'btn btn-' . $variant]) }} :processing="isProcessing"
    @disabled($disabled)>
    {{ $slot }}
</button>
