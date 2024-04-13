@props([
    'src' => null,
    'alt' => null,
    'decoding' => 'async',
    'loading' => 'eager',
])
<img src="{{ $src }}" alt="{{ $alt }}" decoding="{{ $decoding }}" loading="{{ $loading }}" {{ $attributes }}/>
