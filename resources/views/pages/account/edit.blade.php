@extends('layouts.app')

@section('content')
    <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />

    <form data-element="form" x-ref="form" action="{{ route('account.update') }}" method="post">
        <section class="p-3 p-sm-5 card mb-3">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <x-form.input-field id="firstname" :label="__('First name')" :placeholder="__('Your first name')" required :value="$authUser->firstname"
                        autocomplete="given-name" minlength="1" maxlength="32" />
                </div>
                <div class="col-lg-6">
                    <x-form.input-field id="lastname" :label="__('Last name')" :placeholder="__('Your lastname')" required :value="$authUser->lastname"
                        autocomplete="family-name" minlength="2" maxlength="32" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-8">
                    <x-form.input-field type="email" id="email" :label="__('Email address')" :value="$authUser->email" readonly disabled
                        autocomplete="off" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <label class="form-label required" for="locale">@lang('Language')</label>
                    <select id="locale" name="locale" class="form-select form-control" required>
                        <option value="" disabled selected>@lang('Select language')</option>
                        @foreach ($locales as $code => $locale)
                            <option value="{{ $code }}" @selected(old('locale', $authUser?->preferences?->locale ?? app()->getLocale()) == $code)>
                                {{ $locale }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-5">
                <a href="{{ route('account.password.edit') }}"
                    class="text-muted text-hover-primary d-flex align-items-cente">
                    <i class="ti ti-lock fs-5 me-1"></i>
                    <span>@lang('Change password')</span>
                </a>
                <a href="{{ route('account.email.edit') }}" class="text-muted text-hover-primary d-flex align-items-cente">
                    <i class="ti ti-at fs-5 me-1"></i>
                    <span>@lang('Update email')</span>
                </a>
            </div>
        </section>

        <section class="d-flex justify-content-end my-3 my-lg-4">
            <div role="none" tabindex="-1" class="d-none">
                @method('PUT')
                @csrf
            </div>
            <x-button type="submit">
                <i class="fs-5 ti ti-device-floppy"></i>
                <span class="ms-1">@lang('Save changes')</span>
            </x-button>
        </section>
    </form>
@endsection

@push('script-stack-after')
    <x-notification.flash :errors="$errors" />
@endpush
