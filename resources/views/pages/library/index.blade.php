@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />

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
        <div class="row">
            <template x-for="(content, index) in resources" :key="index">
                <div class="col-lg-6 d-flex align-items-stretch mb-3">
                    <div class="card card-item p-3 w-100 d-block">
                        <div>
                            <div class="fw-bold mb-0" x-text="content.title"></div>
                            <small class="mt-1 small text-muted">
                                <time x-text="content.created_at"></time>
                            </small>
                            <div class="mt-2 d-flex align-items-center">
                                <span class="badge fw-bold py-1 small me-1 text-capitalize" x-text="content.type"
                                    :class="`bg-${content.type}`"></span>
                                <template x-if="content.resource">
                                    <span class="badge fw-bold py-1 small"
                                        :style="`background-color: ${content.resource.color};`"
                                        x-text="content.resource.title"></span>
                                </template>
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
