@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="group/grid" data-state="initial" :data-state="state">
        <div class="row">
            <template x-for="(voice, index) in resources" :key="index">
                <div class="col-lg-4 d-flex align-items-stretch mb-3">
                    <div class="card card-item p-3 w-100 d-block">

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

                        <div class="action mt-4">
                            <a x-bind:href="`{{ route('agent.voiceover.show', '') }}/${voice.uuid}`"
                                class="btn btn-light w-100 d-block text-body">@lang('Use this voice')</a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>
@endsection

@push('script-stack-after')
    @if (session()->get('message'))
        <x-notification :message="session()->get('message')['content']" :show="true" :icon="session()->get('message')['type'] == 'success' ? 'ti-square-rounded-check-filled' : ''" />
    @endif

    {!! javascript('js/listing.min.js', true) !!}
@endpush
