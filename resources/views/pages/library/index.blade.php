@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>
    <section class="row">
        <div class="col-lg-4 d-flex align-items-stretch">
            <a class="card p-3" href="{{ route('library.agent.content') }}">
                <div class="mb-3">
                    <div class="d-inline-block">
                        <x-content.icon color="warning" icon="ti-file-text" size="4" />
                    </div>
                </div>
                <div class="mb-2">
                    <h3 class="fw-bolder h5 mb-0">@lang('Content writer')</h3>
                </div>
                <small class="text-muted d-block">@lang('Content created with the content writer agent')</small>
            </a>
        </div>
        <div class="col-lg-4">
            <a class="card p-3" href="{{ route('library.agent.coder') }}">
                <div class="mb-3">
                    <div class="d-inline-block">
                        <x-content.icon color="danger" icon="ti-code" size="4" />
                    </div>
                </div>
                <div class="mb-2">
                    <h3 class="fw-bolder h5 mb-0">@lang('Coder writer')</h3>
                </div>
                <small class="text-muted d-block">@lang('Content created with the coder writer agent')</small>
            </a>
        </div>
    </section>
@endsection
