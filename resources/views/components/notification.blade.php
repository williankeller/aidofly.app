@props([
    'message' => '',
    'icon' => 'ti ti-square-rounded-x-filled',
    'show' => 'false',
    'timeout' => 5000,
])

<div x-data="{ show: {{ $show }} }" x-init="setTimeout(() => show = false, {{ $timeout }})" :class="{ 'show': show }" data-message="notification"
    class="toast toast-container px-3 py-2 mb-4 bottom-0 start-50 translate-middle-x fade" aria-live="assertive"
    aria-atomic="true" role="alert">
    <div class="toast-body">
        <i data-message="icon" class="{{ $icon }}"></i>
        <span data-message="content" class="ms-1 message">{{ $message }}</span>
    </div>
</div>
