<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head')

<body x-data="{{ $xData ?? '{}' }}">
    <div class="app d-lg-flex">
        <aside role="complementary" aria-label="@lang('Menu')" aria-labelledby="menu-label" x-ref="menuView"
            class="aside flex-column py-4 d-none d-lg-flex">
            @include('sections.aside')
        </aside>
        <div class="d-flex flex-column flex-grow-1">
            <main role="main" aria-label="@lang('Main')" class="main">
                @yield('content')
            </main>
            <footer role="contentinfo" aria-label="@lang('Footer')" class="footer">
                @include('sections.footer')
            </footer>
        </div>
        <div class="d-lg-none" aria-label="@lang('Mobile Menu')">
            @include('sections.nav.mobile')
        </div>
    </div>
    @include('layouts.snippets.scripts')
</body>

</html>
