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
                    <div>
                        <button type="button" class="btn">
                            <i class="fas fa-search"></i>
                        </button>
                        <button id="button--user" type="button" class="btn header__user {{ auth()->guard('web')->check() ? 'header__user--login' : 'header__user--logout' }}">
                            <i class="fas fa-user"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>