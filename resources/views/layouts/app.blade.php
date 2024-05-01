<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head', ['robots' => $robots ?? 'index, follow'])

<body x-data="{{ $xData ?? '{}' }}">
    <div class="app d-flex">
        <aside role="complementary" class="aside d-flex flex-column py-4" aria-label="Menu">
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
    </div>
    @include('layouts.snippets.scripts')
</body>

</html>
