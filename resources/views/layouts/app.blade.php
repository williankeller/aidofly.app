<!DOCTYPE html>
<html lang="{!! locale(true) !!}">

@include('layouts.snippets.head')

<body x-data="{{ $xData ?? '{}' }}">
    <div class="app d-flex flex-column flex-grow-1">
        <header role="navigation" class="header d-none d-md-block">
            @include('sections.header')
        </header>
        <main role="main" aria-label="@lang('Main')" class="main">
            @yield('content')
        </main>
        <footer role="contentinfo" aria-label="@lang('Footer')" class="footer">
            @include('sections.footer')
        </footer>
    </div>
    <div role="none" aria-label="@lang('Mobile Menu')" aria-hidden="true" class="d-lg-none">
        @include('sections.nav.mobile')
    </div>
    @include('layouts.snippets.scripts')
</body>
</html>
