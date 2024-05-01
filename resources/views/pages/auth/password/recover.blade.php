@extends('layouts.auth')

@section('content')
    <section class="bg-primary w-100 bg-gradient overflow-x-hidden">
        <div class="p-5 row align-items-center min-h-screen justify-content-center">
            <div class="m-3 col-12 col-sm-9 col-lg-6 col-xxl-4 card">
                <form class="card-body p-5" is="x-form" x-ref="form" method="post" action="{{ route('password.send') }}">
                    @csrf
                    <div class="mb-4 text-center">
                        <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="45" height="45" />
                        <h2 class="my-4 fw-bolder h4">
                            <span>@lang('Forgot your password?')</span>
                        </h2>
                        <p class="text-muted">@lang('No worries! Let us know your email address and we\'ll email you a password reset link that will allow you to set a new one.')</p>
                    </div>
                    <div>
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

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
