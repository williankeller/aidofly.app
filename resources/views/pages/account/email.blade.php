@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="account.edit" :name="__('Account')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <form data-element="form" x-ref="form" action="{{ route('account.email.update') }}" method="post">
        <section class="p-3 p-sm-5 card mb-3">
            <div class="mb-3">
                <x-form.input-field type="email" id="email" :label="__('Email')" :placeholder="__('Email you want to change to')" required
                    maxlength="255" :value="$authUser->email" autocomplete="email" />
            </div>
            <x-form.password-field id="password" :label="__('Password')" :placeholder="__('Your current password')" required minlength="6"
                maxlength="255" autocomplete="current-password" />
        </section>

        <section class="d-flex justify-content-end mt-3 mb-5">
            <div role="none" tabindex="-1" class="d-none">
                @method('PUT')
                @csrf
            </div>
            <x-button type="submit">
                <i class="fs-5 ti ti-at"></i>
                <span class="ms-1">@lang('Change email')</span>
            </x-button>
        </section>
    </form>
@endsection

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
