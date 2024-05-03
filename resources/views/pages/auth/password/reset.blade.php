@extends('layouts.auth')

@section('content')
    <section class="bg-primary w-100 bg-gradient overflow-x-hidden">
        <div class="p-5 row align-items-center min-h-screen justify-content-center">
            <div class="m-3 col-12 col-sm-9 col-lg-6 col-xxl-4 card">
                <form class="card-body p-5" data-element="form" x-ref="form" method="post" action="{{ route('password.update') }}">

                    <div class="mb-4 text-center">
                        <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="45" height="45" />
                        <h2 class="mt-3 ls-tight fw-bolder h4">
                            <span>@lang('Reset your password')</span>
                        </h2>
                    </div>
                    <div class="mb-3">
                        <x-form.input-field type="email" :label="__('Email address')" id="email" :placeholder="__('Your email')" required
                            autocomplete="email" maxlength="255" readonly class="disabled"
                            value="{{ request()->query('email') }}" />
                    </div>
                    <div class="mb-3">
                        <x-form.password-field :label="__('New password')" id="password" :placeholder="__('Your password')" required
                            autocomplete="new-password" minlength="6" maxlength="255" />
                    </div>
                    <div class="mb-3">
                        <x-form.password-field :label="__('Confirm password')" id="password_confirmation" :placeholder="__('Confirm your password')" required
                            autocomplete="new-password" minlength="6" maxlength="255" />
                    </div>
                    <div class="mt-4">
                        <x-button class="w-100 ">
                            <span>@lang('Reset password')</span>
                        </x-button>
                    </div>
                    <div role="none" tabindex="-1" class="d-none">
                        @csrf
                        <input type="hidden" name="token" value="{{ request()->token }}">
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

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
