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
{{$time}}
<section class="section__holder" id="sectionList">
    <h2 class="listing__title" id="listadoTitulo">
        @isset($elements['total']['products'])
        <span>{{$elements['total']['products']}}</span> producto{{$elements['total']['products'] > 1 ? 's' : ''}}
        @endisset
    </h2>
    <div class="listing__content">
        <div class="filters">
            <form action="" method="post" id="buscadorAjax">
                <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                <input type="hidden" name="part" value="{{ $params[0] ?? '' }}">
                <input type="hidden" name="subpart" value="{{ $params[1] ?? '' }}">
                <input type="hidden" name="brand" class="elemDelete" value="{{ $params[2] ?? '' }}">
                <div class="filters__top">
                    <div class="filters__header__top">
                        <h4 class="filters__title filters__title--filters  filters__title--white">Filtros aplicados</h4> 
                        <button class="button button--secondary-text" type="button" id="cleanFilters">
                            <i class="fas fa-trash"></i>Limpiar
                        </button>
                    </div>
                    <ul class="filters__labels" id="filterLabels">{!!$elements['filtersLabels'] ?? ''!!}</ul>
                </div>
                <div class="filters__header">
                    @include("filters.search")
                    @include("filters.markup")
                    @include("filters.brands_select")
                    <div class="" style="margin-top:10px;">
                        <div class="filters__item__flex__list">
                            <h4 class="filters__title filters__title--white filters__title--small">Productos en liquidación</h4>
                            <label class="switch">
                                <input type="radio" @if(isset($type) && $type == 'liquidacion') checked @endif name="type" value="liquidacion" class="elemFilter" data-name="Productos en liquidación" data-element="type" data-value="liquidacion"/>
                                <span class="switch-slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="" style="margin-top:10px;">
                        <div class="filters__item__flex__list">
                            <h4 class="filters__title filters__title--white filters__title--small">Productos nuevos</h4>
                            <label class="switch">
                                <input type="radio" @if(isset($type) && $type == 'nuevos') checked @endif name="type" value="nuevos" class="elemFilter" data-name="Productos nuevos" data-element="type" data-value="nuevos"/>
                                <span class="switch-slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="filters__content">
                    @include("page.elements.__lateral", ['elements' => $lateral])
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
                <span class="desktop-filter-bar__title">Ordenar por:</span>
                <div class="form-item form-item--select-icon">
                    <i class="fas fa-caret-down"></i>
                    <select class="select orderFilter" id="orderByProducts">
                        <option @if($orderBy == 'code') selected @endif value="code">Código</option>
                        <option @if($orderBy == 'name') selected @endif value="name">Nombre</option>
                    </select>
                </div>
            </div>
            @auth
            <div class="tab-selector">

                <div class="tab-selector__item --pdf">
                    <i class="fas fa-file-pdf"></i>
                    <span>Descargar</span>
                </div>

            </div>
            @endauth
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
                {{--@include("page.elements.__clients")

                @include('page.elements.__action_user')
                @if (auth()->guard('web')->check())
                    @include('page.elements.__products_table')
                @else--}}
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