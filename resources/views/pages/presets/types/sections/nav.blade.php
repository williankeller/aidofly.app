<x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
    <ul class="nav nav-pills" aria-label="@lang('Select preset')">
        <li class="nav-item">
            <a href="{{ route('presets.index') }}" @class([
                'nav-link d-flex align-items-center',
                'active' => isRoute('presets.index'),
            ])>
                <i class="fs-5 ti ti-device-laptop me-1"></i>
                <span>@lang('Default')</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('presets.user') }}" @class([
                'nav-link d-flex align-items-center',
                'active' => isRoute('presets.user'),
            ])>
                <i class="fs-5 ti ti-user me-1"></i>
                <span>@lang('My presets')</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('presets.discover') }}" @class([
                'nav-link d-flex align-items-center',
                'active' => isRoute('presets.discover'),
            ])>
                <i class="fs-5 ti ti-world-search me-1"></i>
                <span>@lang('Discover')</span>
            </a>
        </li>
    </ul>
</x-nav.page-title>
