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
    $(document).on('click', '.js-avatar-desktop', function () {
        $('.social-nav__menu').toggleClass('--open');
    });
    </script>
@endpush
<div id="headerItem">
    <header class="header">

        <div class="header__holder">

            <div class="hamburger-nav">
                <div class="hamburger">
                    <div class="_layer -top"></div>
                    <div class="_layer -mid"></div>
                    <div class="_layer -bottom"></div>
                </div>
            </div>
            <div class="logo">
                <a href="{{ \URL::to('/') }}">
                    <h1>
                        <picture>
                            <img srcset="https://ventor.com.ar/{{ $ventor->images['logo']['i'] }}" alt="{{config('app.name')}}">
                        </picture>
                    </h1>
                </a>
            </div>
            <ul class="mobile-nav">
                @if (Auth::check())
                <li class="mobile-nav__item">
                    <a class="mobile-nav__link">
                        <i class="fas fa-user mobile-nav__link--user --active">
                            <div class="mobile-nav__link--user__count"></div>
                        </i>
                    </a>
                </li>
                @else
                    <li class="mobile-nav__item">
                    <a class="mobile-nav__link">
                        <i class="fas fa-user mobile-nav__link--user"></i>
                    </a>
                    </li>
                @endif 
                <li class="mobile-nav__item">
                    <a href="#" class="mobile-nav__link hamburger js-mobile-nav-hamburger">
                    <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="social-nav">
                @if (Auth::check())
                <li class="social-nav__item">
                    <div class="avatar js-avatar-desktop">
                        <i class="fas fa-user mobile-nav__link--user --active">
                            <div class="mobile-nav__link--user__count"></div>
                        </i>
                        <p class="avatar__title"><span>Hola <strong>{{ Auth::user()->name }}</strong></span><i class="avatar__arrow fas fa-caret-down"></i></p>
                    </div>
                    <ul class="social-nav__menu">
                        <li class="social-nav__item">
                            <a href="{{ route('client.action', ['cliente_action' => 'mis-pedidos']) }}" class="main-nav__link goToPanel">
                                <i class="fas fa-cash-register"></i>Mis Pedidos
                            </a>
                        </li>
                        <li class="social-nav__item">
                            <a href="{{ route('client.action', ['cliente_action' => 'analisis-deuda']) }}" class="main-nav__link goToPanel">
                                <i class="far fa-chart-bar"></i>Análisis de deuda
                            </a>
                        </li>
                        <li class="social-nav__item">
                            <a href="{{ route('client.action', ['cliente_action' => 'faltantes']) }}" class="main-nav__link goToPanel">
                                <i class="fas fa-layer-group"></i>Faltantes
                            </a>
                        </li>
                        <li class="social-nav__item">
                            <a href="{{ route('client.action', ['cliente_action' => 'comprobantes']) }}" class="main-nav__link goToPanel">
                                <i class="fas fa-ticket-alt"></i>Comprobantes
                            </a>
                        </li>
                        <hr>
                        <li class="social-nav__item">
                            <a href="{{ route('client.action', ['cliente_action' => 'mis-datos']) }}" class="social-nav__link button button--secondary-text goToPanel">
                                <i class="fas fa-id-card"></i>Mi perfil
                            </a>
                        </li>
                        <li class="social-nav__item">
                            <a href="{{ route('client.action', ['cliente_action' => 'mis-datos']) }}" class="social-nav__link button button--secondary-text goToPanel">
                                <i class="fas fa-user-cog"></i>Configuración
                            </a>
                        </li>
                        <li class="social-nav__item">
                            <a class="social-nav__link button button--secondary-text logoutUser"><i class="fas fa-sign-out-alt"></i>Salir</a>
                        </li>
                    </ul>
                </li>
                @else
                <li class="social-nav__item">
                    <a href="" class="button button--primary-outline goToPanel redirectToPanel"><i class="fas fa-user-circle"></i>Iniciá sesión</a> 
                </li>
                @endif
            </ul>

        </div>

    </header>
</div>
{{--<header class="header listing">
    <div class="container-fluid">
        <div class="container__header">
            <div class="header__logo">
                <a href="{{ \URL::to('/') }}">
                    <img src="{{ asset() }}" alt="{{  }}" srcset="">
                </a>
            </div>
            <div class="header__nav">
                @php
                $page = $data["page"] ?? "";
                @endphp
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
</header>--}}