@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="group/list" data-state="initial" :data-state="state">
        <x-content.empty :title="__('There is no content yet')" :subtitle="__('You haven\'t create an AI-driven content yet')" />
        <div class="placeholders">
            @for ($i = 0; $i < 3; $i++)
                <div class="card mb-2 p-3">
                    <div class="d-flex placeholder-wave justify-content-between align-items-center">
                        <div class="d-flex">
                            <div class="placeholder rounded" style="width: 40px; height: 40px"></div>
                        </div>
                        <div class="ms-2 w-100">
                            <div class="d-block placeholder col-6 rounded"></div>
                            <div class="placeholder col-2 rounded"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        <template x-for="(content, index) in resources" :key="index">
            <div class="card mb-2 p-3">
                <a class="d-flex" x-bind:href="`/library/${content.type}/${content.uuid}`">
                    <div class="d-flex justify-content-between align-items-center">
                        <template x-if="!content.preset">
                            <div class="icon-sm bg-secondary text-white">
                                <span class="fs-5" x-text="content.title.match(/(\b\S)?/g).join('').slice(0, 2)"></span>
                            </div>
                        </template>
                    </div>
                    <div class="ms-2">
                        <div class="fw-bolder mb-0 text-body" x-text="content.title"></div>
                        <small class="mt-2 text-sm text-muted">
                            <time is="x-time" :datetime="content.created_at" data-type="date" class="text-sm "></time>
                        </small>
                    </div>
                </a>
            </div>
        </template>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/listing.min.js') !!}
@endpush
