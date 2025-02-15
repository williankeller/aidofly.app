@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
        <x-nav.back route="agent.writer.presets.index" />
    </x-nav.page-title>

    <section class="p-3 p-sm-5 card mb-3" x-show="showForm" style="display: none;">
        <h3 class="fw-bolder h5">@lang('Prompts')</h3>
        <form data-element="form" x-ref="form" @submit.prevent="submit(null)" class="d-grid gap-3 mt-3">
            @if ($templates)
                @foreach ($templates as $p)
                    @php($id = 't' . rand())

                    @if (isset($p->type->value) && $p->type->value == 'text')
                        <div class="mb-3">
                            <label @class(['form-label', 'required' => $p->required]) for="{{ $id }}">{{ __($p->label) }}</label>
                            @if ($p->multiline)
                                <div class="w-100">
                                    <textarea id="{{ $id }}" name="{{ $p->name }}" placeholder="{{ __($p->placeholder) }}" class="form-control"
                                        tabindex="0" dir="auto" rows="4" autocomplete="off" @required($p->required)>{{ $p->value }}</textarea>
                                </div>
                            @else
                                <input type="text" id="{{ $id }}" name="{{ $p->name }}"
                                    placeholder="{{ __($p->placeholder) }}" class="form-control" autocomplete="off"
                                    value="{{ $p->value }}" @required($p->required)>
                            @endif
                            @if ($p->info)
                                <div class="mt-1 d-flex align-items-center text-sm text-muted">
                                    <i class="ti ti-info-square-rounded-filled text-muted"></i>
                                    <small class="ms-1 text-muted">{{ __($p->info) }}</small>
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
                            <div class="row d-lg-flex">
                                @foreach ($tones as $tone)
                                    <div @class(['col-auto flex-fill mb-2 p-1'])>
                                        <input type="radio" class="btn-check" value="{{ $tone }}" name="tone"
                                            class="btn btn-outline-secondary fw-normal py-0"
                                            id="tone{{ $loop->iteration }}" autocomplete="off" />
                                        <label class="btn btn-outline-secondary fw-normal py-0 w-100"
                                            for="tone{{ $loop->iteration }}">{{ $tone }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="mb-2">
                    <label for="prompt" class="form-label required">@lang('Your query')</label>
                    <div class="w-100">
                        <textarea class="form-control" name="prompt" id="prompt" tabindex="0" dir="auto" rows="4"
                            autocomplete="off" placeholder="@lang('Type your content here...')" required>{{ $prompt ?? '' }}</textarea>
                    </div>
                    <div class="mt-1 d-flex align-items-center text-sm text-muted">
                        <i class="ti ti-info-square-rounded-filled"></i>
                        <small class="ms-1 text-muted">@lang('The more details you provide, the better the result will be.')</small>
                    </div>
                </div>
            @endif

            <div class="submit-button">
                <x-button class="w-100">
                    <i class="fs-4 ti ti-sparkles"></i>
                    <span class="ms-2">@lang('Generate')</span>
                </x-button>
            </div>
        </form>
    </section>
    <template x-if="docs.length > 0 && docs[index]">
        <div class="p-3 p-sm-5 card relative">
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
                    <div class="col-lg-11">
                        <div class="h4 grow-wrap mb-0">
                            <textarea id="title" name="title" aria-label="@lang('Title')" placeholder="@lang('Untitled document')"
                                autocomplete="off" x-model="typing(docs[index].title)" rows="1"
                                class="d-block w-100 p-0 text-body border-0 bg-white"></textarea>
                        </div>
                    </div>
                </template>
                <template x-if="!docs[index].uuid">
                    <div class="col-lg-11 placeholder-wave">
                        <div class="h2 placeholder rounded col-9"></div>
                    </div>
                </template>
                <div class="col-lg-1 d-flex align-items-center mt-3 mt-lg-0 justify-content-center justify-md-content-end">
                    <button type="button"class="btn btn-white p-0" @click="showForm = !showForm"
                        :class="{ 'text-content-dimmed': showForm }" x-tooltip.raw="@lang('See prompt')">
                        <i class="ti ti-section h5"></i>
                    </button>
                </div>
            </div>
            <hr>
            <template x-if="docs[index].content">
                <div class="formatted-content" x-html="format(docs[index].content)"></div>
            </template>

            <template x-if="!docs[index].content">
                <div class="placeholder-wave my-1">
                    <div class="h6 placeholder rounded col-12"></div>
                    <div class="h6 placeholder rounded col-12"></div>
                    <div class="h6 placeholder rounded col-7"></div>
                </div>
            </template>
            <hr class="mt-4" />
            <template x-if="!docs[index].uuid">
                <div class="placeholder-wave d-flex justify-content-between align-items-center mt-3">
                    <div class="col-6">
                        <div class="h6 placeholder rounded col-3 me-2"></div>
                        <div class="h6 placeholder rounded col-3 me-2"></div>
                        <div class="h6 placeholder rounded col-2"></div>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <div class="h6 placeholder rounded col-1 me-3"></div>
                        <div class="h6 placeholder rounded col-4"></div>
                    </div>
                </div>
            </template>
            <template x-if="docs[index].uuid" data-think="docs[index].uuid">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="d-block d-md-flex align-items-center mr-auto">
                        <div class="d-flex align-items-center small text-muted me-3 mt-2 mt-md-0">
                            <i class="ti ti-coins me-1"></i>
                            <div x-text="docs[index].cost"></div>
                            <span class="ms-1">@lang('credits')</span>
                        </div>
                        <div class="d-flex align-items-center small text-muted me-3 mt-2 mt-md-0">
                            <i class="ti ti-square-rounded-letter-t me-1"></i>
                            <div x-text="docs[index].tokens"></div>
                            <span class="ms-1">@lang('tokens')</span>
                        </div>
                        <div class="d-flex align-items-center small text-muted mt-2 mt-md-0">
                            <i class="ti ti-brain me-1"></i>
                            <div x-text="docs[index].model"></div>
                        </div>
                    </div>
                    <div class="d-block d-md-flex align-items-center">
                        <button class="btn btn-white p-0 me-3 d-flex align-items-center"
                            x-tooltip.raw="@lang('Copy to clipboard')" @click="copyDocumentContents(docs[index])">
                            <i class="fs-5 ti ti-copy"></i>
                            <span class="ms-1 d-block d-md-none small">@lang('Copy')</span>
                        </button>
                        <div class="mt-3 mt-md-0 small">
                            <a :href="`/library/writer/${docs[index].uuid}`" x-tooltip.raw="@lang('See in your library')"
                                class="d-flex align-items-center text-success toggle-on-hover">
                                <i class="fs-5 ti ti-books me-1 show"></i>
                                <i class="fs-5 ti ti-square-rounded-check me-1 hide"></i>
                                <span>@lang('Saved to library')</span>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="!showForm">
                <button class="btn btn-primary d-block mt-5 d-flex align-items-center justify-content-center"
                    type="button" @click="submit(docs[index].params)" x-tooltip.raw="@lang('Generate a new version')"
                    :disabled="isProcessing">
                    <i class="fs-4 ti ti-sparkles"></i>
                    <span class="ms-2">@lang('Regenerate')</span>
                </button>
            </template>
        </div>
    </template>
@endsection

@push('script-stack-before')
    {!! javascript('js/content.min.js') !!}
@endpush
