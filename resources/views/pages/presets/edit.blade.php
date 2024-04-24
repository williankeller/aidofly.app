@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="presets.user" :name="__('Your templates')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <form is="x-form" x-ref="form" action="{{ route('presets.update', $preset->uuid) }}" method="post">
        @method('PUT')
        <section class="p-5 card mb-3">
            <h3 class="fw-bolder h5">@lang('Details')</h3>
            <div class="d-grid gap-3 mt-3">
                <div class="mb-2">
                    <x-form.input-field id="title" :label="__('Title')" :placeholder="__('Include a title for the template')" :value="$preset->title" required
                        maxlength="128" />
                </div>
                <div class="mb-2">
                    <label class="form-label required" for="description">@lang('Description')</label>
                    <textarea class="form-control" id="description" name="description"
                        placeholder="{{ __('Include a description for the template') }}" rows="2" autocomplete="off" maxlength="255"
                        required>{{ old('description', $preset->description) }}</textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label required" for="category">@lang('Category')</label>
                    <select class="form-control form-select" id="category" name="category" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->uuid }}" @if ($preset->uuid === $category->uuid) selected @endif>
                                {{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="p-5 card mb-3">
            <h3 class="fw-bolder h5">@lang('Prompt')</h3>
            <div class="d-grid gap-3 mt-3">
                <div class="mb-2">
                    <label class="form-label required" for="template">@lang('AI Prompt')</label>
                    <textarea class="form-control" id="template" name="template"
                        placeholder="{{ __('Describe how the AI should behave given a prompt') }}" rows="4" autocomplete="off"
                        required>{{ old('template', $preset->template) }}</textarea>
                </div>
            </div>
        </section>

        <section class="p-5 card mb-3">
            <h3 class="fw-bolder h5">@lang('Visibility')</h3>
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <div class="form-label mb-0">
                    <span>@lang('Status')</span>
                    <small class="d-block text-muted fw-normal">@lang('Make this template visible to the world')</small>
                </div>
                <div class="d-flex">
                    <div class="form-check me-4">
                        <input class="form-check-input mt-3" type="radio" name="visibility" id="visibilityPublic"
                            value="public" @checked($preset->visibility === 'public')>
                        <label class="form-check-label" for="visibilityPublic">
                            <span class="fw-bold">@lang('Public')</span>
                            <small class="d-block text-muted">@lang('World will see it')</small>
                        </label>
                    </div>
                    <div class="form-check mb-0">
                        <input class="form-check-input mt-3" type="radio" name="visibility" id="visibilityPrivate"
                            value="private" @checked($preset->visibility === 'private')>
                        <label class="form-check-label" for="visibilityPrivate">
                            <span class="fw-bold">@lang('Private')</span>
                            <small class="d-block text-muted">@lang('Only for you')</small>
                        </label>
                    </div>
                </div>
            </div>
        </section>

        @if (auth()->user()->isAdmin())
            <section class="p-5 card mb-3">
                <h3 class="fw-bolder h5">@lang('Admin')</h3>
                <div class="mt-3 d-flex justify-content-between align-items-center" x-data="{ status: {{ $preset->status ?? 'false' }} }">
                    <div class="form-label mb-0">
                        <span>@lang('Status')</span>
                        <small class="d-block text-muted fw-normal">@lang('Enable this template to make it available for use')</small>
                    </div>
                    <label class="form-check form-switch form-check-reverse mb-0" for="status" @click="status = !status">
                        <input class="form-check-input" type="checkbox" name="status" role="switch" id="status"
                            value="1" @checked($preset->status)>
                        <span class="form-check-label fw-bold"
                            :class="{ 'd-none': !status, 'd-block': status }">@lang('Active')</span>
                        <span class="form-check-label fw-bold"
                            :class="{ 'd-none': status, 'd-block': !status }">@lang('Inactive')</span>
                    </label>
                </div>
                <div class="row mt-5 d-flex justify-content-between">
                    <div class="col-lg-4">
                        <x-form.input-field id="icon" :label="__('Icon code')" :placeholder="__('Example: ti-arrow')" :value="$preset->icon"
                            maxlength="32" />
                    </div>
                    <div class="col-lg-4 d-flex justify-content-center">
                        <div>
                            <label class="form-label" for="color">@lang('Background color')</label>
                            <input type="color" class="form-control" id="color" name="color"
                                style="max-width: 100px; width: 100px;"
                                @if (old('color', $preset->color)) value="{{ old('color', $preset->color) }}" @endif
                                maxlength="7">
                        </div>
                    </div>
                    <div class="col-lg-3 d-flex justify-content-end" x-data="{ hightlight: false }">
                        <div>
                            <div class="form-label">@lang('Hightlight')</div>
                            <label class="form-check form-switch mb-0 mt-3" for="hightlight"
                                @click="hightlight = !hightlight">
                                <input class="form-check-input" type="checkbox" name="hightlight" role="switch"
                                    id="hightlight" value="1">
                                <span class="form-check-label"
                                    :class="{ 'd-none': !hightlight, 'd-block': hightlight }">@lang('Yes')</span>
                                <span class="form-check-label"
                                    :class="{ 'd-none': hightlight, 'd-block': !hightlight }">@lang('No')</span>
                            </label>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <div class="d-flex justify-content-between mt-4 mb-5">
            <button type="button" class="btn btn-danger" @click="modal.open('delete-preset-modal')">
                <i class="fs-5 ti ti-trash"></i>
                <span class="ms-2">@lang('Delete template')</span>
            </button>
            <x-button type="submit" disabled="isProcessing">
                @csrf
                <i class="fs-5 ti ti-device-floppy"></i>
                <span class="ms-2">@lang('Update template')</span>
            </x-button>
        </div>
    </form>
@endsection

@push('script-stack-before')
    <div is="x-modal" id="delete-preset-modal" class="modal fade show" tabindex="-1" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="{{ route('presets.destroy', $preset->uuid) }}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="uuid" value="{{ $preset->uuid }}">
                <div class="modal-body text-center">
                    <div class="m-4 d-flex align-items-center justify-content-center">
                        <div
                            class="icon-lg bg-danger bg-gradient text-white rounded d-flex align-items-center justify-content-center">
                            <i class="fs-2 ti ti-trash"></i>
                        </div>
                    </div>
                    <p>@lang('Are you sure you want to delete <strong>:title</strong> template?', ['title' => $preset->title])</p>
                    <div class="mt-4 d-flex align-items-center justify-content-center">
                        <button type="button" class="btn btn-secondary m-2"
                            @click="modal.close()">@lang('No, cancel')</button>
                        <button type="submit" class="btn btn-danger">@lang('Yes, delete')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal-backdrop fade show d-none"></div>
@endpush

@push('script-stack-after')
    @if ($errors->any())
        <x-notification :message="$errors->first()" :show="true" />
    @endif

    @if (session()->get('message'))
        <x-notification :message="session()->get('message')['content']" :show="true" :icon="session()->get('message')['type'] == 'success' ? 'ti-square-rounded-check-filled' : ''" />
    @endif

    {!! javascript('js/auth.min.js', true) !!}
@endpush
