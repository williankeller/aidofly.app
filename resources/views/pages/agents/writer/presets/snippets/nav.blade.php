<section class="row my-4 my-lg-5 d-flex justify-content-between align-items-center">
    <div @class(['col-lg-7 col-md-6 col-sm-12', 'text-center text-md-start'])>
        <h2 class="page-heading mb-1">{{ $metaTitle }}</h2>
        <p class="mb-0 text-muted">{{ $metaDescription }}</p>
    </div>

    <div class="col-lg-5 col-md-6 col-sm-12 d-flex mt-3 mt-md-0 justify-content-center justify-content-lg-end">
        <ul class="nav nav-pills" aria-label="@lang('Select preset')">
            <li class="nav-item">
                <x-nav.item route="agent.writer.presets.index" class="px-1">
                    <i class="fs-5 ti ti-device-laptop me-1"></i>
                    <span>@lang('Default')</span>
                </x-nav.item>
            </li>
            <li class="nav-item">
                <x-nav.item route="agent.writer.presets.user" class="px-1">
                    <i class="fs-5 ti ti-user me-1"></i>
                    <span>@lang('My presets')</span>
                </x-nav.item>
            </li>
            <li class="nav-item">
                <x-nav.item route="agent.writer.presets.discover">
                    <i class="fs-5 ti ti-world-search me-1"></i>
                    <span>@lang('Discover')</span>
                </x-nav.item>
            </li>
        </ul>
    </div>
</section>
