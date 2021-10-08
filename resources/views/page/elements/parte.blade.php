@push('styles')
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/color.js') }}"></script>
    <script src="{{ asset('js/solver.js') }}"></script>
    <script src="https://unpkg.com/history/umd/history.production.min.js"></script>
    <script>
        var historial = window.HistoryLibrary.createBrowserHistory();
        $(".filters__item__dropdown").click(function (evt) {

            if ($(evt.target).is('a')) return;
            $(`.filters__item .--active`).toggleClass('--active');
            $(this).find("+ .filters__item__dropdown__content").toggleClass("--active");
            $(this).find("i").toggleClass("--active");

        });
        $(".elemFilter").click(function (evt) {

            let {value, name, element, remove, clean = null} = $(this).data();
            if (clean !== null && $(`.filters__labels__item[data-element="${clean}"]`).length == 1) {

                $(`.filters__labels__item[data-element="${clean}"]`).remove();
                $(`#buscadorAjax [name="${clean}"]`).val('');

            }
            if ($(`.filters__labels__item[data-element="${element}"]`).length == 1) {

                $(`.filters__labels__item[data-element="${element}"] span`).html(name+'<i class="fas fa-times"></i>');
                $(`#buscadorAjax [name="${element}"]`).val(value);

            } else {

                newFilterLabel($(this));

            }

        });
        $("#appliedFilters").click(function (evt) {

            var data = $('#buscadorAjax').serializeArray();
            data.push({
                name: 'orderBy',
                value: $('#orderByProducts').val()
            });
            search(data);

        });
        function newFilterLabel(elem){

            let {value, name, element, remove} = elem.data();
            var html = '<li class="filters__labels__item" data-element="'+element+'">'+
                '<span class="filter-label">'+
                    name+'<i class="fas fa-times"></i>'+
                '</a>'+
            '</li> ';
            $('#filterLabels').append(html);
            $(`#buscadorAjax [name="${element}"]`).val(value);

        }
        function results(resp){

            $('#product-main').html(resp.productsHTML);
            $('#filterLabels').html(resp.filtersLabels);
            $('#listadoTitulo').html(resp.title);
            $('#ventorProducts .overlay').removeClass('--active');
            var urlData = {
                pathname: '/'+resp.slug,
                search: ''
            };
            historial.push(urlData);
            setTimeout(() => {

                $(`#part--${resp.request.part} i, #part--${resp.request.part} + .filters__item__dropdown__content`).addClass('--active');
                if (resp.request.subpart) {

                    $(`#part--${resp.request.part} + .filters__item__dropdown__content input[data-value="${resp.request.subpart}"]`).prop('checked', true);

                }

            }, 500);

        }
        async function search(params){

            var sectionList = document.getElementById('sectionList');
            window.scrollTo({
                top: sectionList.offsetTop-200,
                left: 0,
                behavior: 'smooth'
            });

            $('#ventorProducts .overlay').addClass('--active');
            let response = await axios.post('{{ route('ventor.ajax.products')}}', params);
            let {data} = response;
            results(data);

        }

        $(document).ready(function(){

            let data = $('#buscadorAjax').serializeArray();
            data.push({
                name: 'orderBy',
                value: $('#orderByProducts').val()
            });
            search(data);

        });
    </script>
@endpush
<section class="section listing" id="sectionList">
    <h2 class="listing__title" id="listadoTitulo">
        @isset($data['elements']['total']['products'])
        <span>{{$data['elements']['total']['products']}}</span> producto{{$data['elements']['total']['products'] > 1 ? 's' : ''}}
        @endisset
    </h2>
    <div class="listing__content">
        <div class="filters">
            <form action="" method="post" id="buscadorAjax">
                <input type="hidden" name="route" value="{{ auth()->guard('web')->check() ? 'order' : 'products' }}">
                @isset($data['args'])
                    <input type="hidden" name="part" value="{{ $data['args'][0] }}">
                    @isset($data['args'][1])
                    <input type="hidden" name="subpart" value="{{ $data['args'][1] }}">
                    @endisset
                @endisset
                <div class="filters__top">
                    <div class="filters__header__top">
                        <h4 class="filters__title filters__title--filters  filters__title--white">Filtros aplicados</h4> 
                        <button class="button button--secondary-text" id="cleanFilters">
                            <i class="fas fa-trash"></i>Limpiar
                        </button>
                    </div>
                    <ul class="filters__labels" id="filterLabels">{!!$data['elements']['filtersLabels'] ?? ''!!}</ul>
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
            </form>
        </div>
        
        <div class="desktop-filter-bar">
            <div class="desktop-filter-bar__flex">
                <span class="desktop-filter-bar__title">Ordenar por:</span>
                <div class="form-item form-item--select-icon">
                    <i class="fas fa-caret-down"></i>
                    <select class="select orderFilter" id="orderByProducts">
                        <option @if($data['orderBy'] == 'code') selected @endif value="code">Código</option>
                        <option @if($data['orderBy'] == 'name') selected @endif value="name">Nombre</option>
                    </select>
                </div>
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
                @include("page.elements.__clients")

                @include('page.elements.__action_user')
                {{--@if (auth()->guard('web')->check())
                    @include('page.elements.__products_table')
                @else--}}
                <div class="container__products" id="product-main">
                    {!! $data['elements']['productsHTML'] ?? '' !!}
                </div>
                {{--@endif
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
                @endif--}}
            </div>
        </div>
    </div>
</section>