@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="admin.users.index" :name="__('Users')" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
            <a href="{{ route('admin.users.edit', $user->uuid) }}" class="btn btn-sm btn-light ms-auto">
                <i class="ti ti-pencil"></i>
                <span>@lang('Edit')</span>
            </a>
        </x-nav.page-title>
    </section>

    <section class="card p-5">
        <div class="row d-flex align-items-center">
            <div class="col-lg-8">
                <h4 class="text-bold">{{ $user->firstname }} {{ $user->lastname }}</h4>
                <p>{{ $user->email }}</p>
                <div class="text-muted small">
                    <strong>@lang('Joined'):</strong>
                    <time data-element="time" :data-datetime="{{ $user->created_at }}" data-type="datetime"></time>
                </div>
                <div class="text-muted small">
                    <strong>@lang('Last updated'):</strong>
                    <time data-element="time" :data-datetime="{{ $user->updated_at }}" data-type="datetime"></time>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex align-items-center">
                    <div class="me-2">@lang('Status'):</div>
                    <div>
                        @if ($user->status === 1)
                            <span class="badge bg-success">@lang('Active')</span>
                        @else
                            <span class="badge bg-danger">@lang('Inactive')</span>
                        @endif
                    </div>
                </div>
                <div class="mt-2 d-flex align-items-center">
                    <div class="me-2">@lang('Role'):</div>
                    <div>
                        @if ($user->role === 1)
                            <span class="badge bg-warning">@lang('Admin')</span>
                        @else
                            <span class="badge bg-secondary">@lang('User')</span>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="mt-2 d-flex align-items-center">
                    <div class="me-2">@lang('Library'):</div>
                    <div>
                        <span class="badge bg-primary">{{ $user->library->count() }}</span>
                    </div>
                </div>
                <div class="mt-2 d-flex align-items-center">
                    <div class="me-2">@lang('Presets'):</div>
                    <div>
                        <span class="badge bg-primary">{{ $user->presets->count() }}</span>
                    </div>
                </div>
                <div class="mt-2 d-flex align-items-center">
                    <div class="me-2">@lang('Usage'):</div>
                    <div>
                        <span class="badge bg-primary">{{ $user->library->sum('cost') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
