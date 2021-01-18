@push('js')
    <script src="{{ asset('js/mobile/header.js') }}"></script>
@endpush
<div class="header">
    <header>
        <div class="container-fluid">
            <div class="header__container">
                <div>
                    <button id="button--nav" type="button" class="header__menu btn btn-lg">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div>
                    <a href="{{ \URL::to('/') }}">
                        <img class="header__logo" src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ env('APP_NAME') }}" srcset="">
                    </a>
                </div>
                <div class="header__end">
                    <div class="header__btns">
                        <button type="button" class="btn btn-sm p-0 header__search">
                            <i id="btn-search" class="fas fa-search header__search--icon"></i>
                        </button>
                        @auth('web')
                            @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                            <button type="button" class="mt-2 btn btn-sm p-0 header__cart" data-user="{{ auth()->guard('web')->user()->role }}">
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