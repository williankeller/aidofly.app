@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        @include('pages.presets.types.sections.nav')
    </section>

    <section class="group/list" data-state="initial" :data-state="state">
        <x-content.empty :title="__('No custom presets yet')" :subtitle="__('You haven\'t create a preset template yet.')">
            <a href="{{ route('presets.create') }}" class="btn btn-primary">
                <div class="d-flex align-items-center">
                    <i class="fs-4 ti ti-square-rounded-plus me-2"></i>
                    <span>@lang('Create a new preset')</span>
                </div>
            </a>
        </x-content.empty>
        @include('pages.presets.types.sections.placeholder')
        <div class="row">
            <div class="free-form col-lg-4 d-flex align-items-stretch mb-3">
                <a class="card mb-2 p-3 d-block w-100" href="{{ route('agent.writer.create') }}">
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
                <div class="col-lg-4 d-flex align-items-stretch mb-3">
                    <a class="card mb-2 p-3 w-100 d-block"
                        x-bind:href="`{{ route('presets.show', '') }}/${preset.uuid}`">
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
    @if (session()->get('message'))
        <x-notification :message="session()->get('message')['content']" :show="true" :icon="session()->get('message')['type'] == 'success' ? 'ti-square-rounded-check-filled' : ''" />
    @endif

    {!! javascript('js/listing.min.js', true) !!}
@endpush
