<div class="mobile-nav bg-secondary fixed-bottom start-50 translate-middle rounded">
    <div class="d-flex justify-content-between">
        <a href="{{ route('home.index') }}">
            <div class="d-flex align-items-center text-white">
                <i class="fs-4 ti ti-home"></i>
            </div>
        </a>
        <button aria-expanded="false" class="btn btn-transparent p-0"
            @click="document.body.classList.remove('nav-account');document.body.classList.toggle('nav-main')">
            <div class="d-flex align-items-center text-white">
                <i class="fs-4 ti ti-menu"></i>
            </div>
        </button>
        <button class="btn btn-transparent p-0"
            @click="document.body.classList.remove('nav-main');document.body.classList.toggle('nav-account')">
            <div class="d-flex align-items-center text-white">
                <i class="fs-4 ti ti-user"></i>
            </div>
        </button>
    </div>
</div>

<div class="mobile-account-view position-fixed bg-white top-0 start-0 z-11 w-100 h-100 p-4">
    <div class="w-100 bg-light rounded p-3 mb-3">
        <div class="text-truncate fw-bold">{{ $authUser->firstname }}</div>
        <div class="text-muted text-truncate">{{ $authUser->email }}</div>
    </div>
    @include('sections.nav.account')
</div>

<div class="mobile-menu-view position-fixed bg-white top-0 start-0 z-11 w-100 h-100 p-4">
    @include('sections.nav.mobile-menu')
</div>
