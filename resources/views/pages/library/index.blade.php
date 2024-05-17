@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    <section data-state="initial" :data-state="state">
        <div class="mb-4">
            <div class="position-relative search-content rounded d-flex align-items-center" x-data="filter([], [])">
                <div class="input-wrapper rounded bg-white">
                    <input type="search" class="w-100 border-0 bg-white" placeholder="@lang('Search your library...')" autocomplete="off"
                        x-model="params.search" id="search" aria-label="@lang('Search')" />
                </div>
                <div class="px-2 icon position-absolute d-flex align-items-center">
                    <i class="fs-3 ti ti-search" :class="{ 'd-none': isLoading }"></i>
                    <div class="m-2 spinner-grow spinner-grow-sm" :class="{ 'd-block': isLoading }" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="px-2 actions position-absolute end-0">
                    <div class="shortcut d-none d-lg-flex">
                        <div class="keyboard">
                            <div class="d-flex">
                                <kbd class="py-0 me-1 command-key">⌘</kbd>
                                <kbd class="py-0">K</kbd>
                            </div>
                        </div>
                        <kbd class="keyclose py-0">esc</kbd>
                    </div>
                </div>
            </div>
            <div class="ms-1 d-flex justify-content-end mt-3">
                <template x-if="resources.length">
                    <div class="small text-muted d-flex">
                        <span>@lang('Showing')</span>
                        <span class="mx-1" x-text="resources.length"></span>
                        <span>@lang('of')</span>
                        <span class="mx-1" x-text="pagination.total"></span>
                        <span>@lang('items')</span>
                    </div>
                </template>
                <div class="placeholder-wave w-100 d-flex justify-content-end">
                    <div class="placeholder col-2 rounded"></div>
                </div>
            </div>
        </div>
        <x-content.empty :title="__('No content')" :subtitle="__('There is no content to show here')" />
        <x-content.placeholder :count="4">
            <div class="card card-item p-3 w-100 d-block mb-3">
                <div class="d-flex align-items-center">
                    <div class="icon me-2">
                        <div class="icon-md placeholder"></div>
                    </div>
                    <div class="d-block fs-5 placeholder col-10 rounded mb-0"></div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <div class="placeholder-sm placeholder col-3 rounded" style="height: 10px;"></div>
                    <div class="col-6 d-flex justify-content-end" style="height: 20px;">
                        <div class="placeholder col-2 rounded h-100 me-1"></div>
                        <div class="placeholder col-2 rounded h-100"></div>
                    </div>
                </div>
            </div>
        </x-content.placeholder>
        <div class="row">
            <template x-for="(content, index) in resources" :key="content.uuid">
                <div class="col-lg-6 d-flex align-items-stretch mb-3">
                    <div class="card card-item p-3 w-100 d-flex">
                        <div class="d-flex align-items-center flex-fill">
                            <div class="me-2 d-none d-md-block z-2">
                                <template x-if="content.resource">
                                    <a x-bind:href="`/library/${content.type}/${content.uuid}`" class="icon icon-md" :style="`background-color: ${content.resource.color}`"
                                        x-tooltip="content.resource.title">
                                        <template x-if="!content.resource.icon">
                                            <div class="text-white fw-bold" x-text="content.initials"></div>
                                        </template>
                                        <template x-if="content.resource.icon">
                                            <i :class="content.resource.icon"></i>
                                        </template>
                                    </a>
                                </template>
                                <template x-if="!content.resource">
                                    <a x-bind:href="`/library/${content.type}/${content.uuid}`" class="icon icon-md bg-light">
                                        <div class="fw-bold text-muted" x-text="content.initials"></div>
                                    </a>
                                </template>
                            </div>
                            <div class="fw-bold mb-0" x-text="content.title"></div>
                        </div>
                        <div class="mt-3 d-block d-md-flex justify-content-between align-items-center">
                            <div class="small d-flex text-muted">
                                <span class="me-1">@lang('Created')</span>
                                <time x-text="content.created_at"></time>
                            </div>
                            <div class="d-block mt-2 mt-lg-0 align-items-center justify-content-end">
                                <div class="badge fw-bold py-1 small me-1 text-capitalize bg-opacity-10" x-text="content.type"
                                    :class="`bg-${content.type}`"></div>
                                <div class="d-inline-block d-md-none">
                                    <template x-if="content.resource">
                                        <div class="badge fw-bold py-1 small"
                                            :style="`background-color: ${content.resource.color};`"
                                            x-text="content.resource.title"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <a x-bind:href="`/library/${content.type}/${content.uuid}`" class="stretched-link z-1"></a>
                    </div>
                </div>
            </template>
        </div>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/listing.min.js') !!}
@endpush

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
