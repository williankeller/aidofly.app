@push('script-stack-before')
    <div role="dialog" aria-modal="true" tabindex="-1" class="modal fade show" id="{{ $id }}">
        <div class="modal-dialog modal-dialog-centered" role="document">{{ $slot }}</div>
    </div>
    <div class="modal-backdrop fade show d-none"></div>
@endpush
