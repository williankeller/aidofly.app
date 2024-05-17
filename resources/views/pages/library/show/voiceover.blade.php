@extends('layouts.app')

@section('content')
    <section class="my-4 my-lg-5 row d-flex justify-content-between align-items-center">
        <div class="text-center text-lg-start col-lg-9 col-sm-12">
            <h2 class="page-heading mb-1">{{ $metaTitle }}</h2>
            <div
                class="d-flex align-items-center mt-3 mt-md-2 justify-content-between justify-content-between justify-content-md-center justify-content-lg-start text-muted">
                <div class="d-block d-md-flex align-items-center mx-1 me-md-4">
                    <i class="ti ti-coins me-0 me-md-1 d-block d-md-flex"></i>
                    <span>@lang(':credits credits', ['credits' => $library->cost])</span>
                </div>
                <div class="d-block d-md-flex align-items-center mx-1 me-md-4">
                    <i class="ti ti-square-rounded-letter-t me-0 me-md-1 d-block d-md-flex"></i>
                    <span>@lang(':letters letters', ['letters' => $library->tokens])</span>
                </div>
                <div class="d-block d-md-flex align-items-center mx-1 me-md-4">
                    <i class="ti ti-brain me-0 me-md-1 d-block d-md-flex"></i>
                    <span>@lang(':model', ['model' => $library->model])</span>
                </div>
                <div class="d-flex align-items-center mx-1 me-lg-4">
                    <i class="ti ti-speakerphone me-1"></i>
                    <span>@lang(':name', ['name' => $library->voice->name])</span>
                </div>
            </div>
        </div>

        <div class="d-flex col-lg-3 col-sm-12 mt-lg-0 mt-4 justify-content-center justify-content-lg-end">
            <x-nav.back route="library.index" />
        </div>
    </section>

    <section class="p-3 p-sm-5 card mb-3 voiceover">
        <div class="card p-2">
            <component-wave class="d-flex justify-content-between align-items-center" x-ref="previewWave"
                src="{{ filestorage($library->uuid) }}" @audioprocess="previewTime = $event.detail.time" state="initial">
                <button type="button" play-pause
                    class="btn btn-primary btn-play-pause icon icon-md p-1 d-flex align-items-center" style="width: 120px;">
                    <div class="play">
                        <div class=" d-flex align-items-center">
                            <i class="ti ti-player-play-filled me-1"></i>
                            <span>@lang('Listen')</span>
                        </div>
                    </div>
                    <i class="pause ti ti-player-pause-filled"></i>
                    <div class="loading spinner-grow spinner-grow-sm m-1" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
                <span process class="ms-3 small process-timer">00:00</span>
                <div wave class="mx-3 flex-grow-1"></div>
                <span duration class="small duration-timer"></span>
            </component-wave>
        </div>
        <div class="original-prompt mt-4">
            @if ($prompt->get('largeContent'))
                <div class="content-preview" x-ref="contentPreview" x-tooltip.raw="{{ __('Toggle full prompt') }}"
                    @click="$refs.contentPreview.classList.toggle('content-preview')">
                    <div class="fw-bold me-1">@lang('Prompt'):</div>
                    <div class="content-truncate">{!! $prompt->get('content') !!}</div>
                </div>
            @else
                <span class="fw-bold">@lang('Prompt'):</span>
                <span class="content-truncate">{!! $prompt->get('content') !!}</span>
            @endif
        </div>
    </section>
    <section class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <x-modal.trigger id="delete-library-modal" variant="white" class="p-0 me-4"
                x-tooltip.raw="{{ __('Delete voiceover') }}">
                <i class="ti ti-trash fs-4 text-hover-danger" x-ref="trashHover"></i>
            </x-modal.trigger>
            <button class="btn btn-white p-0 me-4">
                @if ($library->visibility == 'public')
                    <div x-tooltip.raw="@lang('Public')" class="d-flex align-items-center">
                        <i class="ti ti-world fs-4"></i>
                    </div>
                @else
                    <div x-tooltip.raw="@lang('Visible only to me')" class="d-flex align-items-center">
                        <i class="ti ti-lock fs-4"></i>
                    </div>
                @endif
            </button>
            <form method="post" action="{{ route('library.filestorage.download', $library->uuid) }}">
                <div role="none" tabindex="-1" class="d-none">
                    @csrf
                    @method('GET')
                </div>
                <button type="submit" class="btn btn-white p-0 d-flex align-items-center"
                    x-tooltip.raw="@lang('Download')">
                    <i class="ti ti-download fs-4"></i>
                </button>
            </form>
        </div>
        <a class="btn btn-light d-flex align-items-center"
            href="{{ route('agent.voiceover.show', $library->voice->uuid) }}">
            <i class="ti ti-sparkles fs-4"></i>
            <span class="ms-1">@lang('Use :voice again', ['voice' => $library->voice->name])</span>
        </a>
    </section>
@endsection

@push('script-stack-before')
    <x-modal.modal id="delete-library-modal" size="md">
        <form class="modal-content" method="post" action="{{ route('library.destroy', $library->uuid) }}">
            <div class="modal-body p-5 text-center">
                <h5 class="fw-bold mb-3">@lang('Delete libray item?')</h5>
                <p>@lang("You are about to delete the <strong>\":title\"</strong> voiceover. Once deleted, it cannot be recovered.", ['title' => $library->title])</p>
            </div>
            <div role="none" tabindex="-1" class="d-none">
                @method('DELETE')
                @csrf
                <input type="hidden" name="uuid" value="{{ $library->uuid }}">
            </div>
            <div class="modal-footer flex-nowrap p-0">
                <button type="button" class="btn btn-link text-decoration-none col-6 py-3 m-0 rounded-0 border-end text-body fw-normal"
                    @click="modal.close()">@lang('No, cancel')</button>
                <button type="submit"
                    class="btn btn-link text-decoration-none col-6 py-3 m-0 rounded-0 text-danger fw-bold">@lang('Yes, delete')</button>
            </div>
        </form>
    </x-modal.modal>

    {!! javascript('js/voiceover.min.js') !!}
@endpush
