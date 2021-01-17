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
<div class="nav__mobile nav__mobile--search nav__mobile--share" id="share-nav">
    <button type="button" class="close text-white" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="container-fluid">
        <div class="share">
            <div class="alert alert-warning">
                Genere y comparta su enlace para mostrar la lista de precios con su <i>markup</i> activo.
            </div>
            <form class="contact__form" onsubmit="event.preventDefault(); saveUrl(this);" action="{{ route('client.url') }}" method="post">
                @csrf
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="url-ventor">ventor.com.ar/link/</span>
                    </div>
                    <input id="url-share-ventor" value="{{ auth()->guard('web')->user()->url }}" onkeypress="return changeUrl(event, this);" maxlength="40" type="text" class="form-control form-control-lg" name="url" aria-describedby="url-ventor">
                </div>
                <small class="form-text text-muted">Solo caracteres alfanuméricos y el guión bajo.</small>
                <div class="d-flex mt-2 justify-content-end">
                    <button type="button" id="btn-url-copy" @empty(auth()->guard('web')->user()->url) disabled @endempty class="btn btn-light btn-lg mr-2"><i class="far fa-copy"></i></button>
                    <button type="submit" @empty(auth()->guard('web')->user()->url) disabled @endempty class="btn btn-lg btn-primary">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="nav__mobile nav__mobile--search" id="search-nav">
    <button type="button" class="close text-white" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <form action="{{ route('redirect') }}" method="post">
        @csrf
        <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
        <div class="search--container">
            <input placeholder="Estoy buscando..." required type="search" name="search" class="form-control border-0 form-control-lg">
        </div>
    </form>
</div>