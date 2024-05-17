@extends('layouts.app')

@section('content')
    <section class="my-4 my-lg-5">
        <small class="d-block mb-1 text-muted">@lang('Hello') 👋</small>
        <h3 class="fw-bolder">{{ $authUser->firstname }}</h3>
    </section>
    <section class="my-5">
        <div class="card p-4">
            <div class="icon-sm bg-gradient bg-warning">
                <i class="ti ti-coins"></i>
            </div>
            <div class="mt-3">
                <div class="d-flex d-flex align-items-baseline" :class="{ 'd-none': !usageFetched }">
                    <div class="h4 mb-0 fw-bolder" data-element="credit" :data-value="usage.total"></div>
                    <small class="ms-1 text-muted fw-bold h6 mb-0">/@lang('Unlimited')</small>
                </div>
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
            <div class="position-relative search-content rounded d-flex align-items-center dropdown">
                <div class="input-wrapper rounded bg-white" :class="{ 'has-value': hasValue }">
                    <input type="text" name="q" placeholder="@lang('Search your library or ask the AI')" aria-label="@lang('Search')"
                        autocomplete="off" class="w-100 border-0 bg-white" x-ref="input" @keyup.down="showResults = true">
                </div>
                <div class="px-2 icon position-absolute d-flex align-items-center">
                    <i class="fs-2 ti ti-robot" :class="{ 'd-none': isProcessing }"></i>
                    <div class="m-2 spinner-grow spinner-grow-sm bg-primary" :class="{ 'd-block': isProcessing }"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="px-2 actions position-absolute end-0">
                    <div class="shortcut d-none d-lg-flex">
                        <div class="keyboard" :class="{ 'd-none': hasValue }">
                            <div class="d-flex">
                                <kbd class="py-0 me-1 command-key">⌘</kbd>
                                <kbd class="py-0">K</kbd>
                            </div>
                        </div>
                        <kbd class="keyclose py-0" :class="{ 'd-none': hasValue }">esc</kbd>
                    </div>
                    <x-button class="submit px-2" ::class="{ 'd-block': hasValue }" :disabled="false">
                        <i class="fs-5 fs-md-4 ti ti-sparkles me-1"></i>
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
                            <div class="d-block d-lg-flex justify-content-between align-items-center">
                                <div class="d-block d-lg-flex align-items-center">
                                    <div class="icon d-none d-md-block">
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
                                    <div class="mx-0 mx-md-3">
                                        <div class="text-truncate fw-bold" x-text="item.title"></div>
                                        <div class="text-muted small" x-text="item.description"></div>
                                    </div>
                                </div>
                                <div class="mt-2 mt-md-0 badge bg-secondary text-capitalize" x-text="item.object"></div>
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
