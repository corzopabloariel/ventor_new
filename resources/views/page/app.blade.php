<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="captcha" content="{{ $ventor->captcha['public'] }}">
    <meta name="url" content="{{ url::to('/') }}">
    @if (Auth::user())
        <meta name="preference" content="{{ Auth::user()->configs }}">
    @endif
    <title>@yield('headTitle')</title>
    <meta name="title" content="{{ $data['title'] ?? '' }}">
    <meta name="description" content="{{ $data['description'] ?? '' }}">
    <link rel="icon" type="image/png" href="{{ asset($ventor->images['favicon']['i']) }}" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <script src="https://kit.fontawesome.com/9ab0ab8372.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link href="{{ asset('css/main.css').'?t='.time() }}" rel="stylesheet">
    <link href="{{ asset('css/Toast.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="home">
    @includeIf('layouts._header')
    @yield('content')
    @includeIf('layouts._footer')
    <script src="{{ asset('js/app.js').'?t='.time() }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    @yield('script')
    @stack('js')
</body>
</html>
