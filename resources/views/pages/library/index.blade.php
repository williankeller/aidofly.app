@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />

    <section data-state="initial" :data-state="state">
        <div class="mb-4">
            <div class="position-relative search-content d-flex align-items-center" x-data="filter([], [])">
                <div class="input-wrapper">
                    <input type="search" class="w-100 border-0" placeholder="@lang('Search...')" autocomplete="off"
                        x-model="params.search" id="search" aria-label="@lang('Search')" />
                </div>
                <div class="px-2 icon d-flex align-items-center">
                    <i class="fs-3 ti ti-search" :class="{ 'd-none': isLoading }"></i>
                    <div class="m-2 spinner-grow spinner-grow-sm" :class="{ 'd-block': isLoading }" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="px-2 actions">
                    <div class="shortcut d-none d-lg-flex">
                        <kbd class="keyboard py-0">⌘ K</kbd>
                        <kbd class="keyclose py-0">esc</kbd>
                    </div>
                </div>
            </div>
            <template x-if="resources.length">
                <div class="ms-1 d-flex justify-content-end mt-3">
                    <div class="small text-muted d-flex">
                        <span>@lang('Showing')</span>
                        <span class="mx-1" x-text="resources.length"></span>
                        <span>@lang('of')</span>
                        <span class="mx-1" x-text="pagination.total"></span>
                        <span>@lang('items')</span>
                    </div>
                </div>
            </template>
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
        <template x-for="(content, index) in resources" :key="content.uuid">
            <div class="card card-item p-3 w-100 d-block mb-3">
                <div class="d-flex align-items-center">
                    <div class="icon me-2 d-none d-md-block">
                        <template x-if="content.resource">
                            <div class="icon-md" :style="`background-color: ${content.resource.color}`">
                                <template x-if="!content.resource.icon">
                                    <div class="text-white fw-bold" x-text="content.abbreviation"></div>
                                </template>
                                <template x-if="content.resource.icon">
                                    <i :class="content.resource.icon"></i>
                                </template>
                            </div>
                        </template>
                        <template x-if="!content.resource">
                            <div class="icon-md bg-light">
                                <div class="fw-bold" x-text="content.abbreviation"></div>
                            </div>
                        </template>
                    </div>
                    <div class="fw-bold mb-0" x-text="content.title"></div>
                </div>
                <div class="mt-2 d-block d-md-flex justify-content-between">
                    <div class="small d-flex text-muted">
                        <span class="me-1">@lang('Created')</span>
                        <time x-text="content.created_at"></time>
                    </div>
                    <div class="d-block d-lg-flex mt-2 mt-lg-0 align-items-center justify-content-end">
                        <span class="badge fw-bold py-1 small me-1 text-capitalize bg-gradient" x-text="content.type"
                            :class="`bg-${content.type}`"></span>
                        <template x-if="content.resource">
                            <span class="badge fw-bold py-1 small" :style="`background-color: ${content.resource.color};`"
                                x-text="content.resource.title"></span>
                        </template>
                    </div>
                </div>
                <a x-bind:href="`/library/${content.type}/${content.uuid}`" class="stretched-link z-1"></a>
            </div>
        </template>

    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/listing.min.js') !!}
@endpush

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
