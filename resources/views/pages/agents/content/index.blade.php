@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="group/list" data-state="initial" :data-state="state">
        <x-content.empty :title="__('There are no presets')" :subtitle="__('We do not have set ')" />
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
            <div class="free-form col-lg-4 d-flex align-items-stretch">
                <a class="card mb-2 p-3 d-block" href="">
                    <div class="d-inline-block">
                        <div class="bg-warning bg-gradient rounded p-2 d-flex align-items-center">
                            <i class="fs-4 text-white ti ti-file-text"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="fw-bolder mb-0 text-body h5">
                            <span>@lang('Free form AI')</span>
                        </div>
                        <small class="mt-2 text-sm text-muted d-block">@lang("Don't need a template? Start writing with our AI writer.")</small>
                    </div>
                </a>
            </div>
            <template x-for="preset in resources" :key="preset.uuid">
                <div class="col-lg-4 d-flex align-items-stretch">
                    <a class="card mb-2 p-3 d-block" x-bind:href="'/agent/content/' + preset.uuid">
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
    {!! javascript('js/base/list.min.js', true) !!}
@endpush
