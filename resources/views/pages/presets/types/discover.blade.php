@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        @include('pages.presets.types.sections.nav')
    </section>

    <section class="group/list" data-state="initial" :data-state="state">
        <x-content.empty :title="__('There are no presets')" :subtitle="__('We do not have set ')" />
        @include('pages.presets.types.sections.placeholder')
        <div class="row">
            <template x-for="(preset, index) in resources" :key="index">
                <div class="col-lg-4 d-flex align-items-stretch mb-3">
                    <a class="card mb-2 p-3 w-100 d-block" x-bind:href="`{{ route('presets.show', '') }}/${preset.uuid}`">
                        <div class="d-inline-block">
                            <div class="bg-gradient rounded p-2 d-flex align-items-center"
                                :style="{ backgroundColor: preset.color }">
                                <span class="fs-5 text-white"
                                    x-text="preset.title.match(/(\b\S)?/g).join('').slice(0, 2)"></span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="fw-bolder mb-0 text-body h5" x-text="preset.title"></div>
                            <small class="mt-2 text-sm text-muted d-block" x-text="preset.description"></small>
                        </div>
                    </a>
                </div>
            </template>
        </div>
    </section>
@endsection

@push('script-stack-after')
    {!! javascript('js/listing.min.js', true) !!}
@endpush
