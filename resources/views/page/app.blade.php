<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="captcha" content="{{ $ventor->captcha['public'] }}">
    <meta name="url" content="{{ url::to('/') }}">
    <meta name="order" content="{{ route('order') }}">
    <meta name="client" content="{{ route('client.select') }}">
    <meta name="cart" content="{{ route('cart.add') }}">
    <meta name="eventSource" content="{{ route('eventSource') }}">
    <meta name="cart-show" content="{{ route('cart.show') }}">
    <meta name="checkout" content="{{ route('order.checkout') }}">
    @if (Auth::user())
        @if (Auth::user()->isShowQuantity())
        <meta name="browser" content="{{ route('client.browser') }}">
        @endif
        <meta name="preference" content="{{ Auth::user()->configs }}">
    @endif
    <meta name="soap" content="{{ route('soap') }}">
    <meta name="type" content="{{ route('type') }}">
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
    {{--<div id="notification" class="notification d-none align-items-center">
        <div class="notification--text mr-5"></div>
        <div class="spinner-border text-success" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    @if(session('success'))
        <div class="position-fixed w-100 text-center" style="z-index:9999; top:0;">
            <div style="width: 300px; left: calc(50% - 150px);" class="alert alert-success alert-dismissible fade show d-inline-block mb-0 position-absolute">
                {!! session('success') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any() && !empty($errors->first('password')))
        <div class="position-fixed w-100 text-center" style="z-index:9999; top: 0;">
            <div style="width: 300px; left: calc(50% - 150px);" class="alert alert-danger alert-dismissible fade show d-inline-block mb-0 position-absolute">
                {!! $errors->first('password') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    @isset($data["lateral"])
    <div class="modal fade" id="partesModal" tabindex="-1" role="dialog" aria-labelledby="partesModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h2>Partes y subpartes</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include("page.elements.__lateral", ['elements' => $data["lateral"]])
                </div>
            </div>
        </div>
    </div>
    @endisset
    <div class="modal fade" id="modalMenuResponsive" tabindex="-1" role="dialog" aria-labelledby="modalMenuResponsiveTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <a href="{{ url::to('/') }}">
                        <img class="w-100" src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ config('app.name') }}" srcset="">
                    </a>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        @php
                        $page = $data["page"] ?? "";
                        if ($page == "parte" || $page == "subparte" || $page == "producto" || $page == "pedido")
                            $page = "productos";
                        @endphp
                        @if(auth()->guard('web')->check())
                            <a href="#" class="p-0 mb-4 login__link d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle mr-2"></i>Bienvenido, {{ auth()->guard('web')->user()["name"] }}
                            </a>
                            {!! $ventor->sitemap("header", $page) !!}
                            <form class="mt-4" action="{{ route('dataUser', ['attr' => 'markup']) }}" method="post">
                                @csrf
                                <div class="login--item">
                                    <input name="markup" value="{{ auth()->guard('web')->user()->discount }}" class="form-control text-right" type="number" min="0" placeholder="% de utilidad" required />
                                    <button class="btn text-uppercase" type="submit">markup</button>
                                </div>
                            </form>
                            <form action="{{ route('dataUser', ['attr' => 'dates']) }}" class="mt-3" method="post">
                                @csrf
                                <div class="login--item">
                                    <div>
                                        @php
                                        $hoy = date( "Y-m-d" );
                                        $mes = date( "Y-m-d" , strtotime( "-1 month" ) );
                                        @endphp
                                        <input name="datestart" max="{{ $hoy }}" value="{{ empty(auth()->guard('web')->user()->start) ? $mes : auth()->guard('web')->user()->start }}" title="Fecha Desde" class="form-control text-center" type="date" required>
                                        <input name="dateend" max="{{ $hoy }}" value="{{ empty(auth()->guard('web')->user()->end) ? $hoy : auth()->guard('web')->user()->end }}" title="Fecha Hasta" class="form-control text-center" type="date" required>
                                    </div>
                                    <button class="btn text-uppercase" type="submit">
                                        Incorporaciones
                                    </button>
                                </div>
                            </form>
                            <ul class="login">
                                @if (!empty(auth()->guard('web')->user()->uid))
                                <li>
                                    <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'mis-datos']) }}"><i class="fas fa-id-card"></i>Mis datos</a>
                                </li>
                                @endif
                                <li>
                                    <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'mis-pedidos']) }}"><i class="fas fa-cash-register"></i>Mis pedidos</a>
                                </li>
                                @if (!auth()->guard('web')->user()->test)
                                <li>
                                    <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'analisis-deuda']) }}"><i class="far fa-chart-bar"></i>Análisis de deuda</a>
                                </li>
                                <li>
                                    <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'faltantes']) }}"><i class="fas fa-layer-group"></i>Faltantes</a>
                                </li>
                                <li>
                                    <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'comprobantes']) }}"><i class="fas fa-ticket-alt"></i>Comprobantes</a>
                                </li>
                                @endif
                                <li><hr></li>
                                <li>
                                    <a class="login--link" href="{{ URL::to('logout') }}"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a>
                                </li>
                            </ul>
                        @else
                            <a href="#" class="p-0 mb-4 login__link d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle mr-2"></i>Zona de clientes
                            </a>
                            {!! $ventor->sitemap("header", $page) !!}
                            <div class="dropdown-menu dropdown-menu-right border-0 mt-4 bg-transparent p-0">
                                <ul class="login list-unstyled mb-0 p-0 shadow border-0">
                                    <li class="p-4">
                                        <div>
                                            <form id="formLogueoModal" action="{{ url('/login/client') }}" method="post">
                                                {{ csrf_field() }}
                                                <div class="contenedorForm w-100">
                                                    <div class="row justify-content-center align-items-center">
                                                        <div class="col-12">
                                                            <input name="username" id="username-login_modal" class="username-header form-control" value="{{ old('username') }}" onkeyup="verificarUsuario(this);" type="text" placeholder="Usuario" required>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-center align-items-center">
                                                        <div class="col-12">
                                                            <input name="password" class="password-header form-control" type="password" placeholder="Contraseña" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn mx-auto px-4 d-block mx-auto mt-3 text-uppercase" type="submit">ingresar</button>
                                            </form>
                                        </div>
                                    </li>
                                    <li class="py-3 bg-white li-olvide">
                                        <p class="text-center mb-0"><a class="text-primary" href="{{ route('password.request') }}">Olvidé mi contraseña</a></p>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stack('modal')--}}
    @includeIf('page.parts.header')
    {{--@includeIf('page.parts.slider')--}}
    @yield('content')
    {{--@includeIf('page.parts.footer')--}}
    <script src="{{ asset('js/app.js').'?t='.time() }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    @stack('js')
</body>
</html>
