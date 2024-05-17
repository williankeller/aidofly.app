<nav class="navbar p-0 mx-5">
    <h3 id="main-links-label" class="visually-hidden">@lang('Main links')</h3>
    <ul class="navbar-nav m-0 d-flex flex-row">
        <x-nav.item route="home.index" class="me-3">
            <i class="fs-4 ti ti-home"></i>
            <span class="ms-2">@lang('Home')</span>
        </x-nav.item>
        <li class="nav-item dropdown me-3" @click.away="$refs.agents.classList.remove('show')">
            <a href="#" role="button" aria-expanded="false" @class([
                'nav-link dropdown-toggle d-flex align-items-center rounded-4',
                'bg-primary text-primary bg-opacity-10' => isRoute('agent.*'),
            ])
                @click="$refs.agents.classList.toggle('show')">
                <i class="fs-4 ti ti-sparkles"></i>
                <span class="ms-2">@lang('Agents')</span>
            </a>
            <div class="dropdown-menu start-0 translate-bottom position-absolute p-4" x-ref="agents"
                @click="$el.classList.remove('show')">
                <div class="d-flex">
                    <x-nav.dropdown-item route="agent.writer.presets.index" match="agent.writer.*">
                        <div class="icon icon-md bg-warning bg-gradient bg-opacity-10">
                            <i class="ti ti-file-text text-warning"></i>
                        </div>
                        <div class="mt-1 fw-bold text-body">@lang('Writer')</div>
                    </x-nav.dropdown-item>
                    <x-nav.dropdown-item route="agent.voiceover.index" match="agent.voiceover.*" last="true">
                        <div class="icon icon-md bg-success bg-gradient bg-opacity-10">
                            <i class="ti ti-speakerphone text-success"></i>
                        </div>
                        <div class="mt-1 fw-bold text-body">@lang('Voiceover')</div>
                    </x-nav.dropdown-item>
                </div>
            </div>
        </li>
        <x-nav.item route="library.index" match="library.*">
            <i class="fs-4 ti ti-books"></i>
            <span class="ms-2">@lang('Library')</span>
        </x-nav.item>
    </ul>
</nav>
