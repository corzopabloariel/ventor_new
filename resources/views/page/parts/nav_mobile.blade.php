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