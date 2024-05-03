<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head', ['robots' => $robots ?? 'index, follow'])

<body x-data="{{ $xData ?? '{}' }}">
    <main class="h-screen">
        @yield('content')
    </main>
    @include('layouts.snippets.scripts')
</body>
</html>
