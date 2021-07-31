@push("js")
    <script>
    const verificarUsuario = function(t) {
        let target = $(t);
        let form = target.closest( "form" );
        let input = target.val().toUpperCase();
        if( input.indexOf( "EMP_" ) >= 0 || input.indexOf( "VND_" ) >= 0 ) {
            form.find(".password-header").val("nadaaaaaaaaaa")
            form.find(".password-header").closest(".form-group").addClass("d-none");
            $(".li-olvide").addClass("d-none");
        } else {
            if("nadaaaaaaaaaa".localeCompare(form.find(".password-header").val()) == 0)
                form.find(".password-header").val("");
            $(".li-olvide").removeClass("d-none");
            form.find(".password-header").closest(".form-group").removeClass("d-none");
        }
    };
    </script>
@endpush
<div class="header">
    <header>
        <div class="container-fluid">
            <div class="container__header">
                <div class="header__logo">
                    <a href="{{ \URL::to('/') }}">
                        <img src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ config('app.name') }}" srcset="">
                    </a>
                </div>
                <div class="header__nav">
                    @php
                    $page = $data["page"] ?? "";
                    @endphp
                    <div class="header__action">
                        <div class="header__user">
                            <div class="dropdown">
                            @if(auth()->guard('web')->check())
                                <a href="#" class="p-0 login__link" id="dropdownMenuLogin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if (session()->has('accessADM'))
                                    <i class="fas fa-user-circle mr-2"></i><div>Bienvenido, <strike>{{ auth()->guard('web')->user()["name"] }}</strike></div>
                                    @else
                                    <i class="fas fa-user-circle mr-2"></i>Bienvenido, {{ auth()->guard('web')->user()["name"] }}
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-login shadow dropdown-menu-right border-0 mt-3 bg-transparent p-0" aria-labelledby="dropdownMenuLogin">
                                    <ul class="login">
                                        @if(auth()->guard('web')->user()->isShowQuantity())
                                        <li class="login__user">
                                            @php
                                            $cartConfig = auth()->guard('web')->user()->config->other['cart'] ?? 1;
                                            @endphp
                                            <select id="cart__select" class="form-control">
                                                @for($i = 1; $i <= $cartConfig; $i++)
                                                <option @if(session()->has('cartSelect') && session()->get('cartSelect') == $i) selected @endif value="{{$i}}">Carrito #{{$i}}</option>
                                                @endfor
                                            </select>
                                        </li>
                                        @endif
                                        <li class="login__user">
                                            <form action="{{ route('dataUser', ['attr' => 'markup']) }}" id="form--markup" method="post">
                                                @csrf
                                                <div class="login__item login__input">
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
                                        <li><hr class="m-0"></li>
                                        <li class="login__user">
                                            <form action="{{ route('dataUser', ['attr' => 'dates']) }}" method="post">
                                                @csrf
                                                <p class="text-center">Rango de incorporaciones</p>
                                                <div class="login__item">
                                                    @php
                                                    $end = date("d/m/Y");
                                                    $start = date("d/m/Y" , strtotime("-1 month"));
                                                    if (session()->has('accessADM')) {
                                                        if (!empty(session()->get('accessADM')->start))
                                                            $start = date("d/m/Y" , strtotime(session()->get('accessADM')->start));
                                                        if (!empty(session()->get('accessADM')->end))
                                                            $end = date("d/m/Y" , strtotime(session()->get('accessADM')->end));
                                                    } else {
                                                        if (!empty(auth()->guard('web')->user()->start))
                                                            $start = date("d/m/Y" , strtotime(auth()->guard('web')->user()->start));
                                                        if (!empty(auth()->guard('web')->user()->end))
                                                            $end = date("d/m/Y" , strtotime(auth()->guard('web')->user()->end));
                                                    }
                                                    @endphp
                                                    <div class="login__input">
                                                        <input name="datestart" value="{{ $start }}" title="Fecha Desde" class="form-control text-center datepicker date-incorporaciones" type="text" required>
                                                    </div>
                                                    <div class="login__input">
                                                        <input name="dateend" value="{{ $end }}" title="Fecha Hasta" class="form-control text-center datepicker date-incorporaciones" type="text" required>
                                                    </div>
                                                </div>
                                            </form>
                                        </li>
                                        <li><hr class="m-0"></li>
                                        @if (auth()->guard('web')->user()->isAdmin() || !empty(auth()->guard('web')->user()->permissions) && auth()->guard('web')->user()->isShowQuantity())
                                        <li class="login__user login__user--link">
                                            <a class="login__link text-success text-uppercase" href="{{ route('adm') }}"><i class="fas fa-user-shield"></i>Ir al admin</a>
                                        </li>
                                        @endif
                                        @if (!empty(auth()->guard('web')->user()->uid) || session()->has('accessADM'))
                                        <li class="login__user login__user--link">
                                            <a class="login__link" href="{{ route('client.action', ['cliente_action' => 'mis-datos']) }}"><i class="fas fa-id-card"></i>Mis datos</a>
                                        </li>
                                        @endif
                                        <li class="login__user login__user--link">
                                            <a class="login__link" href="{{ route('client.action', ['cliente_action' => 'mis-pedidos']) }}"><i class="fas fa-cash-register"></i>Mis pedidos</a>
                                        </li>
                                        @if (!auth()->guard('web')->user()->test)
                                        <li class="login__user login__user--link">
                                            <a class="login__link" href="{{ route('client.action', ['cliente_action' => 'analisis-deuda']) }}"><i class="far fa-chart-bar"></i>Análisis de deuda</a>
                                        </li>
                                        <li class="login__user login__user--link">
                                            <a class="login__link" href="{{ route('client.action', ['cliente_action' => 'faltantes']) }}"><i class="fas fa-layer-group"></i>Faltantes</a>
                                        </li>
                                        <li class="login__user login__user--link">
                                            <a class="login__link" href="{{ route('client.action', ['cliente_action' => 'comprobantes']) }}"><i class="fas fa-ticket-alt"></i>Comprobantes</a>
                                        </li>
                                        @endif
                                        <li><hr class="m-0"></li>
                                        @if (session()->has('accessADM'))
                                        <li class="login__user login__user--link">
                                            <a title="{{ session()->get('accessADM')->name }}" class="login__link" href="{{ URL::to('adm/clients/access:' . session()->get('accessADM')->uid) }}"><i class="fas fa-sign-out-alt text-danger"></i>Cerrar sesión del Cliente</a>
                                        </li>
                                        @else
                                        <li class="login__user login__user--link">
                                            <a class="login__link" href="{{ URL::to('logout') }}"><i class="fas fa-sign-out-alt text-danger"></i>Cerrar sesión</a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            @else
                                <a href="#" class="p-0 login__link" id="dropdownMenuLogin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle mr-2"></i>Zona de clientes
                                </a>
                                <div class="dropdown-menu dropdown-login shadow dropdown-menu-right border-0 mt-3 bg-transparent p-0" aria-labelledby="dropdownMenuLogin">
                                    <ul class="login">
                                        <form class="form" id="formLogueo" action="{{ \URL('/login/client') }}" method="post">
                                            {{ csrf_field() }}
                                            <li class="login__user">
                                                <div class="form-group mb-0">
                                                    <label for="username">Usuario (CUIT o Nro. cuenta)</label>
                                                    <input name="username" id="username-login" class="username-header form-control" value="{{ old('username') }}" onkeyup="verificarUsuario(this);" type="text" placeholder="Usuario" required>
                                                </div>
                                            </li>
                                            <li class="login__user">
                                                <div class="form-group mb-0">
                                                    <label for="password">Contraseña</label>
                                                    <input name="password" class="password-header form-control" type="password" placeholder="Contraseña" required>
                                                </div>
                                            </li>
                                            <li class="login__user">
                                                <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">ingresar</button>
                                            </li>
                                        </form>
                                        <li class="login__user login__lost">
                                            <p class="text-center mb-0"><a class="login__link login__link--unique" href="{{ route('password.request') }}">Olvidé mi contraseña</a></p>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            </div>
                        </div>
                        <form class="header__search" action="{{ route('redirect') }}" method="post">
                            @csrf
                            <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                            <input type="text" id="search__header" name="search" placeholder="Buscar en Ventor">
                            <label for="search__header">
                                <i class="fas fa-search"></i>
                            </label>
                        </form>
                        @if(isset($data) && ((auth()->guard('web')->check() && ((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))) && $page != 'checkout'))
                        <div class="header__cart">
                            <div class="dropdown">
                                <a href="#" class="p-0 btn-cart_product" data-total="{{ $data['cart']['elements'] }}" id="dropdownCart" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                                <div class="dropdown-menu dropdown-login shadow dropdown-menu-right border-0 mt-3 bg-transparent p-0" aria-labelledby="dropdownCart">
                                    {!! $data['cart']['html'] !!}
                                    {!! $data['cart']['totalHtml'] !!}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <nav>
                        @php
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