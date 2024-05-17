<div class="container-boxed d-flex justify-content-between align-items-center py-3">
    <div class="logo-wrapper">
        <a href="{{ route('home.index') }}" class="d-flex align-items-center text-decoration-none">
            <x-image src="/logo/aidofly.png" alt="{{ config('app.name') }} logo" width="40" height="40"
                priority="high" class="icon" />
            <h1 class="m-0 ms-2 logo-name d-none d-lg-block">{{ config('app.name') }}</h1>
        </a>
    </div>
    @include('sections.nav.horizontal')
    <div class="dropdown" @click.away="$refs.accountMenu.classList.remove('show')">
        <div class="dropdown-menu top-0 end-0 translate-bottom" style="width: 220px;" x-ref="accountMenu"
            @click="$el.classList.remove('show')">
            <div class="p-3">
                <div class="text-truncate fw-bold">{{ $authUser->full_name }}</div>
                <div class="text-muted small text-truncate fw-normal">{{ $authUser->email }}</div>
            </div>
            <hr class="my-1">
            @include('sections.nav.account')
        </div>
        <button class="btn btn-transparent p-0" @click="$refs.accountMenu.classList.toggle('show')">
            <div class="d-flex align-items-center w-100">
                <div class="icon icon-sm bg-primary bg-gradient bg-opacity-10">
                    <div class="text-primary small">{{ $authUser->initials }}</div>
                </div>
                <i class="ml-auto fs-5 ti ti-dots-vertical text-muted"></i>
            </div>
        </button>
    </div>
</div>
