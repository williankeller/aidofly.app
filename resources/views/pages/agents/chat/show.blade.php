@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
        <x-nav.back route="agent.writer.presets.index" />
    </x-nav.page-title>
    <section class="position-relative d-flex flex-column align-items-center "
        :class="isNewChat ? 'justify-content-center' : ''" style="min-height: 50vh">
        <template id="message-user-template">
            @include('pages.agents.chat.snippet.user-template')
        </template>
        <template id="message-ai-template">
            @include('pages.agents.chat.snippet.ai-template')
        </template>
        <div class="formatted-content w-100" id="chat-container">
            <template x-if="isNewChat && !conversation">
                <div class="text-center my-auto">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="icon icon-lg bg-gradient bg-info-subtle">
                            <i class="ti ti-messages text-info"></i>
                        </div>
                    </div>
                    <h4 class="fw-bolder mt-4">@lang('Chat')</h4>
                    <p class="text-muted">@lang('Text with our defaut chatbot')</p>
                </div>
            </template>
            <template x-if="isLoadingConversation">
                <x-content.placeholder :count="2" :columns="true">
                    <div class="d-flex justify-content-end mb-4">
                        <div class="d-flex flex-column align-items-end col-6">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2">
                                    <span class="placeholder col-3"></span>
                                </div>
                                <div class="icon icon-sm placeholder">
                                    <div class="text-primary"></div>
                                </div>
                            </div>
                            <div class="me-4 px-4 py-3 rounded bg-light w-100 text-end">
                                <div class="placeholder rounded col-12"></div>
                                <div class="placeholder rounded col-6"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start mb-4">
                        <div class="d-flex flex-column align-items-start col-8">
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon icon-sm placeholder">
                                    <div class="text-primary"></div>
                                </div>
                                <div class="ms-2">
                                    <span class="placeholder col-3"></span>
                                </div>
                            </div>
                            <div class="ms-4 px-4 py-3 rounded bg-light w-100 text-start">
                                <div class="placeholder rounded col-12"></div>
                                <div class="placeholder rounded col-6"></div>
                            </div>
                        </div>
                    </div>
                </x-content.placeholder>
            </template>
        </div>
    </section>
    <section class="position-sticky bottom-0 w-100 py-4 bg-white bg-gradient rounded">
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
            <input type="hidden" id="reference" name="reference" value="{{ $uuid }}">
        </form>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/chat.min.js') !!}
@endpush
