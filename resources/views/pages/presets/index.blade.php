@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
            <a class="btn btn-primary d-flex align-items-center btn-sm" href="{{ route('presets.create') }}">
                <span>@lang('Create template')</span>
            </a>
        </x-nav.page-title>
    </section>

    <section class="group/list" data-state="initial" :data-state="state">
        <x-content.empty :title="__('There are no custom presets')" :subtitle="__('You havn\'t created a preset yet.')" />
        <x-content.placeholder :count="4" :columns="true">
            <div class="col-lg-4 d-flex align-items-stretch">
                <div class="card mb-2 p-3 w-100">
                    <div class="d-inline-block">
                        <div class="placeholder rounded" style="width: 40px; height: 40px"></div>
                    </div>
                    <div class="mt-3">
                        <div class="d-block placeholder col-7 h5 mb-0 rounded"></div>
                        <div class="mt-2 placeholder col-12 placeholder-xs rounded"></div>
                        <div class="placeholder col-12 placeholder-xs rounded"></div>
                        <div class="placeholder col-6 placeholder-xs rounded"></div>
                    </div>
                </div>
            </div>
        </x-content.placeholder>
        <div class="row">
            <template x-for="preset in resources" :key="preset.uuid">
                <div class="col-lg-4 d-flex align-items-stretch mb-3">
                    <a class="card mb-2 p-3 d-block" x-bind:href="`{{ route('agent.writer.show', '') }}/${preset.uuid}`">
                        <div class="d-inline-block">
                            <div class="bg-gradient rounded p-2 d-flex align-items-center"
                                :style="{ backgroundColor: preset.color }">
                                <i class="fs-4 text-white ti" :class="preset.icon"></i>
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
