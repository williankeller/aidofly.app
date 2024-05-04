<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head')

<body x-data="{{ $xData ?? '{}' }}">
    <div class="app d-lg-flex">
        <aside role="complementary" class="aside flex-column py-4 d-none d-lg-flex" aria-label="Menu">
            @include('sections.aside')
        </aside>
        <div class="d-flex flex-column flex-grow-1">
            <main role="main" class="main" aria-label="Main">
                @yield('content')
            </main>
            <footer class="footer" aria-label="Footer">
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
