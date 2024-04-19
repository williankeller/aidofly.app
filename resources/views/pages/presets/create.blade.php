@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="presets.index" :name="__('Templates')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <form is="x-form" x-ref="form" @submit.prevent="submit(null)">
        <section class="p-5 card mb-3">
            <h3 class="fw-bolder h5">@lang('Details')</h3>
            <div class="d-grid gap-3 mt-3">
                <div class="mb-2">
                    <x-form.input-field id="title" :label="__('Title')" :placeholder="__('Include a title for the template')" required />
                </div>
                <div class="mb-2">
                    <label class="form-label required" for="description">@lang('Description')</label>
                    <textarea class="form-control" id="description" name="description"
                        placeholder="{{ __('Include a description for the template') }}" rows="2" autocomplete="off" required></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-between align-items-center" x-data="{ status: true }">
                <div class="form-label mb-0">
                    <span>@lang('Status')</span>
                    <small class="d-block text-muted fw-normal">@lang('Enable this template to make it available for use')</small>
                </div>
                <label class="form-check form-switch form-check-reverse" for="enabled" @click="status = !status">
                    <input class="form-check-input" type="checkbox" role="switch" id="enabled" checked>
                    <span class="form-check-label me-1"
                        :class="{ 'd-none': !status, 'd-block': status }">@lang('Active')</span>
                    <span class="form-check-label me-1"
                        :class="{ 'd-none': status, 'd-block': !status }">@lang('Inactive')</span>
                </label>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center" x-data="{ visibility: true }">
                <div class="form-label mb-0">
                    <span>{{ __('Visibility') }}</span>
                    <small class="d-block text-muted fw-normal">@lang('Make this template visible to the world')</small>
                </div>
                <label for="visibility" class="form-check form-switch form-check-reverse" @click="visibility = !visibility">
                    <input class="form-check-input" type="checkbox" role="switch" id="visibility">
                    <span class="form-check-label me-1"
                        :class="{ 'd-none': visibility, 'd-block': !visibility }">@lang('Private')</span>
                    <span class="form-check-label me-1"
                        :class="{ 'd-none': !visibility, 'd-block': visibility }"">@lang('Public')</span>
                </label>
            </div>
        </section>
        <section class="p-5 card mb-3">
            <h3 class="fw-bolder h5">@lang('Prompt')</h3>
            <div class="d-grid gap-3 mt-3">
                <div class="mb-2">
                    <label class="form-label required" for="description">@lang('AI Prompt')</label>
                    <textarea class="form-control" id="description" name="description"
                        placeholder="{{ __('Describe how the AI should behave given a prompt') }}" rows="4" autocomplete="off"
                        required></textarea>
                </div>
            </div>
        </section>
        <div class="d-flex justify-content-end mt-4 mb-5">
            <x-button type="submit">
                <i class="ti ti-sparkles h5 mb-0"></i>
                <span class="ms-2">@lang('Create template')</span>
            </x-button>
        </div>
    </form>
@endsection

@push('script-stack-after')
    {!! javascript('js/auth.min.js', true) !!}
@endpush
