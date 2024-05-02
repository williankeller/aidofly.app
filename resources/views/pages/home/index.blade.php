@extends('layouts.app')

@section('content')
    <section class="">
        <small class="d-block mb-1 text-muted">@lang('Welcome')</small>
        <h3 class="fw-bolder">{{ $authUser->firstname }}</h3>
    </section>
    <section class="my-5">
        <div class="card p-4">
            <div class="icon-sm bg-gradient bg-warning">
                <i class="ti ti-coins"></i>
            </div>
            <div class="mt-3 d-flex align-items-baseline">
                <template x-if="usageFetched">
                    <data class="h4 mb-0 fw-bolder" is="x-credit" :value="usage.total"></data>
                </template>
                <template x-if="usageFetched">
                    <small class="text-muted fw-bold h6 mb-0">/@lang('Unlimited')</small>
                </template>
                <div class="placeholder-wave w-100" :class="{ 'd-none': usageFetched }">
                    <div class="placeholder col-3 h4 mb-1 rounded"></div>
                </div>
            </div>
            <div class="text-muted small">@lang('Total credits used')</div>
        </div>
    </section>
    <section class="my-5">
        <form action="{{ route('agent.writer.create') }}" method="GET" x-data="search"
            @input.debounce.200="search($event.target.value)" class="position-relative"
            @click.outside="showResults = false">
            <div class="position-relative search-content d-flex align-items-center dropdown">
                <div class="input-wrapper">
                    <input type="text" name="q" placeholder="@lang('Search your library or ask AI directly...')" aria-label="@lang('Search')"
                        autocomplete="off" class="w-100 border-0" x-ref="input" @keyup.down="showResults = true">
                </div>
                <div class="px-2 icon d-flex align-items-center">
                    <i class="fs-2 ti ti-robot" :class="{ 'd-none': isProcessing }"></i>
                    <div class="m-2 spinner-grow spinner-grow-sm" :class="{ 'd-block': isProcessing }" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="px-3 actions">
                    <kbd class="keyboard" :class="{ 'd-none': hasValue }">⌘ K</kbd>
                    <kbd class="cancel" :class="{ 'd-none': hasValue }">esc</kbd>
                    <x-button class="submit" ::class="{ 'd-block': hasValue }" :disabled="false">
                        <i class="fs-4 ti ti-sparkles me-1"></i>
                        <span>@lang('Generate')</span>
                    </x-button>
                </div>
            </div>

            <template x-if="showResults && results.length > 0">
                <div class="dropdown-menu p-3 w-100 show">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="fw-bold">@lang('Search Results')</div>
                        <div class="text-muted small">
                            <span x-text="results.length"></span>
                            <span>@lang('results found')</span>
                        </div>
                    </div>
                    <template x-for="item in results" :key="item.uuid">
                        <div class="card card-item p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon">
                                        <template x-if="item.icon">
                                            <div class="icon-md" :style="{ backgroundColor: item.color }">
                                                <i :class="item.icon" class="ti"></i>
                                            </div>
                                        </template>
                                        <template x-if="!item.icon">
                                            <div class="icon-md bg-light">
                                                <span class="fw-bold text-uppercase"
                                                    x-text="item.title.match(/(\b\S)?/g).join('').slice(0, 2)"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="mx-3">
                                        <div class="text-truncate fw-bold" x-text="item.title"></div>
                                        <div class="text-muted small" x-text="item.description"></div>
                                    </div>
                                </div>
                                <div class="badge bg-secondary text-capitalize" x-text="item.object"></div>
                            </div>
                            <a :href="`${item.url}`" class="stretched-link z-1"></a>
                        </div>
                    </template>
                </div>
            </template>
        </form>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/home.min.js') !!}
@endpush
