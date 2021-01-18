<div class="nav__mobile nav__mobile--user" id="slide-user">
    <nav>
        <ul class="nav__list">
            @auth('web')
                <li class="nav__element nav__element--name">
                    @if (session()->has('accessADM'))
                    Bienvenido<br/><strike>{{ auth()->guard('web')->user()["name"] }}</strike>
                    @else
                    Bienvenido<br/>{{ auth()->guard('web')->user()["name"] }}
                    @endif
                </li>
                <li class="nav__element nav__element--form">
                    <form action="{{ route('dataUser', ['attr' => 'markup']) }}" method="post">
                        @csrf
                        <div class="nav__form">
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
                <li class="nav__element nav__element--form">
                    <form action="{{ route('dataUser', ['attr' => 'dates']) }}" method="post">
                        @csrf
                        <div class="nav__form nav__form--21">
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
                            <input name="dateend" max="{{ $today }}" value="{{ $end }}" title="Fecha Hasta" class="form-control text-center border-top-0" type="date" required>
                            <button class="btn text-uppercase" type="submit">
                                Rango de<br>Incorporaciones
                            </button>
                        </div>
                    </form>
                </li>
                @if (!empty(auth()->guard('web')->user()->uid) || session()->has('accessADM'))
                <li class="nav__element nav__element--icon">
                    <a href="{{ route('client.action', ['cliente_action' => 'mis-datos']) }}"><i class="fas fa-id-card"></i>Mis datos</a>
                </li>
                @endif
                <li class="nav__element nav__element--icon">
                    <a href="{{ route('client.action', ['cliente_action' => 'mis-pedidos']) }}"><i class="fas fa-cash-register"></i>Mis pedidos</a>
                </li>
                @if (!auth()->guard('web')->user()->test)
                <li class="nav__element nav__element--icon">
                    <a href="{{ route('client.action', ['cliente_action' => 'analisis-deuda']) }}"><i class="far fa-chart-bar"></i>Análisis de deuda</a>
                </li>
                <li class="nav__element nav__element--icon">
                    <a href="{{ route('client.action', ['cliente_action' => 'faltantes']) }}"><i class="fas fa-layer-group"></i>Faltantes</a>
                </li>
                <li class="nav__element nav__element--icon">
                    <a href="{{ route('client.action', ['cliente_action' => 'comprobantes']) }}"><i class="fas fa-ticket-alt"></i>Comprobantes</a>
                </li>
                @endif
                @if (session()->has('accessADM'))
                <li class="nav__element nav__element--icon">
                    <a title="{{ session()->get('accessADM')->name }}" href="{{ URL::to('adm/clients/access:' . session()->get('accessADM')->uid) }}"><i class="fas fa-sign-out-alt"></i>Cerrar sesión del Cliente</a>
                </li>
                @else
                <li class="nav__element nav__element--icon">
                    <a href="{{ URL::to('logout') }}"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a>
                </li>
                @endif
            @endauth
            @unless (Auth::check())
                <li class="nav__element nav__element--name">
                    Ingrese a su cuenta
                </li>
                <li class="nav__element nav__element--form">
                    <form class="contact__form" id="formLogueo" action="{{ \URL('/login/client') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group mb-0">
                            <label for="username">Usuario (CUIT o Nro. cuenta)</label>
                            <input id="username" placeholder="Usuario" required type="text" value="{{ old('Usuario') }}" name="username" class="form-control">
                        </div>

                        <div class="form-group mb-0">
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" name="password" required placeholder="Contraseña" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-primary text-uppercase d-block mx-auto text-white px-5">ingresar</button>
                    </form>
                </li>
                <li class="nav__element">
                    <p class="text-center mb-0"><a class="text-primary" href="{{ route('password.request') }}">Olvidé mi contraseña</a></p>
                </li>
            @endunless
        </ul>
    </nav>
</div>
</div>