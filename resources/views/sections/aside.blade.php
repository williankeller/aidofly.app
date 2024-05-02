<div class="logo-wrapper px-3">
    <a href="{{ route('home.index') }}" class="d-flex align-items-center text-decoration-none">
        <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="40" height="40" />
        <h1 class="m-2 ms-2 logo-name text-body">{{ config('app.name') }}</h1>
    </a>
</div>
<nav role="navigation" class="d-flex flex-column mt-4 px-3">
    <ul class="list-unstyled">
        <x-aside.item route="home.index">
            <i class="fs-4 ti ti-home"></i>
            <span class="ms-2">@lang('Home')</span>
        </x-aside.item>
        <x-aside.item route="library.agent.index" match="library.*">
            <i class="fs-4 ti ti-books"></i>
            <span class="ms-2">@lang('Library')</span>
        </x-aside.item>
    </ul>
    @if ($authUser->isAdministrator())
        <ul class="list-unstyled">
            <li class="nav-item mb-1">
                <strong>@lang('Admin')</strong>
            </li>
            <x-aside.item route="admin.users.index" match="admin.users.*">
                <i class="fs-4 ti ti-users"></i>
                <span class="ms-2">@lang('Users')</span>
            </x-aside.item>
        </ul>
    @endif
    <ul class="list-unstyled">
        <li class="nav-item mb-1">
            <strong>@lang('Agents')</strong>
        </li>
        <x-aside.item route="agent.writer.presets.index" match="agent.writer.presets.*">
            <span class="icon-sm bg-warning">
                <i class="ti ti-file-text"></i>
            </span>
            <span class="ms-2">@lang('Writer')</span>
        </x-aside.item>
        <x-aside.item route="agent.voiceover.index" match="agent.voiceover.*">
            <span class="icon-sm bg-success">
                <i class="ti ti-speakerphone"></i>
            </span>
            <span class="ms-2">@lang('Voice over')</span>
        </x-aside.item>
    </ul>
</nav>
<div class="d-flex flex-column mt-auto px-3">
    <div class="dropdown" @click.away="$refs.accountMenu.classList.remove('show')">

        <div class="dropdown-menu bottom-0 start-50 translate-middle w-100" x-ref="accountMenu"
            @click="$el.classList.remove('show')">
            <a href="{{ route('account.edit') }}" class="d-flex align-items-center dropdown-item py-2">
                <i class="ti ti-user fs-4 me-1"></i>
                <span>@lang('Account')</span>
            </a>
            <a href="{{ route('account.signout') }}" class="d-flex align-items-center dropdown-item py-2">
                <i class="ti ti-logout fs-4 me-1"></i>
                <span>@lang('Sign out')</span>
            </a>
        </div>

        <button class="btn btn-transparent p-0 d-grid gap-2 w-100" @click="$refs.accountMenu.classList.toggle('show')">
            <div class="d-flex align-items-center w-100">
                <div class="text-start flex-grow-1">
                    <div class="text-truncate fw-bold">{{ $authUser->firstname }}</div>
                    <div class="text-muted small text-truncate">{{ $authUser->email }}</div>
                </div>
                <i class="ml-auto fs-4 ti ti-dots-vertical text-muted"></i>
            </div>
        </button>
    </div>
</div>
