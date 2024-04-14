<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head', ['robots' => $robots ?? 'index, follow'])

<body class="vh-100 d-flex">
    <aside role="navigation" class="aside vh-100 d-flex flex-column py-4">
        @include('sections.aside')
    </aside>
    <div class="vh-100 d-flex flex-column flex-grow-1">
        <main role="main" class="main">
            @yield('content')
        </main>
        <footer class="footer">
            @include('sections.footer')
        </footer>
    </div>
    @include('layouts.snippets.scripts')
</body>

</html>
