<li class="nav-item mb-1">
    <a href="{{ route($route) }}" @class([
        'nav-link d-flex align-items-center',
        'active' => isRoute($route),
    ])>
        {{ $slot }}
    </a>
</li>
