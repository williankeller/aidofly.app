<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head', ['robots' => $robots ?? 'index, follow'])

<body>
    <div class="app d-flex">
        <aside role="navigation" class="aside d-flex flex-column py-4">
            @include('sections.aside')
        </aside>
        <div class="d-flex flex-column flex-grow-1">
            <main role="main" class="main"
                @isset($xData) x-data="{{ $xData }}" @endisset>
                @yield('content')
            </main>
            <footer class="footer">
                @include('sections.footer')
            </footer>
        </div>
    </div>
    @include('layouts.snippets.scripts')
</body>

</html>
