@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
        <x-nav.back route="agent.voiceover.index" />
    </x-nav.page-title>
    <section class="card">
        <form data-element="form" x-ref="form" @submit.prevent="submit">
            <div class="d-flex w-100 align-items-end p-2">
                <div class="grow-wrap w-100" :data-replicated-value="prompt">
                    <textarea name="prompt" id="prompt" tabindex="0" dir="auto" rows="1" autocomplete="off" x-ref="prompt"
                        placeholder="@lang('Type the content you want to voiceover here...')" x-model="prompt" required class="p-2 bg-white"></textarea>
                </div>
                <div class="d-block">
                    <x-button>
                        <span class="visually-hidden">@lang('Generate')</span>
                        <i class="fs-4 ti ti-sparkles"></i>
                    </x-button>
                </div>
            </div>
            <input type="hidden" id="uuid" name="uuid" value="{{ $voice->uuid }}">
        </form>
    </section>
    <section class="mt-5">
        <template x-if="!preview && !isProcessing">
            <div class="text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="icon icon-lg bg-gradient bg-success bg-opacity-10">
                        <i class="ti ti-speakerphone text-success"></i>
                    </div>
                </div>
                <h4 class="fw-bolder mt-4">@lang('Voice over')</h4>
                <p>@lang('Transform written text into spoken words.')</p>
            </div>
        </template>
        <template x-if="isProcessing">
            <div class="placeholder-wave">
                <div class="d-block placeholder col-5 h4 rounded"></div>
                <div class="mt-2 mb-2 placeholder col-12 placeholder-lg rounded" style="height: 60px;"></div>
                <div class="d-flex justify-content-between">
                    <div class="col-6">
                        <div class="placeholder col-3 placeholder-sm rounded me-3"></div>
                        <div class="placeholder col-3 placeholder-sm rounded"></div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="placeholder col-4 placeholder-sm rounded"></div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="preview && !isProcessing">
            <div class="title ms-1 mb-2">
                <span class="fw-bolder h5" x-text="preview.title"></span>
            </div>
        </template>

        <template x-if="preview && !isProcessing">
            <div class="card p-2 voiceover">
                <component-wave :src="preview.fullPath" class="d-flex justify-content-between align-items-center"
                    x-ref="previewWave" @audioprocess="previewTime = $event.detail.time" state="initial">
                    <button type="button" play-pause
                        class="btn btn-primary btn-play-pause icon icon-md p-1 d-flex align-items-center">
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
        </template>

        <template x-if="preview && !isProcessing">
            <div class="d-flex justify-content-between mt-4">
                <div class="d-flex small">
                    <div class="d-flex align-items-center me-4">
                        <i class="ti ti-square-rounded-letter-t me-1"></i>
                        <span x-text="preview.tokens"></span>
                        <span class="ms-1">@lang('characters')</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-coins me-1"></i>
                        <span x-text="preview.cost"></span>
                        <span class="ms-1">@lang('credits')</span>
                    </div>
                </div>
                <div class="mt-2 small">
                    <a :href="`/library/voiceover/${preview.uuid}`" x-tooltip.raw="@lang('See in your library')"
                        class="d-flex align-items-center text-success toggle-on-hover">
                        <i class="fs-5 ti ti-books me-1 show"></i>
                        <i class="fs-5 ti ti-square-rounded-check me-1 hide"></i>
                        <span>@lang('Saved to library')</span>
                    </a>
                </div>
            </div>
        </template>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/voiceover.min.js') !!}
@endpush
