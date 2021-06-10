@push("js")
    <script src="{{ asset('js/mobile/nav.js') }}"></script>
@endpush
<div class="sidenav-overlay" id="sidenav-overlay"></div>
<div class="nav__mobile" id="slide-out">
    <nav>
        <ul class="nav__list">
            <li class="nav__element nav__element--logo"></li>
            @php
            $page = $data["page"] ?? "/";
            if ($page == "parte" || $page == "subparte" || $page == "producto" || $page == "pedido")
                $page = "productos";
            @endphp
            {!! $ventor->sitemap("mobile", $page, "nav__element") !!}
        </ul>
    </nav>
</div>
<div class="nav__mobile nav__mobile--search" id="search-nav">
    <button type="button" class="close text-white" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <form action="{{ route('redirect') }}" method="post">
        @csrf
        <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
        <div class="container__search">
            <input placeholder="Buscar en Ventor" required type="search" name="search" class="form-control border-0 form-control-lg">
        </div>
    </form>
</div>