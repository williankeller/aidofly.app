@extends('layouts.auth')

@section('content')
    <section class="row g-0 justify-content-center gradient-bottom-right bg-primary">
        <div
            class="col-12 col-md-12 col-lg-7 offset-lg-5 min-vh-100 overflow-y-auto d-flex flex-column justify-content-center position-relative bg-body">
            <div class="w-md-50 mx-auto px-5 px-md-0 py-5">
                <div class="mb-4">
                    <x-image src="/img/logo/aidofly.png" :alt="config('app.name')" width="45" height="45" />
                    <h2 class="mt-3 ls-tight fw-bolder h3">
                        <span>@lang('Begin your creative journey today')</span>
                        <span></span>
                    </h2>
                </div>
                <form is="x-form" x-ref="form" method="post" action="{{ route('auth.signup.store') }}">
                    @csrf
                    <div class="mb-3 row">
                        <div class="col-sm-6">
                            <x-form.label-input type="text" :label="__('First name')" id="firstname" :placeholder="__('Your first name')" required
                                autocomplete="given-name" />
                        </div>
                        <div class="col-sm-6">
                            <x-form.label-input type="text" :label="__('Last name')" id="lastname" :placeholder="__('Your last name')" required
                                autocomplete="family-name" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-form.label-input type="email" :label="__('Email address')" id="email" :placeholder="__('Your email')" required
                            autocomplete="email" />
                    </div>
                    <div class="mb-5">
                        <x-form.label-input type="password" :label="__('Password')" id="password" :placeholder="__('Your password')" required
                            autocomplete="current-password" />
                    </div>
                    <div>
                        <x-button variant="primary" reference="signup" class="w-100">
                            <span>@lang('Sign up')</span>
                        </x-button>
                    </div>
                </form>
                <div class="text-center mt-5 text-sm text-muted">
                    <span>@lang('Already have an account?')</span>
                    <a href="{{ route('auth.signin') }}" class="fw-semibold">@lang('Sign in')</a>
                </div>
            </div>
        </div>
        <div
            class="col-md-5 col-lg-5 col-xl-5 position-fixed start-0 top-0 vh-100 overflow-y-hidden d-none d-lg-flex flex-lg-column">
            <div class="p-5">
                <h1 class="fw-bolder h3 text-white">{{ config('app.name') }}</h1>
                <div class="mt-5 pt-5">
                    <h2 class="ls-tight fw-bolder h1 text-white mb-3">@lang('Sign up to harness the power of AI today')</h2>
                    <p class="text-white text-opacity-75 lead">@lang('Our AI-driven content creator is your new partner in creativity, ready to elevate your concepts with precision and flair.')</p>
                </div>
            </div>
            <div class="mt-auto ps-16 ps-xl-20">
                // LANG
            </div>
        </div>
    </section>
@endsection
