@extends('layouts.auth')
@section('content')
    <div class="d-flex justify-content-center align-items-center h-100">
        <section
            class="col-lg-5 col-xl-4 p-3 position-fixed start-0 top-0 h-screen overflow-y-hidden d-none d-lg-flex flex-column">
            <div class="d-lg-flex flex-column w-100 h-100 p-5 pb-0 bg-primary rounded-4">
                <h1 class="fw-bolder h3 text-white">{{ config('app.name') }}</h1>
                <div class="mt-5 pt-5">
                    <h2 class="ls-tight fw-bolder h1 text-white mb-3">@lang('Transform Your Brainstorm Ideas into Reality')</h2>
                    <p class="text-white text-opacity-75 lead">@lang('Welcome back to the threshold of innovation. Log in now to begin shaping the future with every word you generate!')</p>
                </div>

            </div>
        </section>
        
        <section
            class="col-12 col-md-9 col-lg-7 offset-lg-5 border-left-lg min-h-lg-screen d-flex flex-column justify-content-center py-lg-5 px-lg-5 position-relative">
            <div class="mx-auto px-3 py-md-5 px-md-0 py-5 col-12 col-sm-9 col-lg-9 col-xxl-6">
                <div class="mb-4">
                    <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="45" height="45" />
                    <h2 class="mt-3 ls-tight fw-bolder h3">
                        <span>@lang('Hey, Hello')</span>
                        <span>👋</span>
                    </h2>
                </div>
                <form data-element="form" x-ref="form" method="post" action="{{ route('auth.signin.authorize') }}">
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
                        <a href="{{ route('password.index') }}"
                            class="text-sm text-muted text-primary-hover text-underline">
                            <small>@lang('Forgot password?')</small>
                        </a>
                    </div>
                    <div>
                        <x-button class="w-100 btn-lg">
                            <span>@lang('Sign in')</span>
                        </x-button>
                    </div>
                </form>
                <div class="text-center mt-5 text-sm text-muted">
                    <span>@lang("Don't have an account?")</span>
                    <a href="{{ route('auth.signup.index') }}" class="fw-semibold">@lang('Sign up')</a>
                </div>
            </div>
        </section>
    </div>
    <div class="position-fixed end-0 top-0 px-3">
        @include('pages.auth.snippets.locale')
    </div>
@endsection

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
