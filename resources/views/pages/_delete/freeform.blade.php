@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="presets.index" :name="__('Preset templates')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="p-5 card mb-3" x-show="showForm">
        <h3 class="fw-bolder h5">@lang('Prompts')</h3>
        <form is="x-form" x-ref="form" @submit.prevent="submit(null)" class="d-grid gap-3 mt-3">
            <div class="mb-2">
                <label for="prompt" class="form-label required">@lang('Your query')</label>
                <textarea class="form-control" id="prompt" name="prompt" placeholder="@lang('Type here what do you want to create')" rows="3"
                    autocomplete="off" required></textarea>
                <div class="mt-1 d-flex align-items-center text-sm text-muted">
                    <i class="ti ti-info-square-rounded-filled"></i>
                    <small class="ms-1 text-muted">@lang('The more details you provide, the better the result will be.')</small>
                </div>
            </div>
            @include('pages.agents.writer.snippets.creativity-level')
            <x-button type="submit" class="w-100">
                <i class="ti ti-sparkles h5 mb-0"></i>
                <span class="ms-2">@lang('Generate')</span>
            </x-button>
        </form>
    </section>

    <template x-if="docs.length > 0 && docs[index]">
        <div class="p-5 card relative">

            @include('pages.agents.writer.snippets.pagination')

            @include('pages.agents.writer.snippets.textarea')

            <hr>

            <template x-if="docs[index].content">
                <div class="editor" x-html="format(docs[index].content)"></div>
            </template>

            <template x-if="!docs[index].content">
                <div class="placeholder-wave my-1">
                    <div class="h6 placeholder rounded col-12"></div>
                    <div class="h6 placeholder rounded col-12"></div>
                    <div class="h6 placeholder rounded col-7"></div>
                </div>
            </template>

            <template x-if="docs[index].uuid" data-think="docs[index].uuid">

                <div class="d-flex justify-content-between mt-5">
                    <div class="d-flex align-items-center mr-auto">
                        <template x-if="docs[index].cost > 0">
                            <span class="d-flex align-items-center text-sm text-muted">
                                <i class="text-base ti ti-coins me-2"></i>
                                <data is="x-credit" :value="docs[index].cost" format="{{ __(':count credits') }}"></data>
                            </span>
                        </template>
                    </div>

                    <div class="mt-2 d-flex align-items-center">
                        <button class="btn btn-white p-0 me-3" @click="copyDocumentContents(docs[index])">
                            <i class="ti ti-copy h5"></i>
                        </button>

                        <div class="small">
                            <a :href="`/library/writer/${docs[index].uuid}`"
                                class="d-flex align-items-center text-muted btn btn-light btn-sm toggle-on-hover">
                                <i class="fs-5 ti ti-books me-1 show"></i>
                                <i class="fs-5 ti ti-square-rounded-check me-1 hide"></i>
                                <span>@lang('Saved to library')</span>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
@endsection

@push('script-stack-after')
    {!! javascript('js/content.min.js', true) !!}
@endpush
