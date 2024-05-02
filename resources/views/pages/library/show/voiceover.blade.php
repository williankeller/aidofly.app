@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="library.agent.index" :name="__('Library')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
            <a class="btn btn-light d-flex align-items-center"
                href="{{ route('agent.voiceover.show', $library->voice->uuid) }}">
                <i class="ti ti-sparkles fs-4"></i>
                <span class="ms-1">@lang('Generate new')</span>
            </a>
        </x-nav.page-title>
    </section>

    <section class="p-5 card mb-3 voiceover">
        <div class="card p-2">
            <component-wave class="d-flex justify-content-between align-items-center" x-ref="previewWave"
                src="{{ filestorage($library->uuid) }}" @audioprocess="previewTime = $event.detail.time" state="initial">
                <button type="button" play-pause class="btn btn-primary btn-play-pause icon-md p-1">
                    <i class="play ti ti-player-play-filled"></i>
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
                <div class="d-flex text-muted">
                    <div class="d-flex align-items-center ms-1 me-4">
                        <i class="ti ti-brain me-1"></i>
                        <span>@lang(':model', ['model' => $library->model])</span>
                    </div>
                    <div class="d-flex align-items-center ms-1 me-4">
                        <i class="fs-4 ti ti-abc me-1"></i>
                        <span>@lang(':chars characters', ['chars' => $library->tokens])</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-coins me-1"></i>
                        <span>@lang(':credits credits', ['credits' => $library->cost])</span>
                    </div>
                </div>
                <div class="ms-4 audience">
                    <button class="btn btn-secondary btn-sm py-0 d-flex align-items-center">
                        @if ($library->visibility == 'public')
                            <i class="ti ti-world"></i>
                            <span>@lang('Public')</span>
                        @else
                            <i class="ti ti-lock"></i>
                            <span>@lang('Only me')</span>
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
                    <button type="submit" class="btn btn-white p-0">
                        <i class="ti ti-download fs-4"></i>
                    </button>
                </form>

                <button class="btn btn-white p-0" @click="deleteDocument(library.content)">
                    <i class="ti ti-trash fs-4 text-hover-danger" x-ref="trashHover"></i>
                </button>
            </div>
        </div>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/voiceover.min.js') !!}
@endpush
