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
        $(document).on('click', '.elemFilter', function (evt) {

            let {value, name, element, remove = 1, clean = null} = $(this).data();
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

        }).on('click', '#filterLabels .filters__labels__item i', function(){

            if ($(this).closest('.filters__labels__item').length > 0) {

                let {element, value} = $(this).closest('.filters__labels__item').data();
                if (element == 'part') {

                    $(`#part--${value} i, #part--${value} + .filters__item__dropdown__content`).removeClass('--active');
                    if ($(`#part--${value} + .filters__item__dropdown__content`).find('input:checked').length) {

                        $(`#buscadorAjax [name="subpart"]`).val('');
                        $(`#part--${value} + .filters__item__dropdown__content`).find('input:checked').prop('checked', false);

                    }

                }
                if (element == 'subpart' || element == 'brand' || element == 'type') {

                    $(`[name="${element}"][data-value="${value}"]`).prop('checked', false);

                }
                $(`#buscadorAjax [name="${element}"]`).val('');
                $(this).closest('.filters__labels__item').remove();

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
        $(".js-select-brand").click(function () {
            $(this).find('.filters__modal').toggleClass('--open');
        });
        $('#cleanFilters').click(function() {
            $(`[name="brand"]:checked, [name="type"]:checked, .filters__content [name="subpart"]:checked`).prop('checked', false);
            $(`[name="part"],[name="subpart"]`).val('');
            $('.filters__content .--active').removeClass('--active');
            $(``).prop('checked', false);
            $('#search').val('');
            $('#buscadorAjax').submit();
        });
        $('#buscadorAjax').submit(function(evt) {

            evt.preventDefault();
            var data = $('#buscadorAjax').serializeArray();
            search(data);

        });
        function newFilterLabel(elem){

            let {value, name, element, remove} = elem.data();
            var html = '<li class="filters__labels__item" data-element="'+element+'" data-value="'+value+'">'+
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
            $('#buscadorAjax .elemDelete').remove();
            $('#ventorProducts .overlay').removeClass('--active');
            $('.js-select-brand .filters__dropdown').html('');
            Object.keys(resp.brands).forEach(index => {
                var brand = resp.brands[index];
                $('.js-select-brand .filters__dropdown').append(`<label class="checkbox-container">` +
                    brand.name+
                    `<input ${resp.request && resp.request.brand && resp.request.brand == brand.slug ? 'checked' : ''} type="radio" name="brand" class="elemFilter" data-name="${brand.name}" data-element="brand" data-value="${brand.slug}" value="${brand.slug}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);

            });
            var urlData = {
                pathname: '/'+resp.slug,
                search: ''
            };
            historial.push(urlData);
            if (resp.request) {

                setTimeout(() => {

                    $(`#part--${resp.request.part} i, #part--${resp.request.part} + .filters__item__dropdown__content`).addClass('--active');
                    if (resp.request.subpart) {

                        $(`#part--${resp.request.part} + .filters__item__dropdown__content input[data-value="${resp.request.subpart}"]`).prop('checked', true);

                    }

                }, 300);

            }

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
                <input type="hidden" name="part" value="{{ $data['params'][0] ?? '' }}">
                <input type="hidden" name="subpart" value="{{ $data['params'][1] ?? '' }}">
                <input type="hidden" name="brand" class="elemDelete" value="{{ $data['params'][2] ?? '' }}">
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
                    <div class="" style="margin-top:10px;">
                        <div class="filters__item__flex__list">
                            <h4 class="filters__title filters__title--white filters__title--small">Productos en liquidación</h4>
                            <label class="switch">
                                <input type="radio" name="type" value="liquidacion" class="elemFilter" data-name="Productos en liquidación" data-element="type" data-value="liquidacion"/>
                                <span class="switch-slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="" style="margin-top:10px;">
                        <div class="filters__item__flex__list">
                            <h4 class="filters__title filters__title--white filters__title--small">Productos nuevos</h4>
                            <label class="switch">
                                <input type="radio" name="type" value="nuevos" class="elemFilter" data-name="Productos nuevos" data-element="type" data-value="nuevos"/>
                                <span class="switch-slider round"></span>
                            </label>
                        </div>
                    </div>
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