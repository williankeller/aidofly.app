@props([
    'color' => 'var(--bs-primary)',
    'icon' => 'ti-star',
    'size' => '4',
])
<div class="bg-{{ $color }} bg-gradient rounded p-2 d-flex align-items-center">
    <i class="fs-{{ $size }} text-white ti {{ $icon }}"></i>
</div>
