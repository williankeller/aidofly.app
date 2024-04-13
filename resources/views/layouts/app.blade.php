<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head', ['robots' => $robots ?? 'index, follow'])

<body>
    <aside class="sidebar">
        @include('sections.aside')
    </aside>
    <header class="header"></header>
    <div class="">
        <main class="main">
            @yield('content')
        </main>
        <footer class="footer">
            @include('sections.footer')
        </footer>
    </div>
    @include('layouts.snippets.scripts')
</body>

</html>
