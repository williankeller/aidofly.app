@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="library.agent.index" :name="__('Library')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
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
                <div class="d-flex">
                    <div class="d-flex align-items-center ms-1 me-4">
                        <i class="ti ti-square-rounded-letter-t me-1"></i>
                        <span>{{ __(':chars characters', ['chars' => mb_strlen($library->title)]) }}</span>
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
                            <span>{{ __('Public') }}</span>
                        @else
                            <i class="ti ti-lock"></i>
                            <span>{{ __('Only me') }}</span>
                        @endif
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown me-3" @click.away="$refs.downloadOptions.classList.remove('show')">
                    <button type="button" class="btn btn-white p-0" @click="$refs.downloadOptions.classList.toggle('show')"
                        aria-expanded="false">
                        <i class="ti ti-download fs-4"></i>
                    </button>

                    <ul class="dropdown-menu" x-ref="downloadOptions" @click="$el.classList.remove('show')">
                        <li class="dropdown-item">
                            <button class="w-full btn btn-white d-flex justify-content-center  p-1"
                                @click="download(library.content, 'word')">
                                <i class="fs-5 ti ti-download me-1"></i>
                                <span>{{ __('Download file') }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <button class="btn btn-white p-0" @click="deleteDocument(library.content)">
                    <i class="ti ti-trash fs-4 text-hover-danger" x-ref="trashHover"></i>
                </button>
            </div>
        </div>
    </section>
@endsection

@push('script-stack-after')
    {!! javascript('js/voiceover.min.js') !!}
@endpush
