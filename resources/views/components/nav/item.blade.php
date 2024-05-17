<li {{ $attributes->merge(['class' => 'nav-item']) }}>
    <a href="{{ route($route) }}" @class([
        'nav-link d-flex align-items-center rounded-4',
        'bg-primary text-primary bg-opacity-10 bg-gradient' => isRoute($match ?? $route),
    ])>{{ $slot }}</a>
</li>
