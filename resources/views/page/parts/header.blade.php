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
                <a href="{{ url::to('/') }}">
                    <img class="header--logo" src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ env('APP_NAME') }}" srcset="">
                </a>
                <div class="header--nav">
                    <div class="d-flex">
                        <div class="pr-2 border-right">
                            @if(auth()->guard('web')->check())
                                <a tabindex="-1" href="#" class="p-0 login-link d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle mr-2"></i>Bienvenido, {{ auth()->guard('web')->user()["name"] }}
                                </a>
                                <div class="dropdown-menu dropdown-login shadow-sm dropdown-menu-right border-0 mt-3 bg-transparent p-0">
                                    <ul class="login">
                                        <li>
                                            <form action="{{ url('/cliente/markup') }}" method="post">
                                                {{ csrf_field() }}
                                                <div class="login--item">
                                                    <input name="markup" id="markup" value="{{ auth()->guard('web')->user()->discount }}" class="form-control text-right" type="number" min="0" placeholder="% de utilidad" required />
                                                    <button class="btn text-uppercase" type="submit">markup</button>
                                                </div>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ url('/cliente/dates') }}" method="post">
                                                {{ csrf_field() }}
                                                <div class="login--item">
                                                    <div>
                                                        @php
                                                        $hoy = date( "Y-m-d" );
                                                        $mes = date( "Y-m-d" , strtotime( "-1 month" ) );
                                                        @endphp
                                                        <input name="date-desde" max="{{ $hoy }}" value="{{ empty(auth()->guard('web')->user()->start) ? $mes : auth()->guard('web')->user()->start }}" title="Fecha Desde" class="form-control text-center" type="date" required>
                                                        <input name="date-hasta" max="{{ $hoy }}" value="{{ empty(auth()->guard('web')->user()->end) ? $hoy : auth()->guard('web')->user()->end }}" title="Fecha Hasta" class="form-control text-center" type="date" required>
                                                    </div>
                                                    <button class="btn text-uppercase" type="submit">
                                                        Rango de<br>Incorporaciones
                                                    </button>
                                                </div>
                                            </form>
                                        </li>
                                        <li>
                                            <a tabindex="-1" class="login--link" href="{{ URL::to('cliente/datos') }}"><i class="fas fa-id-card"></i>Mis datos</a>
                                        </li>
                                        <li>
                                            <a tabindex="-1" class="login--link" href="#{{-- URL::to('cliente/pedidos') --}}"><i class="fas fa-cash-register"></i>Mis pedidos</a>
                                        </li>
                                        @if (!auth()->guard('web')->user()->isAdmin())
                                        <li>
                                            <a tabindex="-1" class="login--link" href="{{ URL::to('logout') }}"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            @else
                                <a tabindex="-1" href="#" class="p-0 login-link d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle mr-2"></i>Zona de clientes
                                </a>
                                <div class="dropdown-menu dropdown-menu-right border-0 mt-3 bg-transparent p-0">
                                    <ul class="login list-unstyled mb-0 p-0 shadow border-0">
                                        <li class="p-4">
                                            <div>
                                                <form id="formLogueo" action="{{ url('/login/client') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="contenedorForm w-100">
                                                        <div class="row justify-content-center align-items-center">
                                                            <div class="col-12">
                                                                <input name="username" class="username-header form-control" value="{{ old('username') }}" onkeyup="verificarUsuario(this);" type="text" placeholder="Usuario" required>
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
                                            <p class="text-center mb-0"><a tabindex="-1" class="text-primary" href="#" onclick="registrar( this );">Olvidé mi contraseña</a></p>
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
                        $page = $data["page"];
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