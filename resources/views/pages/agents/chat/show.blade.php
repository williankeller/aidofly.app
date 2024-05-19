@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
        <x-nav.back route="agent.writer.presets.index" />
    </x-nav.page-title>
    <section class="position-relative d-flex flex-column align-items-center justify-content-center" style="min-height: 50vh">
        @if (!$library)
            <template x-if="!conversation">
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
        @endif
        <template id="message-user-template">
            @include('pages.agents.chat.snippet.user-template')
        </template>
        <template id="message-ai-template">
            @include('pages.agents.chat.snippet.ai-template')
        </template>
        <div class="formatted-content w-100" id="chat-container">
            @if ($library)
                @foreach ($conversation as $message)
                    @if ($message->role == 'user')
                        @include('pages.agents.chat.snippet.user-template')
                    @else
                        @include('pages.agents.chat.snippet.ai-template', ['message' => $message])
                    @endif
                @endforeach
            @endif
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
            <input type="hidden" id="reference" name="reference" value="{{ $library->uuid ?? '' }}">
        </form>
    </section>
@endsection

@push('script-stack-before')
    {!! javascript('js/chat.min.js') !!}
@endpush
