@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        @include('pages.presets.types.sections.nav')
    </section>

    <section data-state="initial" :data-state="state">
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
                <a class="card mb-2 p-3 d-block w-100" href="{{ route('presets.create') }}">
                    <div class="d-inline-block">
                        <div class="card-icon bg-primary bg-gradient rounded p-2 d-flex align-items-center">
                            <i class="fs-4 text-white ti ti-square-rounded-plus"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="fw-bolder mb-0 text-body h5">
                            <span>@lang('Create your own')</span>
                        </div>
                        <small class="mt-2 text-sm text-muted d-block">@lang('Create your own template to use with the AI writer.')</small>
                    </div>
                </a>
            </div>
            <template x-for="(preset, index) in resources" :key="index">
                <div class="col-lg-4 d-flex align-items-stretch mb-3">
                    <div class="card mb-2 p-3 w-100 d-block">
                        <div class="d-flex justify-content-between">
                            <div class="card-icon bg-gradient rounded p-2 d-flex align-items-center"
                                :style="{ backgroundColor: preset.color }">
                                <span class="fs-5 text-white"
                                    x-text="preset.title.match(/(\b\S)?/g).join('').slice(0, 2)"></span>
                            </div>
                            <div x-show="!preset.status">
                                <span class="badge text-bg-danger rounded-pill">@lang('Inactive')</span>
                            </div>

                            <a class="d-block z-3 text-muted btn btn-white btn-sm text-hover-primary p-0" x-bind:href="`/preset/${preset.uuid}/edit`">
                                <i class="ti ti-pencil"></i>
                                <small>@lang('Edit')</small>
                            </a>

                        </div>
                        <div class="mt-3">
                            <div class="fw-bolder mb-0 text-body h5" x-text="preset.title"></div>
                            <small class="mt-2 text-sm text-muted d-block" x-text="preset.description"></small>
                        </div>
                        <div class="category mt-2">
                            <span class="badge text-bg-secondary rounded-pill" x-text="preset.category.title"></span>
                        </div>
                        <a x-bind:href="`{{ route('presets.show', '') }}/${preset.uuid}`" class="stretched-link z-1"></a>
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
