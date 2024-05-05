@extends('layouts.app')

@section('content')

    @include('pages.agents.writer.presets.snippets.nav')

    <section data-state="initial" :data-state="state">
        <x-content.empty :title="__('Nothing to discover')" :subtitle="__('There are no templates to discovery yet.')" icon="ti ti-world-search" />
        @include('pages.agents.writer.presets.snippets.placeholder')
        <div class="row">
            <template x-for="(preset, index) in resources" :key="index">
                <div class="col-lg-4 d-flex align-items-stretch mb-3">
                    <a class="card card-item p-3 w-100 d-block"
                        x-bind:href="`{{ route('agent.writer.presets.show', '') }}/${preset.uuid}`">
                        <div class="d-inline-block">
                            <div class="icon-md" :style="{ backgroundColor: preset.color }">
                                <span class="fs-5 text-white"
                                    x-text="preset.title.match(/(\b\S)?/g).join('').slice(0, 2)"></span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="fw-bold mb-0 text-body h5" x-text="preset.title"></div>
                            <small class="mt-2 text-sm text-muted d-block" x-text="preset.description"></small>
                        </div>
                    </a>
                </div>
            </template>
        </div>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/listing.min.js') !!}
@endpush
