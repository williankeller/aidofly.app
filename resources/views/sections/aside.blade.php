<div class="logo-wrapper px-3">
    <a href="{{ route('home.index') }}" class="d-flex align-items-center text-decoration-none">
        <x-image src="/img/logo/aidofly.png" :alt="config('app.name')" width="40" height="40" />
        <h1 class="m-2 ms-2 logo-name text-body">{{ config('app.name') }}</h1>
    </a>
</div>
<nav class="d-flex flex-column mt-4 px-3">
    <ul class="list-unstyled">
        <x-aside.item route="home.index">
            <i class="fs-4 ti ti-home"></i>
            <span class="ms-2">@lang('Home')</span>
        </x-aside.item>
        <x-aside.item route="library.agent.index">
            <i class="fs-4 ti ti-files"></i>
            <span class="ms-2">@lang('Library')</span>
        </x-aside.item>
    </ul>
    <ul class="list-unstyled">
        <li class="nav-item mb-1">
            <strong>@lang('Agents')</strong>
        </li>
        <x-aside.item route="presets.index">
            <span class="nav-icon bg-warning bg-gradient rounded p-1 d-flex align-items-center">
                <i class="fs-4 text-white ti ti-file-text"></i>
            </span>
            <span class="ms-2">@lang('Writer')</span>
        </x-aside.item>
        {{--<x-aside.item route="home.index">
            <span class="bg-success bg-gradient rounded p-1 d-flex align-items-center">
                <i class="fs-4 text-white ti ti-speakerphone"></i>
            </span>
            <span class="ms-2">@lang('Voice over')</span>
        </x-aside.item>--}}
    </ul>
</nav>
