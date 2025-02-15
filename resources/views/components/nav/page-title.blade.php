<section class="row my-4 my-lg-5 d-flex justify-content-between align-items-center">
    <div @class([
        'text-center text-lg-start',
        'col-lg-9 col-sm-12' => !$slot->isEmpty(),
    ])>
        <h2 class="page-heading mb-1">{{ $title }}</h2>
        <p class="mb-0 text-muted">{{ $lead }}</p>
    </div>
    @if (!$slot->isEmpty())
        <div class="d-flex col-lg-3 col-sm-12 mt-lg-0 mt-4 justify-content-center justify-content-lg-end">
            {{ $slot }}
        </div>
    @endif
</section>
