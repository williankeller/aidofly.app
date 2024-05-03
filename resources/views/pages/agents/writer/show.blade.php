@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="agent.writer.presets.index" :name="__('Preset templates')" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="p-5 card mb-3" x-show="showForm">
        <h3 class="fw-bolder h5">@lang('Prompts')</h3>
        <form data-element="form" x-ref="form" @submit.prevent="submit(null)" class="d-grid gap-3 mt-3">
            @if ($templates)
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
                                            class="btn btn-outline-secondary fw-normal py-0"
                                            id="tone{{ $loop->iteration }}" autocomplete="off" />
                                        <label class="btn btn-outline-secondary fw-normal py-0"
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
                    <textarea class="form-control" id="prompt" name="prompt" placeholder="@lang('Type here what do you want to create')" rows="3"
                        autocomplete="off" required>{{ $query }}</textarea>
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
                            <textarea placeholder="@lang('Untitled document')" autocomplete="off" x-model="docs[index].title" rows="1"
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
                        :class="{ 'text-content-dimmed': showForm }" x-tooltip.raw="@lang('See prompt')">
                        <i class="ti ti-section h5"></i>
                    </button>
                    <button class="btn btn-white p-0" type="button" @click="submit(docs[index].params)"
                        x-tooltip.raw="@lang('Regenerate')" :disabled="isProcessing">
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
                                <data is="x-credit" :value="docs[index].cost" format="@lang(':count credits')"></data>
                            </span>
                        </template>
                    </div>

                    <div class="mt-2 d-flex align-items-center">
                        <button class="btn btn-white p-0 me-3 d-flex align-items-center"
                            @click="copyDocumentContents(docs[index])">
                            <i class="fs-5 ti ti-copy"></i>
                        </button>

                        <div class="small">
                            <a :href="`/library/writer/${docs[index].uuid}`"
                                class="d-flex align-items-center text-muted btn btn-light btn-sm toggle-on-hover">
                                <i class="fs-5 ti ti-books me-1 show"></i>
                                <i class="fs-5 ti ti-square-rounded-check me-1 hide"></i>
                                <span>@lang('Saved to library')</span>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
@endsection

@push('script-stack-before')
    {!! javascript('js/content.min.js') !!}
@endpush
