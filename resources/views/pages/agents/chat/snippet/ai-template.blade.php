<div class="message-ai d-flex justify-content-start mb-4">
    <div class="d-flex flex-column align-items-start">
        <div class="d-flex align-items-center mb-2">
            <div class="icon icon-sm bg-gradient bg-info-subtle">
                <i class="ti ti-sparkles text-info"></i>
            </div>
            <div class="ms-2">
                <span class="fw-bold me-1">@lang('AI')</span>
            </div>
        </div>
        <div class="ms-4 px-4 py-3 rounded bg-light text-start" data-message-element="content">
            @isset ($message->content)
                {!! markdownToHtml($message->content) !!}
            @else
                <div class="d-flex flex-column align-items-start">
                    <div class="d-flex align-items-center" style="height: 24px;">
                        <div class="spinner-grow spinner-grow-sm bg-primary"></div>
                    </div>
                </div>
            @endisset
        </div>
    </div>
</div>
