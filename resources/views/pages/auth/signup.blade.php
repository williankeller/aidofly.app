@extends('layouts.auth')

@section('content')
    <div class="d-flex justify-content-center align-items-center h-100">
        <section
            class="col-lg-5 col-xl-4 p-3 position-fixed start-0 top-0 h-screen overflow-y-hidden d-none d-lg-flex flex-column">
            <div class="d-lg-flex flex-column w-100 h-100 p-5 pb-0 bg-primary rounded-4">
                <h1 class="fw-bolder h3 text-white">{{ config('app.name') }}</h1>
                <div class="mt-5 pt-5">
                    <h2 class="ls-tight fw-bolder h1 text-white mb-3">@lang('Sign up to harness the power of AI today')</h2>
                    <p class="text-white text-opacity-75 lead">@lang('Our AI-driven content creator is your new partner in creativity, ready to elevate your concepts with precision and flair.')</p>
                </div>
            </div>
        </section>
        <section
            class="col-12 col-md-9 col-lg-7 offset-lg-5 border-left-lg min-h-lg-screen d-flex flex-column justify-content-center py-lg-5 px-lg-5 position-relative">
            <div class="mx-auto px-5 px-md-0 py-5 col-12 col-sm-9 col-lg-9 col-xxl-6">
                <div class="mb-4">
                    <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="45"
                        height="45" />
                    <h2 class="mt-3 ls-tight fw-bolder h3">
                        <span>@lang('Begin your creative journey today')</span>
                        <span></span>
                    </h2>
                </div>
                <form is="x-form" x-ref="form" method="post" action="{{ route('auth.signup.store') }}">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <x-form.input-field :label="__('First name')" id="firstname" :placeholder="__('Your first name')" required
                                autocomplete="given-name" minlength="1" maxlength="32" />
                        </div>
                        <div class="mb-3 col-sm-6">
                            <x-form.input-field :label="__('Last name')" id="lastname" :placeholder="__('Your lastname')" required
                                autocomplete="family-name" minlength="2" maxlength="32" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-form.input-field type="email" :label="__('Email address')" id="email" :placeholder="__('Your email')" required
                            autocomplete="email" maxlength="255" />
                    </div>
                    <div class="mb-5">
                        <x-form.password-field type="password" :label="__('Password')" id="password" :placeholder="__('Your password')" required
                            autocomplete="current-password" minlength="6" maxlength="255" />
                    </div>
                    <div>
                        <x-button class="w-100 btn-lg">
                            <span>@lang('Sign up')</span>
                        </x-button>
                    </div>
                </form>
                <div class="text-center mt-5 text-sm text-muted">
                    <span>@lang('Already have an account?')</span>
                    <a href="{{ route('auth.signin') }}" class="fw-semibold">@lang('Sign in')</a>
                </div>
            </div>
        </section>
    </div>
    <div class="position-fixed end-0 top-0 px-3">
        @include('pages.auth.snippets.locale')
    </div>
@endsection

@push('script-stack-after')
    @if ($errors->any())
        <x-notification :message="$errors->first()" :show="true" />
    @endif
    @if (session()->get('message'))
        <x-notification :message="session()->get('message')['content']" :show="true" />
    @endif
@endpush

@push('script-stack-before')
    {!! javascript('js/auth.min.js') !!}
@endpush
