@props([
    'color' => 'success',
    'icon' => 'ti-star',
    'size' => 'md',
])
<div class="icon-{{ $size }} bg-{{ $color }}">
    <i class="ti {{ $icon }}"></i>
</div>
