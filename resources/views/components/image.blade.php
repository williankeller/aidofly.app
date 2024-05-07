@props([
    'src' => null,
    'alt' => null,
    'decoding' => 'async',
    'loading' => 'eager',
    'priority' => 'auto',
])
<img src="{{ asset('/img' . $src) }}" alt="{{ $alt }}" decoding="{{ $decoding }}" loading="{{ $loading }}"
    fetchpriority="{{ $priority }}" {{ $attributes }} />
