@push("js")
    <script>
    const verificarUsuario = function(t) {
        let target = $(t);
        let form = target.closest( "form" );
        let input = target.val().toUpperCase();
        if( input.indexOf( "EMP_" ) >= 0 || input.indexOf( "VND_" ) >= 0 ) {
            form.find(".password-header").val("nadaaaaaaaaaa")
            form.find(".password-header").closest(".row").addClass("d-none");
            $(".li-olvide").addClass("d-none");
        } else {
            if("nadaaaaaaaaaa".localeCompare(form.find(".password-header").val()) == 0)
                form.find(".password-header").val("");
            $(".li-olvide").removeClass("d-none");
            form.find(".password-header").closest(".row").removeClass("d-none");
        }
    };
    $(() => {
        $( ".dropdown-menu" ).click(function(e){
            e.stopPropagation();
        });
    });
    </script>
@endpush
<div class="header shadow-sm">
    <header>
        <div class="container">
            <div class="container--header">
                <div>
                    <a href="{{ \URL::to('/') }}">
                        <img class="header--logo" src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ env('APP_NAME') }}" srcset="">
                    </a>
                </div>
                <div class="header--nav__menu">
                    <button class="btn btn-light" data-toggle="modal" data-target="#modalMenuResponsive"><i class="fas fa-bars"></i></button>
                </div>
                <div class="header--nav">
                    <div class="d-flex">
                        <div class="pr-2 border-right">
                            @if(auth()->guard('web')->check())
                                <a href="#" class="p-0 login-link d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if (session()->has('accessADM'))
                                    <i class="fas fa-user-circle mr-2"></i><div>Bienvenido, <strike>{{ auth()->guard('web')->user()["name"] }}</strike></div>
                                    @else
                                    <i class="fas fa-user-circle mr-2"></i>Bienvenido, {{ auth()->guard('web')->user()["name"] }}
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-login shadow-sm dropdown-menu-right border-0 mt-3 bg-transparent p-0">
                                    <ul class="login">
                                        <li>
                                            <form action="{{ route('dataUser', ['attr' => 'markup']) }}" method="post">
                                                @csrf
                                                <div class="login--item">
                                                    @php
                                                    $value = auth()->guard('web')->user()->discount;
                                                    if (session()->has('accessADM')) {
                                                        $value = session()->get('accessADM')->discount;
                                                    }
                                                    @endphp
                                                    <input name="markup" value="{{ $value }}" class="form-control text-right" type="number" min="0" placeholder="% de utilidad" required />
                                                    <button class="btn text-uppercase" type="submit">markup</button>
                                                </div>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('dataUser', ['attr' => 'dates']) }}" method="post">
                                                @csrf
                                                <div class="login--item">
                                                    <div>
                                                        @php
                                                        $today = date("Y-m-d");
                                                        $end = $today;
                                                        $start = date("Y-m-d" , strtotime("-1 month"));
                                                        if (session()->has('accessADM')) {
                                                            if (!empty(session()->get('accessADM')->start))
                                                                $start = session()->get('accessADM')->start;
                                                            if (!empty(session()->get('accessADM')->end))
                                                                $end = session()->get('accessADM')->end;
                                                        } else {
                                                            if (!empty(auth()->guard('web')->user()->start))
                                                                $start = auth()->guard('web')->user()->start;
                                                            if (!empty(auth()->guard('web')->user()->end))
                                                                $end = auth()->guard('web')->user()->end;
                                                        }
                                                        @endphp
                                                        <input name="datestart" max="{{ $today }}" value="{{ $start }}" title="Fecha Desde" class="form-control text-center" type="date" required>
                                                        <input name="dateend" max="{{ $today }}" value="{{ $end }}" title="Fecha Hasta" class="form-control text-center" type="date" required>
                                                    </div>
                                                    <button class="btn text-uppercase" type="submit">
                                                        Rango de<br>Incorporaciones
                                                    </button>
                                                </div>
                                            </form>
                                        </li>
                                        <li><hr></li>
                                        @if (!empty(auth()->guard('web')->user()->uid) || session()->has('accessADM'))
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
                                        @if (session()->has('accessADM'))
                                        <li>
                                            <a title="{{ session()->get('accessADM')->name }}" class="login--link" href="{{ URL::to('adm/clients/access:' . session()->get('accessADM')->uid) }}"><i class="fas fa-sign-out-alt"></i>Cerrar sesión del Cliente</a>
                                        </li>
                                        @else
                                        <li>
                                            <a class="login--link" href="{{ URL::to('logout') }}"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            @else
                                <a href="#" class="p-0 login-link d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle mr-2"></i>Zona de clientes
                                </a>
                                <div class="dropdown-menu dropdown-menu-right border-0 mt-3 bg-transparent p-0">
                                    <ul class="login list-unstyled mb-0 p-0 shadow border-0">
                                        <li class="p-4">
                                            <div>
                                                <form id="formLogueo" action="{{ \URL('/login/client') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="contenedorForm w-100">
                                                        <div class="row justify-content-center align-items-center">
                                                            <div class="col-12">
                                                                <input name="username" id="username-login" class="username-header form-control" value="{{ old('username') }}" onkeyup="verificarUsuario(this);" type="text" placeholder="Usuario" required>
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
                        <form class="position-relative d-flex align-items-center buscador" action="{{ route('redirect') }}" method="post">
                            @csrf
                            <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                            <button type="submit" class="btn btn-link py-0">
                                <i class="fas fa-search"></i>
                            </button>
                            <input placeholder="Estoy buscando..." required type="search" name="search" class="form-control p-0 border-0 form-control-sm">
                        </form>
                    </div>
                    <nav>
                        @php
                        $page = $data["page"] ?? "";
                        if ($page == "parte" || $page == "subparte" || $page == "producto" || $page == "pedido")
                            $page = "productos";
                        @endphp
                        {!! $ventor->sitemap("header", $page) !!}
                    </nav>
                </div>
            </div>
        </div>
    </header>
</div>