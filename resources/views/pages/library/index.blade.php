@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>
    <section class="y">
        <div class="row">
            <div class="col-lg-4">
                <a class="card p-4" href="{{ route('library.agent.content') }}">
                    <div class="mb-3">
                        <div class="d-inline-block bg-warning bg-gradient rounded px-2 py-1">
                            <i class="h4 mb-0 text-white ti ti-file-text"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <h3 class="fw-bolder h5 mb-0">@lang('Content writer')</h3>
                    </div>
                    <div class="text-muted">
                        <p class="mb-0">@lang('Content created with the content writer agent')</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="card p-4" href="{{ route('library.agent.coder') }}">
                    <div class="mb-3">
                        <div class="d-inline-block bg-danger bg-gradient rounded px-2 py-1">
                            <i class="h5 mb-0 text-white ti ti-code"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <h3 class="fw-bolder h5 mb-0">@lang('Coder writer')</h3>
                    </div>
                    <div class="text-muted">
                        <p class="mb-0">@lang('Content created with the coder writer agent')</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection
