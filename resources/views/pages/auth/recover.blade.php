@extends('layouts.auth')

@section('content')
    <section class="bg-primary w-100 bg-gradient">
        <div class="row align-items-center min-h-screen justify-content-center">
            <div class="col-lg-5 card">
                <form class="card-body p-5" is="x-form" x-ref="form" method="post" action="{{ route('auth.recover.send') }}">
                    @csrf
                    <div class="mb-4 text-center">
                        <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="45"
                            height="45" />
                        <h2 class="mt-3 ls-tight fw-bolder h4">
                            <span>@lang('Forgot your password?')</span>
                        </h2>
                        <p class="text-muted">@lang('No worries! ust let us know your email address and we\'ll email you a password reset link that will allow you to choose a new one.')</p>
                    </div>
                    <div class="mb-3">
                        <x-form.input-field type="email" :label="__('Email address')" id="email" :placeholder="__('Your email')" required
                            autocomplete="email" maxlength="255" />
                    </div>
                    <div class="mt-4">
                        <x-button class="w-100 ">
                            <span>@lang('Send recovery link')</span>
                        </x-button>
                    </div>
                    <div class="text-center mt-5 text-muted small">
                        <span>@lang('Remember your password?')</span>
                        <a href="{{ route('auth.signin') }}" class="fw-semibold">@lang('Sign in')</a>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection
