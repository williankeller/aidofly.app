@props([
    'icon' => 'ti ti-server',
    'title' => 'No data yet',
    'subtitle' => 'There is no data available yet',
])
<div class="empty text-center p-5 m-5">
    <div class="empty-icon">
        <i class="fs-1 {{ $icon }}"></i>
    </div>
    <p class="empty-title h4 mt-2 fw-bolder">{{ $title }}</p>
    <p class="empty-subtitle text-muted">{{ $subtitle }}</p>
    {{ $slot }}
</div>
