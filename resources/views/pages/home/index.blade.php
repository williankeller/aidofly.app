@extends('layouts.app')

@section('content')
    <section class="">
        <small class="d-block mb-1 text-muted">@lang('Welcome')</small>
        <h3 class="fw-bolder">{{ $authUser->firstname }}</h3>
    </section>
    <section class="my-5">
        <form action="{{ route('agent.writer.create') }}" method="GET" x-data="search"
            @input.debounce.200="search($event.target.value)" class="position-relative" @click.outside="showResults = false">
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

                <div class="px-2 actions">
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
                        <div class="card p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon">
                                        <template x-if="item.icon">
                                            <div class="icon-sm" :style="{ backgroundColor: item.color }">
                                                <i :class="item.icon" class="ti "></i>
                                            </div>
                                        </template>
                                        <template x-if="!item.icon">
                                            <div class="icon-sm bg-light">
                                                <span class="fw-bold text-uppercase"
                                                    x-text="item.title.match(/(\b\S)?/g).join('').slice(0, 2)"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="ms-2">
                                        <div class="text-truncate" x-text="item.title"></div>
                                        <div class="text-muted" x-text="item.description"></div>
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
