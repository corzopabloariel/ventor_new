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
<div class="header">
    <header>
        <div class="container-fluid">
            <div class="container__header">
                <div class="header__logo">
                    <a href="{{ \URL::to('/') }}">
                        <img src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ env('APP_NAME') }}" srcset="">
                    </a>
                </div>
                <div class="header__nav">
                    <div class="header__action">
                        <div class="header__user">
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
                                        <li class="login__user">
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
                                        <li class="login__user">
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
                                        <li class="login__user">
                                            <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'mis-datos']) }}"><i class="fas fa-id-card"></i>Mis datos</a>
                                        </li>
                                        @endif
                                        <li class="login__user">
                                            <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'mis-pedidos']) }}"><i class="fas fa-cash-register"></i>Mis pedidos</a>
                                        </li>
                                        @if (!auth()->guard('web')->user()->test)
                                        <li class="login__user">
                                            <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'analisis-deuda']) }}"><i class="far fa-chart-bar"></i>Análisis de deuda</a>
                                        </li>
                                        <li class="login__user">
                                            <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'faltantes']) }}"><i class="fas fa-layer-group"></i>Faltantes</a>
                                        </li>
                                        <li class="login__user">
                                            <a class="login--link" href="{{ route('client.action', ['cliente_action' => 'comprobantes']) }}"><i class="fas fa-ticket-alt"></i>Comprobantes</a>
                                        </li>
                                        @endif
                                        <li><hr></li>
                                        @if (session()->has('accessADM'))
                                        <li class="login__user">
                                            <a title="{{ session()->get('accessADM')->name }}" class="login--link" href="{{ URL::to('adm/clients/access:' . session()->get('accessADM')->uid) }}"><i class="fas fa-sign-out-alt"></i>Cerrar sesión del Cliente</a>
                                        </li>
                                        @else
                                        <li class="login__user">
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
                                        <li class="login__user">
                                            <div>
                                                <form class="form" id="formLogueo" action="{{ \URL('/login/client') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="form-group mb-0">
                                                        <label for="username">Usuario (CUIT o Nro. cuenta)</label>
                                                        <input name="username" id="username-login" class="username-header form-control" value="{{ old('username') }}" onkeyup="verificarUsuario(this);" type="text" placeholder="Usuario" required>
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label for="password">Contraseña</label>
                                                        <input name="password" class="password-header form-control" type="password" placeholder="Contraseña" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">ingresar</button>
                                                </form>
                                            </div>
                                        </li>
                                        <li class="login__user login__lost">
                                            <p class="text-center mb-0"><a class="text-primary" href="{{ route('password.request') }}">Olvidé mi contraseña</a></p>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <form class="position-relative d-flex align-items-center header__search" action="{{ route('redirect') }}" method="post">
                            @csrf
                            <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                            <button type="submit" class="btn btn-link py-0">
                                <i class="fas fa-search"></i>
                            </button>
                            <input placeholder="Estoy buscando..." required type="search" name="search" class="form-control py-0 border-0 form-control-sm">
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