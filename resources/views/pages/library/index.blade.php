@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="group/list" data-state="initial" :data-state="state">
        <x-content.empty :title="__('No content yet')" :subtitle="__('You haven\'t create an AI-driven content yet')" />
        <div class="placeholder-wave">
            @for ($i = 0; $i < 3; $i++)
                <div class="card mb-2 p-3">
                    <div class="d-flex placeholder-wave justify-content-between align-items-center">
                        <div class="d-flex">
                            <div class="icon-md placeholder rounded"></div>
                        </div>
                        <div class="ms-2 w-100">
                            <div class="d-block placeholder col-6 rounded mb-2"></div>
                            <div class="placeholder col-2 rounded"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        <template x-for="(content, index) in resources" :key="index">
            <div class="card card-item mb-2 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-md bg-light text-body">
                                <span x-text="content.abbreviation"></span>
                            </div>
                        </div>
                        <div class="ms-2">
                            <div class="fw-bold mb-0" x-text="content.title"></div>
                            <small class="mt-2 small text-muted">
                                <time x-text="content.created_at"></time>
                            </small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge fs-6 fw-normal opacity-75 py-1" x-text="content.type" :class="`bg-${content.type}`"></span>
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
