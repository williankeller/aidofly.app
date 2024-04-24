@props([
    'variant' => 'primary',
    'disabled' => true,
])
<button type="submit" {{ $attributes->merge(['class' => 'btn btn-' . $variant]) }} @disabled($disabled)>
    <div class="btn-loader loader"></div>
    <div class="btn-content d-flex align-items-center justify-content-center">{{ $slot }}</div>
</button>
