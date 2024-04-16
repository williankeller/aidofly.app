@extends('layouts.app')

@section('content')
    <section class="">
        <small class="d-block mb-1 text-muted">@lang('Welcome')</small>
        <h3 class="fw-bolder">{{ $authUser->firstname }}</h3>
    </section>
@endsection
