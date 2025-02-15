@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />

    <form data-element="form" x-ref="form" action="{{ route('agent.writer.presets.store') }}" method="post">
        <section class="p-3 p-sm-5 card mb-3">
            <h3 class="fw-bolder h5">@lang('Details')</h3>
            <div class="d-grid gap-3 mt-3">
                <div class="mb-2">
                    <x-form.input-field id="title" :label="__('Title')" :placeholder="__('Include a title for the template')" required maxlength="128" />
                </div>
                <div class="mb-2">
                    <label class="form-label required" for="description">@lang('Description')</label>
                    <textarea class="form-control" id="description" name="description"
                        placeholder="{{ __('Include a description for the template') }}" rows="2" autocomplete="off" maxlength="255"
                        required>{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label required" for="category">@lang('Category')</label>
                    <select class="form-control form-select" id="category" name="category" required>
                        <option value="0" selected disabled>@lang('Select a category')</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->uuid }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="p-3 p-sm-5 card mb-3">
            <div class="d-flex align-items-center mb-2">
                <h3 class="fw-bolder h5 mb-0">@lang('Prompt')</h3>
                <div class="ms-3 docs">
                    <a href="{{ route('guide.presets') }}" target="_blank" class="btn btn-light btn-sm px-2 py-1">
                        <i class="ti ti-help"></i>
                        <span>@lang('Guide')</span>
                    </a>
                </div>
            </div>
            <div class="d-grid gap-3 mt-3">
                <div class="mb-2">
                    <label class="form-label required" for="template">@lang('AI Prompt')</label>
                    <textarea class="form-control" id="template" name="template"
                        placeholder="{{ __('Describe how the AI should behave given a prompt') }}" rows="5" autocomplete="off"
                        required>{{ old('template') }}</textarea>
                </div>
            </div>
        </section>

        <section class="p-3 p-sm-5 card mb-3">
            <h3 class="fw-bolder h5">@lang('Visibility')</h3>
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <div class="form-label mb-0">
                    <span>@lang('Status')</span>
                    <small class="d-block text-muted fw-normal">@lang('Make this template visible to the world')</small>
                </div>
                <div class="d-flex">
                    <div class="form-check-group me-2">
                        <input type="radio" class="btn-check" name="visibility" id="visibilityPublic" value="public"
                            @checked(old('visibility', 'public') === 'public') autocomplete="off">
                        <label class="btn btn-outline-secondary py-1 d-flex align-items-center" for="visibilityPublic">
                            <i class="fs-5 ti ti-world"></i>
                            <span class="fw-normal ms-1">@lang('Public')</span>
                        </label>
                    </div>
                    <div class="form-check-group">
                        <input type="radio" class="btn-check" name="visibility" id="visibilityPrivate" value="private"
                            @checked(old('visibility') === 'private') autocomplete="off">
                        <label class="btn btn-outline-secondary py-1 d-flex align-items-center" for="visibilityPrivate">
                            <i class="fs-5 ti ti-lock"></i>
                            <span class="fw-normal ms-1">@lang('Private')</span>
                        </label>
                    </div>
                </div>
            </div>
        </section>

        <section class="d-flex justify-content-end my-3 my-lg-4">
            <div role="none" tabindex="-1" class="d-none">
                @csrf
            </div>
            <x-button type="submit">
                <i class="fs-5 ti ti-square-rounded-plus"></i>
                <span class="ms-1">@lang('Create template')</span>
            </x-button>
        </section>
    </form>
@endsection

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
