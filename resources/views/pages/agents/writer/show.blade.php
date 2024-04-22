@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="presets.index" :name="__('Content presets')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="p-5 card mb-3" data-bs-toggle="collapse" x-show="showForm">
        <h3 class="fw-bolder h5">@lang('Prompts')</h3>
        <form is="x-form" x-ref="form" @submit.prevent="submit(null)" class="d-grid gap-3 mt-3">
            @foreach ($templates as $p)
                @php($id = 't' . rand())

                @if (isset($p->type->value) && $p->type->value == 'text')
                    <div class="mb-3">
                        <label @class(['form-label', 'required' => $p->required]) for="{{ $id }}">{{ __($p->label) }}</label>
                        @if ($p->multiline)
                            <textarea cols="5" id="{{ $id }}" name="{{ $p->name }}" placeholder="{{ $p->placeholder }}"
                                class="form-control" autocomplete="off" rows="3" @required($p->required)>{{ $p->value }}</textarea>
                        @else
                            <input type="text" id="{{ $id }}" name="{{ $p->name }}"
                                placeholder="{{ $p->placeholder }}" class="form-control" autocomplete="off"
                                value="{{ $p->value }}" @required($p->required)>
                        @endif
                        @if ($p->info)
                            <div class="mt-1 d-flex align-items-center text-sm text-muted">
                                <i class="ti ti-info-square-rounded-filled text-muted"></i>
                                <small class="ms-1 text-muted">{{ $p->info }}</small>
                            </div>
                        @endif
                    </div>
                @endif

                @if (isset($p->type->value) && $p->type->value == 'enum')
                    <div class="mb-3">
                        <label @class(['form-label', 'required' => $p->required]) for="{{ $id }}">{{ __($p->label) }}</label>
                        <select id="{{ $id }}" name="{{ $p->name }}" class="form-select"
                            @if ($p->required) required @endif>
                            @foreach ($p->options as $o)
                                <option value="{{ $o->value }}" @if ($o->value == $p->value) selected @endif>
                                    {{ __($o->label) }}</option>
                            @endforeach
                        </select>
                        @if ($p->info)
                            <div class="mt-1 d-flex align-items-center text-sm text-muted">
                                <i class="ti ti-info-square-rounded-filled text-muted"></i>
                                <small class="ms-1 text-muted">{{ $p->info }}</small>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($p->type == 'tone')
                    <div class="mb-3">
                        <div class="form-label">@lang('Voice tone')</div>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            @foreach ($tones as $tone)
                                <div class="d-inline-block mb-2 me-1">
                                    <input type="radio" class="btn-check" value="{{ $tone }}" name="tone"
                                        class="btn btn-outline-secondary fw-normal py-0" id="tone{{ $loop->iteration }}"
                                        autocomplete="off" />
                                    <label class="btn btn-outline-secondary fw-normal py-0"
                                        for="tone{{ $loop->iteration }}">{{ $tone }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="mb-3">
                <div class="form-label">@lang('Creativity level')</div>
                <div class="buttons-container">
                    @foreach ($creativities as $value => $creativity)
                        <div class="d-inline-block mb-2 me-1">
                            <input type="radio" class="btn-check" value="{{ $value }}" name="creativity"
                                id="creativity{{ $loop->iteration }}" autocomplete="off">
                            <label class="btn btn-outline-secondary fw-normal py-0"
                                for="creativity{{ $loop->iteration }}">{{ $creativity }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <x-button type="submit" class="w-100">
                    <i class="ti ti-sparkles h5 mb-0"></i>
                    <span class="ms-2">@lang('Generate')</span>
                </x-button>
            </div>
        </form>
    </section>

    <template x-if="docs.length > 0 && docs[index]">
        <div class="p-5 card relative">

            <template x-if="docs.length > 1">
                <div class="d-flex align-items-center gap-1 text-sm mb-3">
                    <button type="button" :disabled="index == 0" @click="index--" ;
                        class="btn btn-white p-0 d-flex align-items-center">
                        <i class="text-xs ti ti-chevron-left text-muted text-sm"></i>
                    </button>

                    <small class="text-muted">
                        <span x-text="index+1"></span>
                        <span>/</span>
                        <span x-text="docs.length"></span>
                    </small>

                    <button type="button" :disabled="index + 1 >= docs.length" @click="index++" ;
                        class="btn btn-white p-0 d-flex align-items-center">
                        <i class="text-xs ti ti-chevron-right text-muted text-sm"></i>
                    </button>
                </div>
            </template>

            <div class="row d-flex align-items-center">
                <template x-if="docs[index].uuid">
                    <div class="col-lg-10">
                        <div class="h4 autogrow-textarea mb-0" :data-replicated-value="docs[index].title">
                            <textarea placeholder="{{ __('Untitled document') }}" autocomplete="off" x-model="docs[index].title" rows="1"
                                class="d-block w-100 p-0 text-body border-0"></textarea>
                        </div>
                    </div>
                </template>

                <template x-if="!docs[index].uuid">
                    <div class="col-lg-10 placeholder-wave">
                        <div class="h2 placeholder rounded col-9"></div>
                    </div>
                </template>

                <div class="col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="button"class="btn btn-white p-0 me-3" @click="showForm = !showForm"
                        :class="{ 'text-content-dimmed': showForm }" x-tooltip.raw="{{ __('Toggle form') }}">
                        <i class="ti ti-section h5"></i>
                    </button>

                    <button class="btn btn-white p-0" type="button" @click="submit(docs[index].params)"
                        x-tooltip.raw="{{ __('Regenerate') }}" :disabled="isProcessing">
                        <i class="ti ti-rotate h5"></i>
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

            <template x-if="docs[index].uuid" data-think="docs[index].uuid">

                <div class="d-flex justify-content-between mt-5">
                    <div class="d-flex align-items-center mr-auto">
                        <template x-if="docs[index].cost > 0">
                            <span class="d-flex align-items-center text-sm text-muted">
                                <i class="text-base ti ti-coins me-2"></i>
                                <data is="x-credit" :value="docs[index].cost"
                                    format="{{ __(':count credits') }}"></data>
                            </span>
                        </template>

                        <div class="audience" data-tbd="true"></div>
                    </div>

                    <div class="d-flex align-items-center">
                        <button class="btn btn-white p-0 me-3" @click="copyDocumentContents(docs[index])">
                            <i class="ti ti-copy h5"></i>
                        </button>

                        <div class="dropdown me-3" @click.away="$refs.downloadOptions.classList.remove('show')">
                            <button type="button" class="btn btn-white p-0"
                                @click="$refs.downloadOptions.classList.toggle('show')" aria-expanded="false">
                                <i class="ti ti-download h5"></i>
                            </button>

                            <ul class="dropdown-menu" x-ref="downloadOptions" @click="$el.classList.remove('show')">
                                <li class="dropdown-item">
                                    <button class="w-full btn btn-white d-flex justify-content-center"
                                        @click="download(docs[index], 'word')">
                                        <i class="h5 ti ti-letter-w text-muted me-2 mb-0"></i>
                                        <span>{{ __('Word document') }}</span>
                                    </button>
                                </li>

                                <li class="dropdown-item">
                                    <button class="w-full btn btn-white d-flex justify-content-center"
                                        @click="download(docs[index], 'html')">
                                        <i class="h5 ti ti-brand-html5 text-muted me-2 mb-0"></i>
                                        <span>{{ __('HTML file') }}</span>
                                    </button>
                                </li>

                                <li class="dropdown-item">
                                    <button class="w-full btn btn-white d-flex justify-content-center"
                                        @click="download(docs[index], 'markdown')">
                                        <i class="h5 ti ti-markdown text-muted me-2 mb-0"></i>
                                        <span>{{ __('Markdown') }}</span>
                                    </button>
                                </li>

                                <li class="dropdown-item">
                                    <button class="w-full btn btn-white d-flex justify-content-center"
                                        @click="download(docs[index], 'text')">
                                        <i class="h5 ti ti-txt text-muted me-2 mb-0"></i>
                                        <span>{{ __('Text') }}</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <button class="btn btn-white p-0" @click="deleteDocument(docs[index])"
                            @hover="$refs.trashHover.classList.toggle('text-danger')">
                            <i class="ti ti-trash h5" x-ref="trashHover"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </template>
@endsection

@push('script-stack-after')
    {!! javascript('js/content.min.js', true) !!}
@endpush
