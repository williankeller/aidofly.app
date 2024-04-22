<x-nav.page-title :title="$metaTitle" :lead="$metaDescription">
    <div class="btn-group" role="group" aria-label="@lang('Select preset')">
        <a href="{{ route('presets.index') }}" @class([
            'btn btn-secondary btn-sm',
            'active' => isRoute('presets.index'),
        ])>Default</a>
        <a href="{{ route('presets.user') }}" @class([
            'btn btn-secondary btn-sm',
            'active' => isRoute('presets.user'),
        ])>My presets</a>
        <a href="{{ route('presets.discover') }}" @class([
            'btn btn-secondary btn-sm',
            'active' => isRoute('presets.discover'),
        ])>Discover</a>
    </div>
</x-nav.page-title>
