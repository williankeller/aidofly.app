@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
        <x-nav.back route="agent.writer.presets.index" />
    </x-nav.page-title>

    <section class="mt-5">
        <template x-if="!conversation">
            <div class="text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="icon icon-lg bg-gradient bg-info-subtle">
                        <i class="ti ti-messages text-info"></i>
                    </div>
                </div>
                <h4 class="fw-bolder mt-4">@lang('Chat')</h4>
                <p class="text-muted">@lang('Text with our defaut chatbot')</p>
            </div>
        </template>

        <template id="message-user-template">
            <div class="message-content d-flex justify-content-end mb-4">
                <div class="d-flex flex-column align-items-end">
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-2">
                            <span class="fw-bold ms-1">@lang('You')</span>
                        </div>
                        <div class="icon icon-sm bg-primary bg-gradient bg-opacity-10">
                            <div class="text-primary fw-bold small">{{ $authUser->initials }}</div>
                        </div>
                    </div>
                    <div class="me-4 px-4 py-3 rounded bg-light mw-lg-400px text-end" data-kt-element="message-text"></div>
                </div>
            </div>
        </template>

        <template id="message-ai-template">
            <div class="message-ai d-flex justify-content-start mb-4">
                <div class="d-flex flex-column align-items-start">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon icon-sm bg-gradient bg-info-subtle">
                            <i class="ti ti-sparkles text-info"></i>
                        </div>
                        <div class="ms-2">
                            <span class="fw-bold me-1">@lang('AI')</span>
                        </div>
                    </div>
                    <div class="ms-4 px-4 py-3 rounded bg-light text-start" data-kt-element="message-text">
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex align-items-center" style="height: 24px;">
                                <div class="spinner-grow spinner-grow-sm bg-primary"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="card-body">
            <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto">
                <div id="chat-container"></div>
            </div>
        </div>
    </section>
    <section class="mt-auto">
        <form data-element="form" x-ref="form" @submit.prevent="submit">
            <div class="position-relative search-content rounded d-flex align-items-center">
                <div class="input-wrapper rounded bg-white grow-wrap w-100 ps-3" :data-replicated-value="prompt">
                    <textarea name="prompt" id="prompt" tabindex="0" dir="auto" rows="1" autocomplete="off" x-ref="prompt"
                        placeholder="@lang('Message the AI bot')" x-model="prompt" required class="p-0 bg-white" @keydown.enter="enter"></textarea>
                </div>
                <div class="px-2 position-absolute end-0">
                    <x-button ::processing="isProcessing" ::disabled="!prompt">
                        <span class="visually-hidden">@lang('Generate')</span>
                        <i class="fs-4 ti ti-arrow-up"></i>
                    </x-button>
                </div>
            </div>
            <input type="hidden" id="reference" name="reference" value="">
        </form>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/chat.min.js') !!}
@endpush
