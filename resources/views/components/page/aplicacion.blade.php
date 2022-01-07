
<section class="section__holder" id="sectionList">
    <h2 class="listing__title" id="listadoTitulo">
        @isset($elements['total']['products'])
        <span>{{$elements['total']['products']}}</span> producto{{$elements['total']['products'] > 1 ? 's' : ''}}
        @endisset
    </h2>
    <div class="listing__content">
        <div class="filters">
            <form action="" method="post" id="buscadorAjax">
                <input type="hidden" name="brand" value="{{ $params[0] ?? '' }}">
                <input type="hidden" name="model" value="{{ $params[1] ?? '' }}">
                <input type="hidden" name="year" class="elemDelete" value="{{ $params[2] ?? '' }}">
                <div class="filters__top">
                    <div class="filters__header__top" style="margin-bottom: 0">
                        <h4 class="filters__title filters__title--filters  filters__title--white">Limpiaparabrisas</h4>
                    </div>
                </div>
                <div class="filters__header">
                    @include("filters.markup")
                    @include("filters.brands_select")
                    @include("filters.models_select")
                    @include("filters.years_select")
                </div>
                <div class="filters__footer">
                    <a class="button button--black-outline --mobile" id="closeFilters">Cerrar</a>
                    <a class="button button--primary --mobile" id="appliedFiltersMobile">Aplicar</a>
                    <a class="button button--primary --desktop" id="appliedFilters">Aplicar filtros</a>
                </div>
            </form>
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