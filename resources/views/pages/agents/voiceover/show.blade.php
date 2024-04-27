@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="agent.voiceover.index" :name="__('Voices')" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="">
        <div class="card">
            <form is="x-form" x-ref="form" @submit.prevent="submit">
                <div class="d-flex w-100 align-items-end p-2">
                    <div class="grow-wrap w-100" :data-replicated-value="prompt">
                        <textarea name="prompt" id="prompt" tabindex="0" dir="auto" rows="1" autocomplete="off" x-ref="prompt"
                            placeholder="@lang('Enter your text here...')" x-model="prompt" required></textarea>
                    </div>
                    <div class="d-block">
                        <x-button>
                            <span class="visually-hidden">@lang('Generate')</span>
                            <i class="fs-4 ti ti-sparkles"></i>
                        </x-button>
                    </div>
                </div>
                <input type="hidden" id="uuid" name="uuid" value="{{ $voice->uuid }}">
            </form>
        </div>
    </section>
@endsection

@push('script-stack-after')
    {!! javascript('js/voiceover.min.js', true) !!}
@endpush
