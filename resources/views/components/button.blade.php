@props([
    'variant' => 'primary',
    'reference' => null,
])
<button {{ $attributes->merge(['class' => 'btn btn-actionable disabled btn-' . $variant]) }} id="btn-{{ $reference }}" data-action="{{ $reference }}" disabled>
    {{ $slot }}
</button>
