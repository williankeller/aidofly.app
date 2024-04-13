@props([
    'variant' => 'primary',
    'reference' => null,
])
<button {{ $attributes->merge(['class' => 'btn btn-actionable btn-' . $variant]) }} id="btn-{{ $reference }}" data-actionable="{{ $reference }}" disabled>
    {{ $slot }}
</button>
