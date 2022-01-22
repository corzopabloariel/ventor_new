@push('styles')
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
@endpush
<div class="cart__products">
    <div class="cart__products--container">
        <div class="cart__products--header">
            <h3>Tu pedido</h3>
            <a class="cart__products--close" href="#">
                <i class="fas fa-times"></i>
            </a>
        </div>
        <div class="cart__products--body">
            <div class="loading">
                <div class="loading__animation">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <p class="loading__text">Cargando <strong>Pedido...</strong></p>
            </div>
            <div class="cart__products--elements"></div>
        </div>
        <div class="cart__products--footer" data-step="0">
            <div class="line">
                <span class="cart-total">Total</span>
                <h3 class="cart-price">$ 0,00</h3>
                <small class="cart-detail">El total no incluye IVA ni impuestos internos</small>
            </div>
            <hr>
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->role != 'USR')
            <div class="line line--normal load loadClients" style="margin-top: 0;">
                <div class="info">-- Click para cargar clientes --</div>
            </div>
            <div class="line line--normal load loadTransports">
                <div class="info">-- Click para cargar transportes --</div>
            </div>
            <hr>
            @endif
            <div class="line line--normal">
                <textarea id="orderObservations" aria-label="orderObservations" placeholder="Observaciones"></textarea>
            </div>
            <div class="line line--normal">
                <button id="orderBtn" type="button" @if (auth()->guard('web')->check() && auth()->guard('web')->user()->role != 'USR') disabled @endif class="button button--primary --desktop">Confirmar pedido</button>
            </div>
        </div>
        <div class="cart__products--footer" style="display: none;" data-step="1">
            <div class="line">
                <span class="cart-total">Total</span>
                <h3 class="cart-price">$ 0,00</h3>
                <small class="cart-detail">El total no incluye IVA ni impuestos internos</small>
            </div>
            <div class="line line--normal">
                <button id="orderFinish" type="button" disabled class="button button--primary --desktop">Confirmar pedido</button>
            </div>
            <div class="line line--normal" style="display: none;">
                <button id="orderClose" type="button" disabled class="button button--primary-outline --desktop">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<section class="section__holder" id="sectionList">
    <h2 class="listing__title" id="listadoTitulo">
        @isset($elements['total']['products'])
        <span>{{$elements['total']['products']}}</span> producto{{$elements['total']['products'] > 1 ? 's' : ''}}
        @endisset
    </h2>
    <div class="listing__content">
        <div class="filters">
            <form action="" method="post" id="buscadorAjax">
                @isset($params[0]) <input type="hidden" name="brand" class="elemDelete" value="{{ $params[0] }}"> @endisset
                @isset($params[1]) <input type="hidden" name="model" class="elemDelete" value="{{ $params[1] }}"> @endisset
                @isset($params[2]) <input type="hidden" name="year" class="elemDelete" value="{{ $params[2] }}"> @endisset
                <div class="filters__top">
                    <div class="filters__header__top">
                        <h4 class="filters__title filters__title--filters  filters__title--white">Limpiaparabrisas</h4>
                    </div>
                    <ul class="filters__labels" id="filterLabels">{!!$elements['filtersLabels'] ?? ''!!}</ul>
                </div>
                <div class="filters__header">
                    @include("filters.markup")
                </div>
                <div class="filters__content">
                    @include("filters.brands_select", ['dark' => true])
                    @include("filters.models_select", ['dark' => true])
                    @include("filters.years_select", ['dark' => true])
                </div>
                <div class="filters__footer">
                    <a class="button button--black-outline --mobile" id="closeFilters">Cerrar</a>
                    <a class="button button--primary --mobile" id="appliedFiltersMobile">Aplicar</a>
                    <a class="button button--primary --desktop" id="appliedFilters">Aplicar filtros</a>
                </div>
            </form>
        </div>
        <div class="desktop-filter-bar">
            <div class="desktop-filter-bar__flex">
                <span class="desktop-filter-bar__title">Aplicaciones</span>
            </div>

            <div class="tab-selector">

            </div>
        </div>
        <div class="listing__cards">
            <div id="ventorProducts">
                <div class="overlay">
                    <div class="loading">
                        <div class="loading__animation">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <p class="loading__text">Cargando <strong>Productos...</strong></p>
                    </div>
                </div>
                <div class="container__products" id="product-main">
                    {!! $elements['productsHTML'] ?? '' !!}
                </div>
                <div class="paginator"></div>
            </div>
        </div>
    </div>
    <div class="fixed-footer fixed-footer--full">
        <a class="button button--primary-fuchsia showFilters">
            <i class="fas fa-filter"></i>Filtrar
        </a>
    </div>
</section>