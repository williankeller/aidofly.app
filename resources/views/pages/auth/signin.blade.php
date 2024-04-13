@extends('layouts.auth')

@section('content')
    <section class="row g-0 justify-content-center gradient-bottom-right bg-primary">
        <div
            class="col-md-5 col-lg-5 col-xl-5 position-fixed start-0 top-0 vh-100 overflow-y-hidden d-none d-lg-flex flex-lg-column">
            <div class="p-5">
                <h1 class="fw-bolder h2 text-white">{{ config('app.name') }}</h1>
                <div class="mt-5 pt-5">
                    <h2 class="ls-tight fw-bolder h1 text-white mb-3">@lang('Transform Your Brainstorm Ideas into Reality')</h2>
                    <p class="text-white text-opacity-75 lead">@lang('Welcome back to the threshold of innovation. Log in now to begin shaping the future with every word you generate!')</p>
                </div>
            </div>
            <div class="mt-auto ps-16 ps-xl-20">
                <img src="../../img/marketing/shot-1.png" class="img-fluid rounded-top-start-4" alt="...">
            </div>
        </div>
        <div
            class="col-12 col-md-12 col-lg-7 offset-lg-5 min-vh-100 overflow-y-auto d-flex flex-column justify-content-center position-relative bg-body">
            <div class="w-md-50 mx-auto px-5 px-md-0 py-5">
                <div class="mb-4">
                    <x-image src="/img/logo/aidofly.png" :alt="config('app.name')" width="45" height="45" />
                    <h2 class="mt-3 ls-tight fw-bolder h3">Hey, Hello 👋</h2>
                </div>
                <form class="needs-validation">
                    <div class="mb-3">
                        <x-form.label-input type="email" :label="__('Email address')" id="email" :placeholder="__('Your email')" required
                            autocomplete="email" />
                    </div>
                    <div class="mb-3">
                        <x-form.label-input type="password" :label="__('Password')" id="password" :placeholder="__('Your password')" required
                            autocomplete="current-password" />
                    </div>
                    <div class="d-flex justify-content-between gap-2 mb-4 align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Keep me logged in</label>
                        </div>
                        <a href="{{ route('auth.recover') }}" class="text-sm text-muted text-primary-hover text-underline">
                            <small>Forgot password?</small>
                        </a>
                    </div>
                    <div>
                        <x-button variant="primary" reference="signin" class="w-100">
                            <span>Sign in</span>
                        </x-button>
                    </div>
                </form>
                <div class="text-center mt-5 text-sm text-muted">
                    <span>Don't have an account?</span>
                    <a href="{{ route('auth.signup') }}" class="fw-semibold">Sign up</a>
                </div>
                {{-- <div class="py-5 text-center"><span class="text-xs text-uppercase fw-semibold">or</span></div>
                <div class="row g-2">
                    <div class="col-sm-6"><a href="#" class="btn btn-neutral w-100"><span
                                class="icon icon-sm pe-2"><img src="../../img/social/github.svg" alt="...">
                            </span>Github</a></div>
                    <div class="col-sm-6"><a href="#" class="btn btn-neutral w-100"><span
                                class="icon icon-sm pe-2"><img src="../../img/social/google.svg" alt="...">
                            </span>Google</a></div>
                </div> --}}
            </div>
        </div>
    </section>
@endsection
