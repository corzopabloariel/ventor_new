@push('styles')
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/color.js') }}"></script>
    <script src="{{ asset('js/solver.js') }}"></script>
@endpush
<section>
    <h2 class="listing__title" id="listadoTitulo"><span>{{$data['elements']['total']['products']}}</span> producto{{$data['elements']['total']['products'] > 1 ? 's' : ''}}</h2>
    <div class="listing__content">
        <div class="filters">
            <div class="filters__top">
                <div class="filters__header__top">
                    <h4 class="filters__title filters__title--filters  filters__title--white">Filtros aplicados</h4> 
                    <button class="button button--secondary-text" id="cleanFilters">
                        <i class="fas fa-trash"></i>Limpiar
                    </button>
                </div>
                <ul class="filters__labels" id="filterLabels">{!!$data['elements']['filtersLabels']!!}</ul>
            </div>
            <div class="filters__header">
                @include("filters.search")
                @include("filters.brands_select")
            </div>
            <div class="filters__content">
                @include("page.elements.__lateral", ['elements' => $data["lateral"]])
            </div>
            <div class="filters__footer">
                <a class="button button--black-outline --mobile" id="closeFilters">Cerrar</a>
                <a class="button button--primary --mobile" id="appliedFiltersMobile">Aplicar</a>
                <a class="button button--primary --desktop" id="appliedFilters">Aplicar filtros</a>
            </div>
        </div>
        
        <div class="desktop-filter-bar">
            <div class="desktop-filter-bar__flex">
                <span class="desktop-filter-bar__title">Ordenar por:</span>
                <div class="form-item form-item--select-icon">
                    <i class="fas fa-caret-down"></i>
                    <select class="select orderFilter">
                        <option value="code">Código</option>
                        <option value="name">Nombre</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="listing__cards">
            <div class="container-fluid">
                @include("page.elements.__breadcrumb")
                @include("page.elements.__clients")
                <form action="{{ route('redirect') }}" method="post">
                    @csrf
                    <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                    @isset($data['elements']['request']['part'])
                    <input type="hidden" name="part" value="{{ $data['elements']['request']['part'] }}">
                    @endisset
                    @isset($data['elements']['request']['subpart'])
                    <input type="hidden" name="subpart" value="{{ $data['elements']['request']['subpart'] }}">
                    @endisset
                </form>
                @include('page.elements.__action_user')
                @if (auth()->guard('web')->check())
                    @include('page.elements.__products_table')
                @else
                <div class="container__products" id="product-main">
                    {!! $data['elements']['productsHTML'] !!}
                </div>
                @endif
                @if ($data["elements"]["products"]->total() == 0)
                    @include('page.elements.__not_found')
                @else
                <div class="main--footer">
                    <div class="table-responsive">
                        <div class="table-responsive d-flex justify-content-center">
                            {{ $data["elements"]["products"]->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>