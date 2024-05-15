@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />

    <section class="voiceover" data-state="initial" :data-state="state">
        <x-content.placeholder :count="3" :columns="true">
            <div class="col-lg-4 d-flex align-items-stretch">
                <div class="card mb-2 p-3 w-100 d-block">
                    <div class="d-block placeholder col-6 h4 rounded"></div>
                    <div class="d-block mt-3 placeholder col-2 placeholder-sm rounded"></div>
                    <div class="d-block mt-3 placeholder col-3 rounded"></div>
                    <div class="d-flex mt-4" style="height: 34px;">
                        <div class="placeholder col-5 placeholder-lg rounded"></div>
                        <div class="placeholder col-7 placeholder-lg rounded ms-2"></div>
                    </div>
                </div>
            </div>
        </x-content.placeholder>

        <div class="row">
            <template x-for="(voice, index) in resources" :key="index">
                <div class="col-lg-4 d-flex align-items-stretch mb-3">
                    <div class="card card-item p-4 w-100 d-block toggle-on-hover">
                        <div class="fw-bold text-body h5" x-text="voice.name"></div>
                        <div class="text-muted text-capitalize small">
                            <template x-if="voice.age">
                                <span x-text="voice.age"></span>
                            </template>
                            <template x-if="voice.accent">
                                <span x-text="voice.accent"></span>
                            </template>
                            <template x-if="voice.gender">
                                <span x-text="voice.gender"></span>
                            </template>
                        </div>
                        <div class="categories mt-2 d text-capitalize">
                            <template x-if="voice.tone">
                                <span class="badge text-bg-secondary me-1" x-text="voice.tone"></span>
                            </template>
                            <template x-if="voice.case">
                                <span class="badge text-bg-secondary me-1" x-text="voice.case"></span>
                            </template>
                        </div>
                        <component-wave :src="voice.sample" state="pause">
                            <div class="card-backdrop position-absolute top-0 start-0 p-4 w-100 h-100 rounded">
                                <button type="button" play-pause
                                    class="btn btn-light btn-try flex-fill text-body mb-2 p-1 w-100">
                                    <div class="play d-flex align-items-center justify-content-center fw-normal">
                                        <i class="ti ti-player-play me-1"></i>
                                        <span>@lang('Try')</span>
                                    </div>
                                    <div class="loading spinner-grow spinner-grow-sm m-auto my-1 bg-purple" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="wave" wave></div>
                                </button>
                                <a x-bind:href="`{{ route('agent.voiceover.show', '') }}/${voice.uuid}`"
                                    class="btn btn-light flex-fill text-body p-2 d-flex align-items-center justify-content-center">
                                    <i class="fs-6 ti ti-sparkles me-1"></i>
                                    <span>@lang('Use voice')</span>
                                </a>
                            </div>
                        </component-wave>
                    </div>
                </div>
            </template>
        </div>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/voiceover.min.js') !!}
@endpush
