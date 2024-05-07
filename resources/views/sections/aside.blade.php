<div class="logo-wrapper px-3">
    <a href="{{ route('home.index') }}" class="d-flex align-items-center text-decoration-none">
        <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="40" height="40" />
        <h1 class="m-2 ms-2 logo-name text-body">{{ config('app.name') }}</h1>
    </a>
</div>
@include('sections.nav.main')
<div class="d-flex flex-column mt-auto px-3">
    <div class="dropdown account-toggle" @click.away="$refs.accountMenu.classList.remove('show')">
        <div class="dropdown-menu bottom-0 start-50 translate-middle w-100" x-ref="accountMenu"
            @click="$el.classList.remove('show')">
            @include('sections.nav.account')
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
