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
<div id="headerItem">
    <header class="header">

        <div class="header__holder">

            <div class="logo">
                <a href="{{ \URL::to('/') }}">
                    <h1>
                        <picture>
                            <img srcset="https://ventor.com.ar/{{ $ventor->images['logo']['i'] }}" alt="{{config('app.name')}}">
                        </picture>
                    </h1>
                </a>
            </div>
            <ul class="social-nav">
                <li class="secondary-nav__item">
                    <a href="{{ \URL::to('productos') }}" class="secondary-nav__link secondary-nav__link--featured">
                        Productos
                    </a>
                </li>
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