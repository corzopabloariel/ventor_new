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
                <div>
                    <button id="button--nav" type="button" class="header__menu btn btn-lg">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div>
                    <a href="{{ \URL::to('/') }}">
                        <img class="header__logo" src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ config('app.name') }}" srcset="">
                    </a>
                </div>
                <div class="header__end">
                    @php
                    $class = "header__btns--simple";
                    if (Auth::check()) {
                        if(isset($data) && ((auth()->guard('web')->check() && ((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))) && ($page != 'checkout' && $page != 'confirm')))
                            $class = "";
                    }
                    @endphp
                    <div class="header__btns {{ $class }}">
                        <button type="button" class="btn btn-sm p-0 header__search">
                            <i id="btn-search" class="fas fa-search header__search--icon"></i>
                        </button>
                        @auth('web')
                            @if(isset($data) && ((auth()->guard('web')->check() && ((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))) && ($page != 'checkout' && $page != 'confirm')))
                            <button type="button" id="header__cart" class="mt-2 btn btn-sm p-0 header__cart" data-user="{{ auth()->guard('web')->user()->role }}">
                                <i class="fas fa-shopping-cart header__cart--icon" id="btn-cart_product" data-products="{{ session()->has('cart') ? count(session()->get('cart')) : 0 }}"></i>
                            </button>
                            @endif
                        @endauth
                        <button id="button--user" type="button" class="btn header__user {{ auth()->guard('web')->check() ? 'header__user--login' : 'header__user--logout' }}">
                            <i class="fas fa-user"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>