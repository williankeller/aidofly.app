@extends('layouts.auth')

@section('content')
    <section class="row g-0 justify-content-center gradient-bottom-right bg-primary">
        <div
            class="col-md-5 col-lg-5 col-xl-5 position-fixed start-0 top-0 vh-100 overflow-y-hidden d-none d-lg-flex flex-lg-column">
            <div class="p-5">
                <h1 class="fw-bolder h3 text-white">{{ config('app.name') }}</h1>
                <div class="mt-5 pt-5">
                    <h2 class="ls-tight fw-bolder h1 text-white mb-3">@lang('Transform Your Brainstorm Ideas into Reality')</h2>
                    <p class="text-white text-opacity-75 lead">@lang('Welcome back to the threshold of innovation. Log in now to begin shaping the future with every word you generate!')</p>
                </div>
            </div>
            @include('pages.auth.snippets.locale')
        </div>
        <div
            class="col-12 col-md-12 col-lg-7 offset-lg-5 min-vh-100 overflow-y-auto d-flex flex-column justify-content-center position-relative bg-body">
            <div class="w-md-50 mx-auto px-5 px-md-0 py-5">
                <div class="mb-4">
                    <x-image src="/img/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="45" height="45" />
                    <h2 class="mt-3 ls-tight fw-bolder h3">
                        <span>@lang('Hey, Hello')</span>
                        <span>👋</span>
                    </h2>
                </div>
                <form is="x-form" x-ref="form" method="post" action="{{ route('auth.signin.authorize') }}">
                    @csrf
                    <div class="mb-3">
                        <x-form.input-field type="email" :label="__('Email address')" id="email" :placeholder="__('Your email')" required
                            autocomplete="email" maxlength="255" />
                    </div>
                    <div class="mb-3">
                        <x-form.password-field :label="__('Password')" id="password" :placeholder="__('Your password')" required
                            autocomplete="current-password" minlength="6" maxlength="255" />
                    </div>
                    <div class="d-flex justify-content-between gap-2 mb-4 align-items-center">
                        <div></div>
                        <a href="{{ route('auth.recover') }}" class="text-sm text-muted text-primary-hover text-underline">
                            <small>@lang('Forgot password?')</small>
                        </a>
                    </div>
                    <div>
                        <x-button variant="primary" class="w-100">
                            <span>@lang('Sign in')</span>
                        </x-button>
                    </div>
                </form>
                <div class="text-center mt-5 text-sm text-muted">
                    <span>@lang("Don't have an account?")</span>
                    <a href="{{ route('auth.signup.index') }}" class="fw-semibold">@lang('Sign up')</a>
                </div>
            </div>
        </div>
    </section>
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
