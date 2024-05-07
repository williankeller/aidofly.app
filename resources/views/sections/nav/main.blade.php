<h2 id="menu-label" class="visually-hidden">@lang('Main menu')</h2>
<nav role="navigation" class="d-flex flex-column mt-4 px-3">
    <h3 id="main-links-label" class="visually-hidden">@lang('Main links')</h3>
    <ul class="list-unstyled">
        <x-aside.item route="home.index">
            <i class="fs-4 ti ti-home"></i>
            <span class="ms-2">@lang('Home')</span>
        </x-aside.item>
        <x-aside.item route="library.index" match="library.*">
            <i class="fs-4 ti ti-books"></i>
            <span class="ms-2">@lang('Library')</span>
        </x-aside.item>
    </ul>
    @if ($authUser->isAdministrator())
        <ul class="list-unstyled">
            <li class="nav-item mb-2 mb-lg-1">
                <strong>@lang('Admin')</strong>
            </li>
            <x-aside.item route="admin.users.index" match="admin.users.*">
                <i class="fs-4 ti ti-users"></i>
                <span class="ms-2">@lang('Users')</span>
            </x-aside.item>
        </ul>
    @endif
    <h3 id="agents-links-label" class="nav-item fs-6 fw-bolder mb-2">@lang('Agents')</h3>
    <ul aria-labelledby="agents-links-label" class="list-unstyled">
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
