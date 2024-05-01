@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" />
        @include('pages.agents.writer.presets.snippets.nav')
    </section>

    <section class="group/list" data-state="initial" :data-state="state">
        @include('pages.agents.writer.presets.snippets.placeholder')
        <div class="row">
            <div class="col-lg-4 d-flex align-items-stretch card-static mb-3">
                <div class="card-wrapper-selected h-100 d-flex align-items-stretch">
                    <div class="card p-3 d-block w-100">
                        <div class="d-inline-block">
                            <div class="icon-md bg-warning">
                                <i class="ti ti-file-text"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="fw-bold mb-0 text-body h5">
                                <span>@lang('Free form AI')</span>
                            </div>
                            <small class="mt-2 text-sm text-muted d-block">@lang("Don't need a template? Start writing with our AI writer.")</small>
                        </div>
                        <a href="{{ route('agent.writer.create') }}" class="stretched-link z-1"></a>
                    </div>
                </div>
            </div>
            <template x-for="(preset, index) in resources" :key="index">
                <div class="col-lg-4 d-flex align-items-stretch mb-3">
                    <div class="card card-item p-3 w-100 d-block">
                        <div class="d-flex justify-content-between">
                            <div class="icon-md" :style="{ backgroundColor: preset.color }">
                                <i class="ti" :class="preset.icon"></i>
                            </div>
                            @if ($isAdmin)
                                <div x-show="!preset.status">
                                    <span class="badge text-bg-danger">@lang('Inactive')</span>
                                </div>
                                <a class="d-block z-3 text-muted btn btn-white btn-sm text-hover-primary p-0"
                                    x-bind:href="`/preset/${preset.uuid}/edit`">
                                    <i class="ti ti-pencil"></i>
                                    <small>@lang('Edit')</small>
                                </a>
                            @endif
                        </div>
                        <div class="mt-3">
                            <div class="fw-bold mb-0 text-body h5" x-text="preset.title"></div>
                            <small class="mt-2 text-sm text-muted d-block" x-text="preset.description"></small>
                        </div>
                        <div class="category mt-2">
                            <span class="badge text-bg-secondary" x-text="preset.category.title"></span>
                        </div>
                        <a x-bind:href="`{{ route('agent.writer.presets.show', '') }}/${preset.uuid}`"
                            class="stretched-link z-1"></a>
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
@endpush

@push('script-stack-before')
    {!! javascript('js/listing.min.js') !!}
@endpush
