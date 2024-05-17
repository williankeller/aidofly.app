<nav role="navigation" class="d-flex flex-column w-100">
    <h3 id="main-links-label" class="visually-hidden">@lang('Main links')</h3>
    <ul class="navbar-nav list-unstyled">
        <x-nav.item route="home.index" class="mb-2">
            <i class="fs-4 ti ti-home"></i>
            <span class="ms-2">@lang('Home')</span>
        </x-nav.item>
        <x-nav.item route="library.index" match="library.*">
            <i class="fs-4 ti ti-books"></i>
            <span class="ms-2">@lang('Library')</span>
        </x-nav.item>
    </ul>
    <h3 id="agents-links-label" class="mt-4 nav-item fs-6 fw-bolder mb-2">@lang('Agents')</h3>
    <ul aria-labelledby="agents-links-label" class="mt-3 list-unstyled">
        <x-nav.item route="agent.writer.presets.index" match="agent.writer.*" class="mb-2">
            <span class="icon icon-md bg-warning bg-gradient bg-opacity-10">
                <i class="ti ti-file-text text-warning"></i>
            </span>
            <span class="ms-1 fw-bold">@lang('Writer')</span>
        </x-nav.item>
        <x-nav.item route="agent.voiceover.index" match="agent.voiceover.*" class="mb-2">
            <span class="icon icon-md bg-success bg-gradient bg-opacity-10">
                <i class="ti ti-speakerphone text-success"></i>
            </span>
            <span class="ms-1 fw-bold">@lang('Voice over')</span>
        </x-nav.item>
    </ul>
</nav>
