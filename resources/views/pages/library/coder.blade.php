@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="library.agent.index" :name="__('Library')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="group/list" data-state="initial" :data-state="state">
        <x-content.empty :title="__('There are no codes yet')" :subtitle="__('You haven\'t used the AI code assitent yet')" />
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
        <template x-for="content in resources" :key="content.uuid">
            <div class="card mb-2 p-3">
                <a class="d-flex" x-bind:href="'/agent/coder/' + content.uuid">
                    <div class="d-flex justify-content-between align-items-center">
                        <template x-if="!content.preset">
                            <div class="bg-secondary text-white bg-gradient rounded px-2 py-1 d-flex align-items-center">
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

@push('script-stack-after')
    {!! javascript('js/base/list.min.js') !!}
@endpush
