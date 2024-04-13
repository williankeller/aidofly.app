<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="{!! $robots ?? 'noindex' !!}" />

    <title>{{ $metaTitle ?? config('app.name') }}</title>

    <meta property="og:locale" content="{{ locale(true) }}">
    <meta property="og:type" content="application">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $metaTitle ?? config('app.name') }}">
    @isset($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="og:description" property="og:description" content="{{ $metaDescription }}">
    @endisset
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="{{ asset('images/logo/aidofly-logo.png') }}">
    <meta property="og:image:alt" content="{{ config('app.name') }} image">

    <link rel="canonical" href="{{ request()->url() }}" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/png">
    <link rel="preconnect" href="https://www.google-analytics.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="manifest" href="{{ config('app.url') }}/manifest.json">

    @stack('head-stack-before')

    {!! stylesheet('style/core.min.css', true) !!}

    @include('layouts.snippets.gtag')

    @stack('head-stack-after')
</head>