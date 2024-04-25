@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" icon="ti-square-rounded-arrow-left-filled" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <form is="x-form" x-ref="form" action="{{ route('account.update') }}" method="post">
        <section class="p-5 card mb-3">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <x-form.input-field id="firstname" :label="__('First name')" :placeholder="__('Your first name')" required maxlength="128"
                        :value="$user->firstname" />
                </div>
                <div class="col-lg-6">
                    <x-form.input-field id="lastname" :label="__('Last name')" :placeholder="__('Your lastname')" required maxlength="128"
                        :value="$user->lastname" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <x-form.input-field type="email" id="email" :label="__('Email address')" :placeholder="__('Your email address')" required
                        maxlength="255" readonly :value="$user->email" disabled />
                </div>
            </div>
        </section>

        @if (auth()->user()->isAdmin())
            <section class="p-5 card mb-3">
                <h3 class="fw-bolder h5">@lang('Account details')</h3>
                <div class="mt-2">
                    <div class="form-label mb-2">
                        <span>@lang('Role')</span>
                    </div>
                    <div class="d-flex">
                        <div class="form-check-group me-2">
                            <input type="radio" class="btn-check" name="role" id="roleUser" value="user"
                                @checked(old('role', $user->role) == '0') autocomplete="off">
                            <label class="btn btn-outline-secondary py-1" for="roleUser">
                                <span class="fw-bold">@lang('User')</span>
                            </label>
                        </div>
                        <div class="form-check-group">
                            <input type="radio" class="btn-check" name="role" id="roleAdmin" value="admin"
                                @checked(old('role', $user->role) == '1') autocomplete="off">
                            <label class="btn btn-outline-secondary py-1" for="roleAdmin">
                                <span class="fw-bold">@lang('Admin')</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center bg-light p-3 rounded"
                    x-data="{ status: {{ old('status', $user->status) ?? 'false' }} }">
                    <div class="form-label mb-0">@lang('Status')</span></div>
                    <label class="form-check form-switch form-check-reverse mb-0" for="status" @click="status = !status">
                        <input class="form-check-input" type="checkbox" name="status" role="switch" id="status"
                            value="active" @checked(old('status', $user->status))>
                        <span class="form-check-label fw-bold"
                            :class="{ 'd-none': !status, 'd-block': status }">@lang('Active')</span>
                        <span class="form-check-label fw-bold"
                            :class="{ 'd-none': status, 'd-block': !status }">@lang('Inactive')</span>
                    </label>
                </div>
            </section>
        @endif

        <section class="d-flex justify-content-end my-5">
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
    @if ($errors->any())
        <x-notification :message="$errors->first()" :show="true" />
    @endif

    @if (session()->get('message'))
        <x-notification :message="session()->get('message')['content']" :show="true" />
    @endif

    {!! javascript('js/auth.min.js', true) !!}
@endpush
