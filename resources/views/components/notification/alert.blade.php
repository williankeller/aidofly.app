@props([
    'message' => '',
    'icon' => 'ti-square-rounded-x-filled',
    'show' => 'false',
    'timeout' => 6000,
])

<div role="alert" aria-live="assertive" aria-atomic="true"
    class="toast toast-container position-fixed px-3 py-2 mb-4 bottom-0 start-50 translate-middle-x fade"
    @if ($show !== 'false') x-data="{ show: {{ $show }} }" x-init="setTimeout(() => show = false, {{ $timeout }})" :class="{ 'show': show }" @endif>
    <div class="toast-body d-flex align-items-center">
        <i data-message="icon" class="fs-4 ti {{ $icon }}"></i>
        <span data-message="content" class="ms-2 message">{{ $message }}</span>
    </div>
</div>
