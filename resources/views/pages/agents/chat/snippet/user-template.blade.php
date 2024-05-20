<div class="message-user d-flex justify-content-end mb-4">
    <div class="d-flex flex-column align-items-end">
        <div class="d-flex align-items-center mb-2">
            <div class="me-2">
                <span class="fw-bold ms-1">@lang('You')</span>
            </div>
            <div class="icon icon-sm bg-primary bg-gradient bg-opacity-10">
                <div class="text-primary fw-bold small">{{ $authUser->initials }}</div>
            </div>
        </div>
        <div class="me-4 px-4 py-3 rounded bg-light mw-lg-400px text-end" data-message-element="content"></div>
    </div>
</div>
