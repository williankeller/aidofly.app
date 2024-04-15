@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="__('Code writer')" :lead="__('Generate high quality code in seconds.')" />
    </section>

    <section class="p-5 card" data-bs-toggle="collapse" x-show="showForm">
        <h3 class="h5">Prompts</h3>
        <div id="responseAi"></div>
        <form is="x-form" x-ref="form" @submit.prevent="submit(null)" class="needs-validation d-grid gap-3 mt-3">
            <div class="mb-3">
                <label for="prompt" class="form-label required">Description</label>
                <textarea class="form-control" id="prompt" name="prompt"
                    placeholder="{{ __('Describe the task you want to accomplish.') }}" rows="3" autocomplete="off" required></textarea>
                <div class="mt-2 d-flex align-items-center text-sm text-muted">
                    <i class="ti ti-help-square-rounded-filled"></i>
                    <small class="ms-1 text-muted">The more details you provide, the better the result will be.</small>
                </div>
            </div>
            <div class="mb-4">
                <x-form.input-field type="text" :label="__('Language')" id="language" :placeholder="__('python, js, java, etc.')" required />
            </div>
            <div>
                <x-button type="submit" class="btn-primary" class="w-100 d-flex align-items-center justify-content-center"
                    reference="codeWriter">
                    <i class="ti ti-sparkles h5 mb-0"></i>
                    <span class="ms-2">Generate code</span>
                </x-button>
            </div>
        </form>
    </section>

    <template x-if="docs.length > 0 && docs[index]">
        <div class="p-5 card relative">
            <div class="flex flex-col gap-4 box" data-density="comfortable">
                <template x-if="docs.length > 1">
                    <div class="flex items-center gap-1 text-xs text-content-dimmed">
                        <button type="button" :disabled="index == 0" @click="index--" ; class="hover:text-content">
                            <i class="text-xs ti ti-chevron-left"></i>
                        </button>

                        <span>
                            <span x-text="index+1"></span>
                            <span>/</span>
                            <span x-text="docs.length"></span>
                        </span>

                        <button type="button" :disabled="index + 1 >= docs.length" @click="index++" ;
                            class="hover:text-content">
                            <i class="text-xs ti ti-chevron-right"></i>
                        </button>
                    </div>
                </template>

                <div class="flex items-start justify-between gap-4">
                    <template x-if="docs[index].id">
                        <div class="grow">
                            <div class="text-xl autogrow-textarea font-editor-heading"
                                :data-replicated-value="docs[index].title">
                                <textarea placeholder="{{ __('Untitled document') }}" autocomplete="off" x-model="docs[index].title" rows="1"
                                    @input.debounce.750ms="saveDocument(docs[index])"
                                    class="block w-full px-0 py-0 transition-colors bg-transparent border-0 appearance-none resize-none focus:ring-0 placeholder:text-content-dimmed placeholder:opacity-50 read-only:text-content-dimmed"></textarea>
                            </div>
                        </div>
                    </template>

                    <template x-if="!docs[index].id">
                        <div class="flex flex-col gap-3 placeholder-wave">
                            <div class="h5 placeholder rounded col-7"></div>

                            <div class="mb-1">
                                <div class="h6 placeholder rounded col-4"></div>
                            </div>
                        </div>
                    </template>

                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button"class="btn btn-light" @click="showForm = !showForm"
                            :class="{ 'text-content-dimmed': showForm }" x-tooltip.raw="{{ __('Toggle form') }}">
                            <i class="ti ti-section"></i>
                        </button>

                        <button class="btn btn-light" type="button" @click="submit(docs[index].params)"
                            x-tooltip.raw="{{ __('Regenerate') }}" :disabled="isProcessing">
                            <i class="ti ti-rotate"></i>
                        </button>

                    </div>
                </div>

                <hr>

                <template x-if="docs[index].content">
                    <div class="editor" x-html="format(docs[index].content)"></div>
                </template>

                <template x-if="!docs[index].content">
                    <div class="placeholder-wave my-1">
                        <div class="h6 placeholder rounded col-12"></div>
                        <div class="h6 placeholder rounded col-12"></div>
                        <div class="h6 placeholder rounded col-7"></div>
                    </div>
                </template>

                <template x-if="docs[index].id">
                    <div class="flex items-center gap-4 mt-4">
                        <div class="flex items-center gap-4 mr-auto">
                            <template x-if="docs[index].cost > 0">
                                <span class="flex items-center gap-1 text-sm text-content-dimmed">
                                    <i class="text-base ti ti-coins"></i>
                                    <data is="x-credit" :value="docs[index].cost"
                                        format="{{ __(':count credits') }}"></data>
                                </span>
                            </template>

                            {% include "snippets/audience.twig" with {ref: 'docs[index]'} %}
                        </div>

                        <div class="flex items-center gap-4">
                            <button @click="copyDocumentContents(docs[index])"
                                class="transition-all text-content-dimmed hover:text-content">
                                <i class="text-xl ti ti-copy"></i>
                            </button>

                            <div class="relative" @click.outside="$refs.downloadOptions.removeAttribute('data-open')">

                                <button @click="$refs.downloadOptions.toggleAttribute('data-open')"
                                    class="transition-all text-content-dimmed hover:text-content">
                                    <i class="text-xl ti ti-download"></i>
                                </button>

                                <div class="menu menu-tr" x-ref="downloadOptions"
                                    @click="$el.removeAttribute('data-open')">

                                    <ul class="text-sm">
                                        <li>
                                            <button
                                                class="flex items-center w-full gap-2 px-4 py-2 text-left hover:bg-intermediate"
                                                @click="download(docs[index], 'word')">
                                                <i class="text-lg text-content-dimmed ti ti-letter-w"></i>
                                                <span>{{ __('Word document') }}</span>
                                            </button>
                                        </li>

                                        <li>
                                            <button
                                                class="flex items-center w-full gap-2 px-4 py-2 text-left hover:bg-intermediate"
                                                @click="download(docs[index], 'html')">
                                                <i class="text-lg text-content-dimmed ti ti-brand-html5"></i>
                                                <span>{{ __('HTML file') }}</span>
                                            </button>
                                        </li>

                                        <li>
                                            <button
                                                class="flex items-center w-full gap-2 px-4 py-2 text-left hover:bg-intermediate"
                                                @click="download(docs[index], 'markdown')">
                                                <i class="text-lg text-content-dimmed ti ti-markdown"></i>
                                                <span>{{ __('Markdown') }}</span>
                                            </button>
                                        </li>

                                        <li>
                                            <button
                                                class="flex items-center w-full gap-2 px-4 py-2 text-left hover:bg-intermediate"
                                                @click="download(docs[index], 'text')">
                                                <i class="text-lg text-content-dimmed ti ti-txt"></i>
                                                <span>{{ __('Text') }}</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <button @click="deleteDocument(docs[index])"
                                class="transition-all text-content-dimmed hover:text-content">
                                <i class="text-xl ti ti-trash group-hover:text-failure"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>
@endsection

@push('script-stack-after')
    {!! javascript('js/agents/coder.min.js') !!}
@endpush
