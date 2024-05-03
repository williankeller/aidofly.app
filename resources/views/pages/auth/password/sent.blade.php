@extends('layouts.auth')

@section('content')
    <section class="bg-primary w-100 bg-gradient overflow-x-hidden">
        <div class="p-5 row align-items-center min-h-screen justify-content-center">
            <div class="m-3 col-12 col-sm-9 col-lg-6 col-xxl-4 card">
                <div class="card-body p-5" data-element="form" x-ref="form" method="post" action="{{ route('password.send') }}">
                    @csrf
                    <div class="mb-4 text-center">
                        <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="45" height="45" />
                        <h2 class="my-4 fw-bolder h4">
                            <span>@lang('Recovery link sent')</span>
                            <span>🚀<span>
                        </h2>
                        <p>@lang('We have sent you an email with a recovery link. Check your inbox (and spam) and follow the instructions to reset your password.')</p>
                        <p class="text-muted small mt-5">@lang('Feel free to close this tab.')</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
