@extends('layouts.app')

@section('content')
    <section class="my-4 my-lg-5 row d-flex justify-content-between align-items-center">
        <div class="text-center text-lg-start col-lg-9 col-sm-12">
            <h2 class="page-heading mb-1">{{ $metaTitle }}</h2>
            <div class="d-flex align-items-center mt-2 justify-content-center justify-content-lg-start text-muted">
                <div class="d-flex align-items-center me-4">
                    <i class="ti ti-coins me-1"></i>
                    <span>@lang(':credits credits', ['credits' => $library->cost])</span>
                </div>
                <div class="d-flex align-items-center me-4">
                    <i class="ti ti-square-rounded-letter-t me-1"></i>
                    <span>@lang(':characters characters', ['characters' => $library->tokens])</span>
                </div>
                <div class="d-flex align-items-center me-4">
                    <i class="ti ti-brain me-1"></i>
                    <span>@lang(':model', ['model' => $library->model])</span>
                </div>
                <div class="d-flex align-items-center me-4">
                    <i class="ti ti-speakerphone me-1"></i>
                    <span>@lang(':name', ['name' => $library->voice->name])</span>
                </div>
            </div>
        </div>

        <div class="d-flex col-lg-3 col-sm-12 mt-lg-0 mt-4 justify-content-center justify-content-lg-end">
            <a class="btn btn-light d-flex align-items-center"
                href="{{ route('agent.voiceover.show', $library->voice->uuid) }}">
                <i class="ti ti-sparkles fs-4"></i>
                <span class="ms-1">@lang('Use :voice again', ['voice' => $library->voice->name])</span>
            </a>
        </div>
    </section>

    <section class="p-3 p-sm-5 card mb-3 voiceover">
        <div class="card p-2">
            <component-wave class="d-flex justify-content-between align-items-center" x-ref="previewWave"
                src="{{ filestorage($library->uuid) }}" @audioprocess="previewTime = $event.detail.time" state="initial">
                <button type="button" play-pause
                    class="btn btn-primary btn-play-pause icon-md p-1 d-flex align-items-center" style="width: 120px;">
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

        <div class="d-flex justify-content-between mt-5">
            <div class="d-flex align-items-center mr-auto">
                <div class="audience">
                    <button class="btn btn-secondary btn-sm py-1 d-flex align-items-center">
                        @if ($library->visibility == 'public')
                            <i class="ti ti-world me-1"></i>
                            <span>@lang('Public')</span>
                        @else
                            <i class="ti ti-lock me-1"></i>
                            <span>@lang('Visible only to me')</span>
                        @endif
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <form method="post" class="me-4" action="{{ route('library.filestorage.download', $library->uuid) }}">
                    <div role="none" tabindex="-1" class="d-none">
                        @csrf
                        @method('GET')
                    </div>
                    <button type="submit" class="btn btn-white p-0 d-flex align-items-center">
                        <i class="ti ti-download fs-4"></i>
                    </button>
                </form>
                <x-modal.trigger id="delete-library-modal" variant="white" class="p-0">
                    <i class="ti ti-trash fs-4 text-hover-danger" x-ref="trashHover"></i>
                </x-modal.trigger>
            </div>
        </div>
    </section>
@endsection

@push('script-stack-before')
    <x-modal.modal id="delete-library-modal" size="md">
        <form class="modal-content" method="post" action="{{ route('library.destroy', $library->uuid) }}">
            <div class="modal-body p-5 text-center">
                <h5 class="fw-bold mb-3">@lang('Delete libray item?')</h5>
                <p>@lang('You are about to delete the <strong>":title"</strong> voiceover. Once deleted, it cannot be recovered.', ['title' => $library->title])</p>
            </div>
            <div role="none" tabindex="-1" class="d-none">
                @method('DELETE')
                @csrf
                <input type="hidden" name="uuid" value="{{ $library->uuid }}">
            </div>
            <div class="modal-footer flex-nowrap p-0">
                <button type="button" class="btn btn-link text-decoration-none col-6 py-3 m-0 rounded-0 border-end"
                    @click="modal.close()">@lang('No, cancel')</button>
                <button type="submit"
                    class="btn btn-link text-decoration-none col-6 py-3 m-0 rounded-0 text-danger fw-bold">@lang('Yes, delete')</button>
            </div>
        </form>
    </x-modal.modal>

    {!! javascript('js/voiceover.min.js') !!}
@endpush
