<div @class([
    'nav-item card card-item p-3',
    'me-3' => isset($last) ? false : true,
    'bg-gradient bg-light' => isRoute($match ?? $route),
])>
    <a href="{{ route($route) }}" @class([
        'nav-link d-flex justify-content-center flex-column align-items-center',
    ])>{{ $slot }}</a>
</div>
