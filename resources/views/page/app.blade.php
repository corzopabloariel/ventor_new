<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="public-key" content="{{ $publicKey ?? '' }}">
    <title>@yield('headTitle')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:200,300,400,400i,600,700,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9ab0ab8372.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Toast.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div id="notification" class="notification d-none align-items-center">
        <div class="notification--text mr-5"></div>
        <div class="spinner-border text-success" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    @if(session('success'))
        <div class="position-fixed w-100 text-center" style="z-index:9999; top:0;">
            <div class="alert alert-success alert-dismissible fade show d-inline-block mb-0">
                {!! session('success')["mssg"] !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="position-fixed w-100 text-center" style="z-index:9999; top: 0;">
            <div style="width: 300px; left: calc(50% - 150px);" class="alert alert-danger alert-dismissible fade show d-inline-block mb-0 position-absolute">
                {!! $errors->first('password') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    @stack('modal')
    @includeIf('page.parts.header')
    @includeIf('page.parts.slider')
    @yield('content')
    @includeIf('page.parts.footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    @stack('js')
</body>
</html>
