@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="presets.user" :name="__('Your templates')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <form is="x-form" x-ref="form" action="{{ route('presets.update', $preset->uuid) }}" method="post">
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
                            <option value="{{ $category->uuid }}" @if ($preset->category->uuid === $category->uuid) selected @endif>
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
                        placeholder="{{ __('Describe how the AI should behave given a prompt') }}" rows="5" autocomplete="off"
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
                    <div class="form-check-group me-2">
                        <input type="radio" class="btn-check" name="visibility" id="visibilityPublic" value="public"
                            @checked($preset->visibility === 'public') autocomplete="off">
                        <label class="btn btn-secondary d-flex align-items-center" for="visibilityPublic">
                            <i class="fs-5 ti ti-world"></i>
                            <span class="fw-bold ms-1">@lang('Public')</span>
                        </label>
                    </div>
                    <div class="form-check-group">
                        <input type="radio" class="btn-check" name="visibility" id="visibilityPrivate" value="private"
                            @checked($preset->visibility === 'private') autocomplete="off">
                        <label class="btn btn-secondary d-flex align-items-center" for="visibilityPrivate">
                            <i class="fs-5 ti ti-lock"></i>
                            <span class="fw-bold ms-1">@lang('Private')</span>
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
                            value="active" @checked($preset->status)>
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

        <section class="d-flex justify-content-between my-5">
            <div role="none" tabindex="-1" class="d-none">
                @method('PUT')
                @csrf
            </div>
            <x-modal.trigger id="delete-preset-modal" variant="danger">
                <i class="fs-5 ti ti-trash"></i>
                <span class="ms-1">@lang('Delete template')</span>
            </x-modal.trigger>
            <x-button>
                <i class="fs-5 ti ti-device-floppy"></i>
                <span class="ms-2">@lang('Update template')</span>
            </x-button>
        </section>
    </form>

    <x-modal.modal id="delete-preset-modal" size="md">
        <form class="modal-content" method="post" action="{{ route('presets.destroy', $preset->uuid) }}">
            <div class="modal-body text-center">
                <div class="m-4 d-flex align-items-center justify-content-center">
                    <div
                        class="icon-lg bg-danger bg-gradient text-white rounded d-flex align-items-center justify-content-center">
                        <i class="fs-2 ti ti-trash"></i>
                    </div>
                </div>
                <p>@lang('Are you sure you want to delete <strong>:title</strong> template?', ['title' => $preset->title])</p>
                <div role="none" tabindex="-1" class="d-none">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="uuid" value="{{ $preset->uuid }}">
                </div>
                <div class="mt-4 d-flex align-items-center justify-content-center">
                    <button type="button" class="btn btn-secondary m-2"
                        @click="modal.close()">@lang('No, cancel')</button>
                    <button type="submit" class="btn btn-danger">@lang('Yes, delete')</button>
                </div>
            </div>
        </form>
    </x-modal.modal>
@endsection

@push('script-stack-after')
    @if ($errors->any())
        <x-notification :message="$errors->first()" :show="true" />
    @endif

    @if (session()->get('message'))
        <x-notification :message="session()->get('message')['content']" :show="true" :icon="session()->get('message')['type'] == 'success' ? 'ti-square-rounded-check-filled' : ''" />
    @endif

    {!! javascript('js/auth.min.js', true) !!}
@endpush
