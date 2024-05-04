@extends('layouts.app')

@section('content')
    <section class="my-4 my-lg-5 row d-flex justify-content-between align-items-center">
        <div class="text-center text-lg-start col-lg-9 col-sm-12">
            <h2 class="page-heading mb-1">{{ $metaTitle }}</h2>
            <div class="d-flex align-items-center mt-2 justify-content-center justify-content-lg-start text-muted">
                <div class="d-flex align-items-center me-4">
                    <i class="ti ti-coins me-1"></i>
                    <span>@lang(':credits credits', ['credits' => $library->cost])</span>
                </div>
                <div class="d-flex align-items-center me-4">
                    <i class="ti ti-square-rounded-letter-t me-1"></i>
                    <span>@lang(':tokens tokens', ['tokens' => $library->tokens])</span>
                </div>
                <div class="d-flex align-items-center me-4">
                    <i class="ti ti-brain me-1"></i>
                    <span>@lang(':model', ['model' => $library->model])</span>
                </div>
            </div>
        </div>

        <div class="d-flex col-lg-3 col-sm-12 mt-lg-0 mt-4 justify-content-center justify-content-lg-end">
            <a class="btn btn-light d-flex align-items-center" href="{{ route('agent.writer.show', $library->uuid) }}">
                <i class="ti ti-sparkles fs-5"></i>
                <span class="ms-1">@lang('Regenarate')</span>
            </a>
        </div>
    </section>

    <section class="p-3 p-sm-5 card mb-3">
        <div class="editor" x-html="format(library.content)"></div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="d-flex align-items-center mr-auto">
                <div class="audience">
                    <button class="btn btn-secondary btn-sm py-1 d-flex align-items-center">
                        @if ($library->visibility == 'public')
                            <i class="ti ti-world me-1"></i>
                            <span>@lang('Public')</span>
                        @else
                            <i class="ti ti-lock me-1"></i>
                            <span>@lang('Visible only to me')</span>
                        @endif
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <button class="btn btn-white p-0 me-3 d-flex align-items-center"
                    @click="copyDocumentContents(library.content)">
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
                                <small>@lang('Word document')</small>
                            </button>
                        </li>
                        <li class="dropdown-item">
                            <button class="w-full btn btn-white d-flex justify-content-center"
                                @click="download(library.content, 'html')">
                                <i class="fs-5 ti ti-brand-html5 me-2"></i>
                                <small>@lang('HTML file')</small>
                            </button>
                        </li>
                        <li class="dropdown-item">
                            <button class="w-full btn btn-white d-flex justify-content-center"
                                @click="download(library.content, 'markdown')">
                                <i class="fs-5 ti ti-markdown me-2"></i>
                                <small>@lang('Markdown')</small>
                            </button>
                        </li>
                        <li class="dropdown-item">
                            <button class="w-full btn btn-white d-flex justify-content-center"
                                @click="download(library.content, 'text')">
                                <i class="fs-5 ti ti-txt me-2"></i>
                                <small>@lang('Text')</small>
                            </button>
                        </li>
                    </ul>
                </div>
                <x-modal.trigger id="delete-library-modal" variant="white" class="p-0">
                    <i class="ti ti-trash fs-4 text-hover-danger" x-ref="trashHover"></i>
                </x-modal.trigger>
            </div>
        </div>
    </section>
@endsection

@push('script-stack-before')
    <x-modal.modal id="delete-library-modal" size="md">
        <form class="modal-content" method="post" action="{{ route('library.destroy', $library->uuid) }}">
            <div class="modal-body p-5 text-center">
                <h5 class="fw-bold mb-3">@lang('Delete libray item?')</h5>
                <p>@lang('You are about to delete the <strong>":title"</strong> content. Once deleted, it cannot be recovered.', ['title' => $library->title])</p>
            </div>
            <div role="none" tabindex="-1" class="d-none">
                @method('DELETE')
                @csrf
                <input type="hidden" name="uuid" value="{{ $library->uuid }}">
            </div>
            <div class="modal-footer flex-nowrap p-0">
                <button type="button" class="btn btn-link text-decoration-none col-6 py-3 m-0 rounded-0 border-end"
                    @click="modal.close()">@lang('No, cancel')</button>
                <button type="submit"
                    class="btn btn-link text-decoration-none col-6 py-3 m-0 rounded-0 text-danger fw-bold">@lang('Yes, delete')</button>
            </div>
        </form>
    </x-modal.modal>

    {!! javascript('js/library.min.js') !!}
@endpush
