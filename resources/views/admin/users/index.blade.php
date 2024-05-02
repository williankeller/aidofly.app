@extends('layouts.app')

@section('content')
    <section class="mb-5">
        <x-nav.back route="home.index" :name="__('Home')" />
        <x-nav.page-title :title="$metaTitle" :lead="$metaDescription" />
    </section>

    <section class="list">
        @foreach ($users as $user)
            <div class="card card-item w-100 p-3 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="">
                        <strong class="d-block">{{ $user->firstname }} {{ $user->lastname }}</strong>
                        <p class="mb-1">{{ $user->email }}</p>
                        <div class="text-muted small">
                            <span>@lang('Joined')</span>
                            <time is="x-time" datetime="{{ $user->created_at }}" data-type="datetime"></time>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        @if ($user->role === 1)
                            <div class="role me-2">
                                <span class="badge bg-warning">@lang('Admin')</span>
                            </div>
                        @endif
                        <div class="badges">
                            @if ($user->status === 1)
                                <span class="badge bg-success">@lang('Active')</span>
                            @else
                                <span class="badge bg-danger">@lang('Inactive')</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $user->uuid) }}" class="stretched-link"></a>
            </div>
        @endforeach
    </section>
@endsection
