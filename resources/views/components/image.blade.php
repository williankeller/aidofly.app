@props([
    'src' => null,
    'alt' => null,
    'decoding' => 'async',
    'loading' => 'eager',
])
<img src="{{ asset($src) }}" alt="{{ $alt }}" decoding="{{ $decoding }}" loading="{{ $loading }}" {{ $attributes }}/>
