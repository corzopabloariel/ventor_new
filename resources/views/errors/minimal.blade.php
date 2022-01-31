<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:200,300,400,400i,600,700,900&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/9ab0ab8372.js" crossorigin="anonymous"></script>
        <!-- Styles -->
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
        @stack('styles')
    </head>
    <body class="home">
        <div id="headerItem">
            <header class="header">

                <div class="header__holder">

                    <div class="logo">
                        <a href="{{ \URL::to('/') }}">
                            <h1>
                                <picture>
                                    <img srcset="http://staticbcp.ventor.com.ar/img/logo.png" alt="{{config('app.name')}}">
                                </picture>
                            </h1>
                        </a>
                    </div>
                </div>
            </header>
        </div>
        @yield('content')
        <footer class="footer">
            <div class="footer__top-layout"></div>
            <div class="footer__holder">
                <div class="footer_info">
                    <img src="http://staticbcp.ventor.com.ar/img/logo.png" alt="{{config('app.name')}}">
                </div>
                <ul class="footer__nav"></ul>
                <ul class="footer__nav"></ul>
                <ul class="footer__contact-nav">
                    <ul class="footer--data">
                        <li>{!! $ventor->addressPrint() !!}</li>
                        <li>{!! $ventor->phonesPrint() !!}</li>
                        <li>{!! $ventor->emailsPrint() !!}</li>
                    </ul>
                </ul>
                <div class="footer__nav">
                    <ul class="footer--data footer__social-nav">
                        {!! $ventor->socialFooter() !!}
                    </ul>
                </div>
            </div>
        </footer>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        @stack('js')
    </body>
</html>
