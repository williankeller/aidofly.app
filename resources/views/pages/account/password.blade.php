@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="account.edit" :name="__('Account')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <form data-element="form" x-ref="form" action="{{ route('account.password.update') }}" method="post">
        <section class="p-3 p-sm-5 card mb-3">
            <div class="mb-3">
                <x-form.password-field id="current" :label="__('Current password')" :placeholder="__('Enter your current password')" required minlength="6"
                    maxlength="255" autocomplete="current-password" />
            </div>
            <x-form.password-field id="password" :label="__('New password')" :placeholder="__('Enter your new password')" required minlength="6"
                maxlength="255" autocomplete="new-password" />
        </section>

        <section class="d-flex justify-content-end mt-3 mb-5">
            <div role="none" tabindex="-1" class="d-none">
                @method('PUT')
                @csrf
            </div>
            <x-button type="submit">
                <i class="fs-5 ti ti-lock"></i>
                <span class="ms-1">@lang('Change password')</span>
            </x-button>
        </section>
    </form>
@endsection

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
