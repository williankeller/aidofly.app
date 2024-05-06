<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head')

<body x-data="{{ $xData ?? '{}' }}">
    <div class="app d-lg-flex">
        <aside role="complementary" aria-label="Menu" aria-labelledby="menu-label" class="aside flex-column py-4 d-none d-lg-flex">
            @include('sections.aside')
        </aside>
        <div class="d-flex flex-column flex-grow-1">
            <main role="main" aria-label="Main" class="main">
                @yield('content')
            </main>
            <footer role="contentinfo" aria-label="Footer" class="footer">
                @include('sections.footer')
            </footer>
        </div>
        <div class="d-lg-none" aria-label="Mobile Menu">
            @include('sections.mobile-menu')
        </div>
    </div>
    @include('layouts.snippets.scripts')
</body>
</html>
