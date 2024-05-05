@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />

    <section data-state="initial" :data-state="state">
        <x-content.empty :title="__('No content yet')" :subtitle="__('You haven\'t create an AI-driven content yet')" />
        <x-content.placeholder :count="4" :columns="true">
            <div class="col-lg-6 d-flex align-items-stretch mb-3">
                <div class="card card-item p-3 w-100 d-block">
                    <div>
                        <div class="d-block fs-5 placeholder col-10 rounded mb-0"></div>
                        <div class="d-block placeholder-sm placeholder mt-2 col-3 rounded"></div>
                        <div class="mt-3" style="height: 20px;">
                            <div class="placeholder col-2 rounded h-100"></div>
                            <div class="placeholder col-2 rounded h-100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </x-content.placeholder>
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
