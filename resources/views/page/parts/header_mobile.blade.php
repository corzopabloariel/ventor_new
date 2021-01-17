@push('js')
    <script src="{{ asset('js/mobile/header.js') }}"></script>
@endpush
<div class="header">
    <header>
        <div class="container-fluid">
            <div class="header__container">
                <div>
                    @if (!session()->has('user_share'))
                    <button id="button--nav" type="button" class="header__menu btn btn-lg">
                        <i class="fas fa-bars"></i>
                    </button>
                    @endif
                </div>
                <div>
                    <a href="{{ \URL::to('/') }}">
                        <img class="header__logo" src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ env('APP_NAME') }}" srcset="">
                    </a>
                </div>
                <div class="header__end">
                    @if (!session()->has('user_share'))
                    <div class="header__btns @unless (Auth::check()) header__btns--simple @endunless">
                        <button type="button" class="btn btn-sm p-0 header__search">
                            <i id="btn-search" class="header__search--icon"></i>
                        </button>
                        @auth('web')
                            @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                            <button type="button" class="mt-2 btn btn-sm p-0 header__cart" data-user="{{ auth()->guard('web')->user()->role }}">
                                <i class="header__cart--icon" id="btn-cart_product" data-products="{{ session()->has('cart') ? count(session()->get('cart')) : 0 }}"></i>
                            </button>
                            @endif
                        @endauth
                        <button id="button--user" type="button" class="btn header__user {{ auth()->guard('web')->check() ? 'header__user--login' : 'header__user--logout' }}">
                            <i class="fas fa-user"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </header>
</div>