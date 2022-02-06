@push("js")
    <script src='https://unpkg.com/vue/dist/vue.js'></script>
    <script src='https://unpkg.com/v-calendar'></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(async function() {

        if ($('.loadClientsHeader .--loader').length) {

            let response = await axios.post('{{ route('ventor.ajax.clients')}}', {fromHeader: 1});
            let {data} = response;
            if (data.client) {

                let {client} = data;
                let [element] = client.elements;
                let html = '<p style="width: 100%; line-height: initial;">' +
                    '<strong>Logueado como:</strong><br/>'+element.razonSocial +
                    '<br/>#'+element.nroCta+' / '+element.nroDoc +
                    '<br/><br/><a href="#" class="logoutClientsHeader"><strong>[SALIR]</strong></a>'
                '</p>';
                $('.loadClientsHeader').html(html);
                return;

            }
            let {clients} = data;
            clients.unshift({id: '', text: '', selected: 'selected', search:'', hidden:true });
            $('.loadClientsHeader').html('<select style="width: 100%"></select>');
            $('.loadClientsHeader select').select2({
                data: clients,
                placeholder: {
                    id: '',
                    text: 'Seleccione un cliente para el logueo',
                    selected:'selected',
                    search: '',
                    hidden: true
                }
            });

        }

    });
    $(document).on('click', '.editConfig', function(e) {

        e.preventDefault();
        editConfigAjax();

    }).on('click', '.login', function(e) {

        e.preventDefault();
        loginAjax();

    }).on('click', '.js-avatar-desktop', function () {

        $('.social-nav__menu').toggleClass('--open');

    }).on('click', '.modal-action', function(evt) {

        evt.preventDefault();
        openModal($(this).data('target'));

    }).on('click', '.closeModal', function(evt) {

        evt.preventDefault();
        closeModal();

    }).on('click', '.hamburger-nav', function () {

        $('.mobile-menu').addClass('expanded');
        $('.overlay_site').addClass('expanded');

    }).on('change', '.loadClientsHeader select', async function (e) {

        let userId = $(this).val();
        let response = await axios.post('{{ route('ventor.ajax.clientAction')}}', {type: 'access', userId});
        let {data} = response;
        if (!data.error) {

            location.reload();

        }
        console.log(data)

    }).on('click', '.logoutClientsHeader', async function(e) {

        e.preventDefault();
        let userId = null;
        let response = await axios.post('{{ route('ventor.ajax.clientAction')}}', {type: 'logout', userId});
        let {data} = response;
        if (!data.error) {

            location.reload();

        }
        console.log(data)

    });
    $('.mobile-menu__buttonclose').on('click', function(event) {

        event.preventDefault();
		$('.mobile-menu').removeClass('expanded');
		$('.overlay_site').removeClass('expanded');

    });
    $('#login_username').on('keyup', function(e) {

        let value = $(this).val().toUpperCase();
        if (
            value.indexOf( "EMP_" ) >= 0 ||
            value.indexOf( "VND_" ) >= 0
        ) {

            $('#login_password').prop('disabled', true);

        } else {

            $('#login_password').prop('disabled', false);

        }

    })
    @auth
    new Vue({
        el: '#app',
        data() {
            return {
                range: {
                    start: new Date(@auth @isset(Auth::user()->start) '{{Auth::user()->start}} 00:00:00' @endisset @endauth),
                    end: new Date(@auth @isset(Auth::user()->end) '{{Auth::user()->end}} 00:00:00' @endisset @endauth),
                },
                masks: {
                    input: 'DD/MM/YYYY',
                },
                start: new Date(@auth @isset(Auth::user()->start) '{{Auth::user()->start}} 00:00:00' @endisset @endauth).toISOString().substr(0, 10),
                end: new Date(@auth @isset(Auth::user()->end) '{{Auth::user()->end}} 00:00:00' @endisset @endauth).toISOString().substr(0, 10)
            }
        },
        methods: {
            updateInputs(date, opts) {
                const calendar = this.$refs.calendar;
                const [start, end] = calendar.dateParts;
                this.start = start.date.toISOString().substr(0, 10);
                this.end = end.date.toISOString().substr(0, 10);
            },
        },
    })
    @endauth

    function openModal(modal) {

        if ($('.mobile-menu.expanded').length) {

            $('.mobile-menu').removeClass('expanded');

        }
        $('.overlay_site').addClass('expanded');
        $('.centeredModal').addClass('--active');
        $('.modal').removeClass('--active');
        $(modal).addClass('--active');

    }
    function closeModal() {

        $('.overlay_site').removeClass('expanded');
        $('.centeredModal').removeClass('--active');
        $('.modal').removeClass('--active');

    }
    async function editConfigAjax() {
        
        var slug = window.location.pathname;
        var data = $("#formConfigUser").serializeArray();
        var response = await axios.post('{{ route('dataUser')}}', {data, route: 'users'});
        var dataResponse = response.data;
        console.log(data)

        return false;

    }
    async function loginAjax() {

        var response = await axios.post('{{ route('login')}}/client', {
            username: $('#login_username').val(),
            password: $('#login_password').val()
        });
        var {data} = response;
        if (!data.error) {

            location.reload();

        }
        console.log(data)

    }
    </script>
@endpush
<div id="headerItem">
    <nav class="nav">
        <div class="nav__holder">
            <ul>
                <li class="">
                    <a href="{{\url::to('empresa')}}">Empresa</a>
                </li>
                <li class="">
                    <a href="{{\url::to('descargas')}}">Descargas</a>
                </li>
                <li class="">
                    <a href="{{\url::to('productos')}}">Productos</a>
                </li>
                @auth
                <li class="">
                    <a href="{{\url::to('aplicacion')}}">Aplicación</a>
                </li>
                @endauth
                <li class="">
                    <a href="{{\url::to('calidad')}}">Calidad</a>
                </li>
                <li class="">
                    <a href="{{\url::to('contacto')}}">Contacto</a>
                </li>
            </ul>
            <ul>
                <li class="">
                    <a href="{{\url::to('atencion/transmision')}}">Análisis de transmisión</a>
                </li>
                <li class="">
                    <a href="{{\url::to('atencion/pagos')}}">Información sobre pagos</a>
                </li>
            </ul>
        </div>
    </nav>
    @php
    $type = pathinfo('http://staticbcp.ventor.com.ar/img/logo.png', PATHINFO_EXTENSION);
    $image = array(
        'base64'    => 'data:image/'.$type.';base64,'.base64_encode(file_get_contents('http://staticbcp.ventor.com.ar/img/logo.png')),
        'url'       => 'http://staticbcp.ventor.com.ar/img/logo.png'
    );
    @endphp
    <header class="header">

        <div class="header__holder">

            <div class="logo">
                <a href="{{ \URL::to('/') }}">
                    <h1>
                        <picture>
                            <img srcset="{{$image['base64']}}" alt="{{config('app.name')}}">
                        </picture>
                    </h1>
                </a>
            </div>
            <ul class="mobile-nav">
                <li class="mobile-nav__item">
                    <div class="hamburger-nav">
                        <button class="hamburger hamburger--elastic" type="button" aria-label="Menu" aria-controls="navigation">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </li>
            </ul>

            <ul class="social-nav">
                @if (Auth::check())
                <li>
                    <div class="config">
                        <a href="#" class="modal-action" data-target="#modalConfigUser">
                            <i class="fas fa-user-cog"></i>
                        </a>
                    </div>
                </li>
                <li class="social-nav__item">
                    <div class="avatar js-avatar-desktop">
                        <i class="fas fa-user mobile-nav__link--user --active">
                            <div class="mobile-nav__link--user__count"></div>
                        </i>
                        <p class="avatar__title"><span>Hola <strong>{{ Auth::user()->name }}</strong></span><i class="avatar__arrow fas fa-caret-down"></i></p>
                    </div>
                    <ul class="social-nav__menu" style="width: 100%; max-width: 250px">
                        @if (Auth::user()->isAdmin())
                        <li class="social-nav__item">
                            <a target="_blank" href="{{ route('adm') }}" class="main-nav__link goToPanel">
                                <i class="fas fa-user-shield"></i>Ir al ADMIN
                            </a>
                        </li>
                        @endif
                        @php
                        $permissions = \Auth::user()->permissions;
                        @endphp
                        @if (
                            Auth::user()->isAdmin() ||
                            (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['read']))
                        )
                        <li class="social-nav__item">
                            <div class="loadClientsHeader">
                                <div class="info --loader">Clientes</div>
                            </div>
                            <div style="line-height: initial; margin-bottom: 0; margin-top: 5px; padding: .5625rem; border-radius: .5rem" class="alert-errors --alert">
                                <div>
                                    <i class="fas fa-exclamation-triangle"></i> Usará el carrito del cliente seleccionado.
                                    @if (Auth::user()->isAdmin())
                                    Esto solo podrán ver los <strong>ADMIN</strong> y los que tienen permiso de <strong>ver Clientes</strong>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <hr/>
                        @endif
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
                            <a href="{{ URL::to('logout') }}" class="social-nav__link button button--secondary-text logoutUser"><i class="fas fa-sign-out-alt"></i>Salir</a>
                        </li>
                    </ul>
                </li>
                @else
                <li class="social-nav__item">
                    <a href="#" class="button button--primary-outline modal-action" data-target="#modalLoginUser"><i class="fas fa-user-circle"></i>Iniciá sesión</a> 
                </li>
                @endif
            </ul>

        </div>

    </header>
</div>

<div class="mobile-menu">
    <a href="#" class="mobile-menu__buttonclose">
        <i class="modal__close fas fa-times"></i>
    </a>
    <div class="mobile-menu__list">
        <ul class="mobile-menu__list__inner">
            @if (Auth::check())
                <div class="avatar avatar--mobile">
                    <div class="avatar__content">
                        <p class="avatar__title"><i class="fas fa-user mobile-nav__link--user --active"></i><span>Hola <strong>{{ Auth::user()->name }}</strong></span></p> 
                        <a href="#" class="secondary-nav__link goToPanel"><i class="fas fa-user-edit"></i>Editar perfil</a>
                        <a href="#" class="secondary-nav__link modal-action" data-target="#modalConfigUser"><i class="fas fa-user-cog"></i>Configuración</a>
                        <a href="{{ URL::to('logout') }}" class="secondary-nav__link logoutUser"><i class="fas fa-sign-out-alt"></i>Salir</a>           
                    </div>
                </div>
                @if (Auth::user()->isAdmin())
                <li>
                    <a href="{{ route('adm') }}" class="main-nav__link goToPanel">
                        <i class="fas fa-user-shield"></i>Ir al ADMIN
                    </a>
                </li>
                @endif
                @php
                $permissions = \Auth::user()->permissions;
                @endphp
                @if (
                    Auth::user()->isAdmin() ||
                    (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['read']))
                )
                <li>
                    <div class="loadClientsHeader">
                        <div class="info --loader">Clientes</div>
                    </div>
                    <div style="line-height: initial; margin-bottom: 0; margin-top: 5px; padding: .5625rem; border-radius: .5rem" class="alert-errors --alert">
                        <div>
                            <i class="fas fa-exclamation-triangle"></i> Usará el carrito del cliente seleccionado.
                            @if (Auth::user()->isAdmin())
                            Esto solo podrán ver los <strong>ADMIN</strong> y los que tienen permiso de <strong>ver Clientes</strong>
                            @endif
                        </div>
                    </div>
                </li>
                <hr/>
                @endif
                @if (!\Auth::user()->test)
                    <li>
                        <a href="{{ route('client.action', ['cliente_action' => 'analisis-deuda']) }}" class="main-nav__link goToPanel">
                            <i class="far fa-chart-bar"></i>Análisis de deuda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.action', ['cliente_action' => 'faltantes']) }}" class="main-nav__link goToPanel">
                            <i class="fas fa-layer-group"></i>Faltantes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.action', ['cliente_action' => 'comprobantes']) }}" class="main-nav__link goToPanel">
                            <i class="fas fa-ticket-alt"></i>Comprobantes
                        </a>
                    </li>
                @endif
            @else
            <li>
                <a href="#" class="social-nav__link button button--primary-text modal-action" data-target="#modalLoginUser"><i class="fas fa-user-circle"></i>Iniciá sesión</a>
            </li>
            @endif
        </ul>
        <ul class="mobile-menu__list__inner">
            <li class="">
                <a class="secondary-nav__link" href="{{\url::to('empresa')}}">Empresa</a>
            </li>
            <li class="">
                <a class="secondary-nav__link" href="{{\url::to('descargas')}}">Descargas</a>
            </li>
            <li class="">
                <a class="secondary-nav__link" href="{{\url::to('productos')}}">Productos</a>
            </li>
            @auth
            <li class="">
                <a class="secondary-nav__link" href="{{\url::to('aplicacion')}}">Aplicación</a>
            </li>
            @endauth
            <li class="">
                <a class="secondary-nav__link" href="{{\url::to('calidad')}}">Calidad</a>
            </li>
            <li class="">
                <a class="secondary-nav__link" href="{{\url::to('contacto')}}">Contacto</a>
            </li>
        </ul>
        <ul class="mobile-menu__list__inner">
            <li class="">
                <a class="secondary-nav__link" href="{{\url::to('atencion/transmision')}}">Análisis de transmisión</a>
            </li>
            <li class="">
                <a class="secondary-nav__link" href="{{\url::to('atencion/pagos')}}">Información sobre pagos</a>
            </li>
        </ul>
    </div>
</div>

<div class="overlay_site"></div>

<div class="centeredModal">

    <div class="modal" id="modalLoginUser">
        <i class="modal__close fas fa-times closeModal"></i>
        <div class="modal__content">
            <h3 class="modal__content__title --action"><i class="fas fa-sign-in-alt"></i> Acceso</h3>
            <form id="formLoginUser">
                <div class="modal__inner">
                    <div class="text-hr">
                        <span>Datos</span>
                    </div>
                    <input id="login_username" type="text" placeholder="Usuario" name="username" class="input" style="margin-top:0;" />
                    <input id="login_password" type="password" placeholder="Contraseña" name="password" class="input" />
                </div>
            </form>
            <div class="modal__footer">
                <button type="button" class="button button--black-outline closeModal">Cerrar</button>
                <button type="button" class="button button--primary login">Iniciar sesión</button>
            </div>
        </div>

	</div>
    <!-- Configuración del usuario -->
    @auth
    <div class="modal" id="modalConfigUser">
        <i class="modal__close fas fa-times closeModal"></i>
        <div class="modal__content">
            <h3 class="modal__content__title --action"><i class="fas fa-user-cog"></i> Configuración</h3>
            <p class="modal__content__text"><strong>Estas son las configuraciones<br>para pedidos:</strong></p>
            <form id="formConfigUser">
                <div class="modal__inner">
                    <div class="text-hr">
                        <span>Rango de incorporaciones</span>
                    </div>
                    <div id="app">
                        <v-date-picker
                            ref="calendar"
                            v-model="range"
                            mode="date"
                            :masks="masks"
                            :max-date='new Date()'
                            is-range
                            @dayclick="updateInputs"
                        >
                            <template v-slot="{ inputValue, inputEvents, isDragging }">
                                <input type="hidden" name="start" :value="start">
                                <input type="hidden" name="end" :value="end">
                                <div class="modal__grid"
                                    v-on="inputEvents.start"
                                >
                                    <span>@{{inputValue.start}}</span><span>@{{inputValue.end}}</span>
                                </div>
                            </template>
                        </v-date-picker>
                    </div>
                    <div class="text-hr">
                        <span>Markup</span>
                    </div>
                    <input value="{{ Auth::user()->discount }}" type="number" name="discount" class="input" min="0" style="margin-top:0; text-align: center;" >
                </div>
            </form>
            <div class="modal__footer">
                <button type="button" class="button button--black-outline closeModal">Cerrar</button>
                <button type="button" class="button button--primary editConfig">Guardar</button>
            </div>
        </div>

	</div>
    @endauth
</div>