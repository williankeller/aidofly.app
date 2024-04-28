@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="library.agent.index" :name="__('Library')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
            <a class="btn btn-light d-flex align-items-center" href="{{ route('agent.writer.edit', $library->uuid) }}">
                <i class="ti ti-sparkles fs-4"></i>
                <span class="ms-1">@lang('Regenarate')</span>
            </a>
        </x-nav.page-title>
    </section>

    <section class="p-5 card mb-3">

        <div class="editor" x-html="format(library.content)"></div>

        <div class="d-flex justify-content-between mt-5">
            <div class="d-flex align-items-center mr-auto">
                <div class="d-flex">
                    <div class="d-flex align-items-center ms-1 me-4">
                        <i class="ti ti-brain me-1"></i>
                        <span>@lang(':model', ['model' => $library->model])</span>
                    </div>
                    <div class="d-flex align-items-center ms-1 me-4">
                        <i class="ti ti-square-rounded-letter-t me-1"></i>
                        <span>@lang(':tokens tokens', ['tokens' => $library->tokens])</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-coins me-1"></i>
                        <span>@lang(':credits credits', ['credits' => $library->cost])</span>
                    </div>
                </div>

                <div class="ms-4 audience">
                    <button class="btn btn-secondary btn-sm py-0 d-flex align-items-center">
                        @if ($library->visibility == 'public')
                            <i class="ti ti-world"></i>
                            <span>@lang('Public')</span>
                        @else
                            <i class="ti ti-lock"></i>
                            <span>@lang('Only me')</span>
                        @endif
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <button class="btn btn-white p-0 me-3" @click="copyDocumentContents(library.content)">
                    <i class="ti ti-copy fs-4"></i>
                </button>

                <div class="dropdown me-3" @click.away="$refs.downloadOptions.classList.remove('show')">
                    <button type="button" class="btn btn-white p-0" @click="$refs.downloadOptions.classList.toggle('show')"
                        aria-expanded="false">
                        <i class="ti ti-download fs-4"></i>
                    </button>

                    <ul class="dropdown-menu" x-ref="downloadOptions" @click="$el.classList.remove('show')">
                        <li class="dropdown-item">
                            <button class="w-full btn btn-white d-flex justify-content-center"
                                @click="download(library.content, 'word')">
                                <i class="fs-5 ti ti-letter-w me-2"></i>
                                <small>{{ __('Word document') }}</small>
                            </button>
                        </li>

                        <li class="dropdown-item">
                            <button class="w-full btn btn-white d-flex justify-content-center"
                                @click="download(library.content, 'html')">
                                <i class="fs-5 ti ti-brand-html5 me-2"></i>
                                <small>{{ __('HTML file') }}</small>
                            </button>
                        </li>

                        <li class="dropdown-item">
                            <button class="w-full btn btn-white d-flex justify-content-center"
                                @click="download(library.content, 'markdown')">
                                <i class="fs-5 ti ti-markdown me-2"></i>
                                <small>{{ __('Markdown') }}</small>
                            </button>
                        </li>

                        <li class="dropdown-item">
                            <button class="w-full btn btn-white d-flex justify-content-center"
                                @click="download(library.content, 'text')">
                                <i class="fs-5 ti ti-txt me-2"></i>
                                <small>{{ __('Text') }}</small>
                            </button>
                        </li>
                    </ul>
                </div>

                <button class="btn btn-white p-0" @click="deleteDocument(library.content)">
                    <i class="ti ti-trash fs-4 text-hover-danger" x-ref="trashHover"></i>
                </button>
            </div>
        </div>
    </section>
@endsection

@push('script-stack-after')
    {!! javascript('js/library.min.js') !!}
@endpush
