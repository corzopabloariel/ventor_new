@push('styles')
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('js')
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/history/umd/history.production.min.js"></script>
    <script>
        var historial = window.HistoryLibrary.createBrowserHistory();
        $('.filters__item__dropdown').click(function (evt) {

            if ($(evt.target).is('a')) return;
            $(`.filters__item .--active`).toggleClass('--active');
            $(this).find("+ .filters__item__dropdown__content").toggleClass("--active");
            $(this).find("i").toggleClass("--active");

        });
        $('.markup').on('change', async function(evt) {

            let type = $(this).val();
            let response = await axios.post('{{ route('ventor.ajax.markup')}}', {type});
            let {data} = response;
            if (!data.error) {

                if (data.type == 'venta') {

                    $('.button--cart').remove();
                    $('.cart__float').remove();

                } else {

                    $('.card__buttons.cart__primary').append('<button class="button button--primary button--cart"><i class="fas fa-shopping-cart"></i></button>');
                    $('body').prepend('<div class="cart__float"><div class="--count">'+data.cart.element+'</div><i class="fas fa-shopping-cart"></i></div>');

                }
                updatePrices();

            }

        });
        $('.tab-selector__item.--pdf').on('click', function() {

            var btn = $(this);
            btn.addClass('--loader');
            btn.find('span').text('Espere...');
            $('#appliedFilters').prop('disabled', true);
            var href = window.location.href;
            pdf(href,
                response => {

                    var {data} = response;
                    btn.removeClass('--loader');
                    btn.find('span').text('Descargar');
                    var file = new Blob([data], {type: 'application/pdf'});
                    var fileURL = URL.createObjectURL(file);
                    window.open(fileURL);

                },
                error => {

                    btn.removeClass('--loader');
                    btn.find('span').text('Descargar');

                }
            );

        });
        $('.loadClients .info').on('click', async function() {

            $(this).text('Espere').addClass('--loader');
            let response = await axios.post('{{ route('ventor.ajax.clients')}}');
            let {data} = response;
            let {clients} = data;
            clients.unshift({id: '', text: '', selected: 'selected', search:'', hidden:true });
            $('.loadClients').html('<select></select>');
            $('.loadClients select').select2({
                data: clients,
                placeholder: {
                    id: '',
                    text: 'Seleccione un cliente',
                    selected:'selected',
                    search: '',
                    hidden: true
                }
            });
            if (!$('.loadTransports select').length) {

                $('.loadTransports .info').click();

            }

        });
        $('.loadTransports .info').on('click', async function() {

            $(this).text('Espere').addClass('--loader');
            let response = await axios.post('{{ route('ventor.ajax.transports')}}');
            let {data} = response;
            let {transports} = data;
            transports.unshift({id: '', text: '', selected: 'selected', search:'', hidden:true });

            $('.loadTransports').html('<select></select>');
            $('.loadTransports select').select2({
                data: transports,
                placeholder: {
                    id: '',
                    text: 'Seleccione un transporte',
                    selected: 'selected',
                    search: '',
                    hidden: true
                }
            });

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

        }).on('click', '.paginator__item a',function(e){

            e.preventDefault();
            var slug = $(this).attr('href');
            paginator(slug);

        }).on('click', '.button--cart', function(evt) {

            var target = $(this).closest('.card__content');
            target.find('.card__cart').addClass('--active');

        }).on('click', '.card__cart__cancel', function (evt) {

            evt.preventDefault();
            var target = $(this).closest('.card__content');
            target.find('.card__cart').removeClass('--active');

        }).on('click', '.button--confirm', async function(evt) {

            var {code} = $(this).data();
            var orderTmp = {};
            if (localStorage.orderTmp !== undefined) {

                orderTmp = JSON.parse(localStorage.orderTmp);

            }
            var quantity = $(`.card__product input[data-code="${code}"]`).val();
            if (orderTmp['__'+code] !== undefined && quantity != '0') {

                quantity = orderTmp['__'+code];
                delete orderTmp['__'+code];
                localStorage.orderTmp = JSON.stringify(orderTmp);

            }
            quantity = parseInt(quantity);
            if (quantity == 0) {

                var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {code, quantity, append: false});
                var {data} = response;
                if (!data.error) {

                    removeProductCart(null, data.elements, code, 0);

                }

            } else {

                var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {code, quantity, append: true});
                var {data} = response;
                if (!data.error) {

                    appendProductCart(data.elements, code, quantity);

                }

            }

        }).on('click', '.button--stock', async function(evt) {

            var {code} = $(this).data();
            var response = await axios.post('{{ route('ventor.ajax.stock')}}', {code});
            var {data} = response;
            if (!data.error) {

                $(this).addClass(data.color);
                if (Number.isInteger(data.stock)) {

                    $(this).text(data.stock);

                }
            }

        }).on('click', '.cart__float', async function(evt) {

            var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {show: true});
            var {data} = response;
            if (!data.error && data.productsHTML != '') {

                $('body').addClass('show--cart');
                $('.cart__products--elements').html(data.productsHTML);
                $('.cart__products--body .loading').addClass('--hidden');
                $('.cart__products--footer h3').text(data.elements.price.string);

            }
            if (!data.error && data.productsHTML == '') {

                swal({
                    title: 'Elija un producto',
                });

            }

        }).on('click', '.cart__product--remove', async function(evt) {

            evt.preventDefault();
            var target = $(this).closest('.cart__product');
            var {code} = $(this).data();
            var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {code, quantity: 0, append: false});
            var {data} = response;
            if (!data.error) {

                removeProductCart(target, data.elements, code, 0);

            }
        }).on('click', '.cart__products--close', function(evt) {

            evt.preventDefault();
            $('body').removeClass('show--cart');
            $('.cart__products--elements').html('');
            $('.cart__products--footer h3').text('$ 0,00');
            $('.cart__products--body .loading').removeClass('--hidden');

        }).on('change', '.cart__product__price .product--quantity input', async function(evt) {

            if (window.disabledAction !== undefined && window.disabledAction) {

                console.warn('No puede cambiar la cantidad del producto');
                return;

            }
            var input = $(this);
            var target = input.closest('.cart__product__price');
            var step = input.attr('step');
            var quantity = input.val();
            var {code} = input.data();

            if (quantity != 0) {

                var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {code, quantity, append: true});
                var {data} = response;
                if (!data.error) {

                    var product = data.elements.data.find(p => p.product == code);
                    target.find('.product.product--total').text(product.price.total.string);
                    $('.cart__products--footer h3').text(data.elements.price.string);
                    if ($('.button.button--primary.button--cart[data-code="'+code+'"]').length) {

                        $('.button.button--primary.button--cart[data-code="'+code+'"]').text(quantity);
                        $('.card__cart .card__product input[data-code="'+code+'"]').val(quantity);

                    }

                }

            } else {

                swal({
                    title: "¿Esta seguro de eliminar el producto del pedido?",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "NO",
                            value: null
                        },
                        confirm: {
                            text: "SI",
                            value: true
                        }
                    },
                })
                .then(async (removeProduct) => {

                    if (removeProduct) {

                        var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {code, quantity, append: false});
                        var {data} = response;

                        if (!data.error) {

                            removeProductCart(target.closest('.cart__product'), data.elements, code, quantity);

                        }

                    } else {

                        input.val(step).trigger('change');

                    }

                });

            }

        }).on('change', '.loadClients select, .loadTransports select', function (e) {

            if ($('.loadClients select').length && $('.loadTransports select').length && $('.loadClients select').val() != '' && $('.loadTransports select').val() != '') {

                $('#orderBtn').prop('disabled', false);

            } else {

                $('#orderBtn').prop('disabled', true);

            }

        }).on('change', '.loadClients select', async function (e) {

            var client = $(this).val();
            if (client == '') {
                if ($('.loadTransports select').length) {

                    $('.loadTransports select').val('').trigger("change");

                }
                return;
            }
            var response = await axios.post(`{{ route('ventor.ajax.client') }}`, {client});
            var {data} = response;
            if (!data.error) {

                var [clientResponse] = data.elements;
                if ($('.loadTransports select').length) {

                    $('.loadTransports select').val(clientResponse.transport?.code).trigger("change");

                }

            }

        });
        $('#orderFinish').click(async function(evt) {

            var btn = $(this);
            btn.addClass('--loader').text('Descargando, espere...');
            $('#appliedFilters').prop('disabled', true);
            var href = '{{ URL::to("/") }}/api/order.pdf/'+window.orderNew.order.id;
            pdf(href,
                response => {

                    var {data} = response;
                    btn.removeClass('--loader').text('Descargar PDF');
                    var file = new Blob([data], {type: 'application/pdf'});
                    var fileURL = URL.createObjectURL(file);
                    window.open(fileURL);

                },
                error => {

                    btn.removeClass('--loader').text('Descargar PDF');

                },
                true
            );

        });
        $('#orderClose').click(function(evt) {

            $('.cart__products--header').html('<h3>Tu pedido</h3><a class="cart__products--close" href="#"><i class="fas fa-times"></i></a>');
            $('.cart__products--footer[data-step="0"]').show();
            $('.cart__products--footer[data-step="1"]').hide();
            $('#orderFinish, #orderClose').prop('disabled', true);
            $('.loadClients select').val('').trigger("change");
            $('.cart__products--close, #appliedFilters').click();
            $('.cart__float').remove();
            delete window.disabledAction;
            delete window.orderNew;

        });
        $('#orderBtn').click(async function (evt) {

            var btn = $(this);
            window.disabledAction = true;
            btn.addClass('--loader').text('Espere...');
            $('#orderFinish').addClass('--loader').text('Espere...');
            $('.cart__products--close, .cart__product--remove').remove();
            $('.product.product--quantity input').prop('disabled', true);
            $('.cart__products--footer[data-step="0"]').hide();
            $('.cart__products--footer[data-step="1"]').show();
            /////////
            var data = {
                observations: $('#orderObservations').val(),
                client: $('.loadClients select').length && $('.loadClients select').val() != '' ? parseInt($('.loadClients select').val()) : null,
                transport: $('.loadTransports select').length && $('.loadTransports select').val() != '' ? parseInt($('.loadTransports select').val()) : null
            };
            var response = await axios.post('{{ route('ventor.ajax.order.new')}}', data);
            var {data} = response;
            if (!data.error) {

                window.orderNew = data.elements;
                btn.removeClass('--loader').text('Confirmar pedido');
                $('.cart__products--header h3').text(`Pedido #${data.elements.order.uid}`)
                $('#orderFinish').removeClass('--loader').text('Descargar PDF');
                $('#orderFinish, #orderClose').prop('disabled', false);
                $('#orderClose').parent().show();
                // TODO: Programar envio de mail del pedido

            }

        });
        $('#appliedFilters').click(function (evt) {

            var data = $('#buscadorAjax').serializeArray();
            data.push({
                name: 'orderBy',
                value: $('#orderByProducts').val()
            });
            search(data);

        });
        $('.js-select-brand').click(function () {

            $(this).find('.filters__modal').toggleClass('--open');

        });
        $('#cleanFilters').click(function() {

            $(`[name="brand"]:checked, [name="type"]:checked, .filters__content [name="subpart"]:checked`).prop('checked', false);
            $(`[name="part"],[name="subpart"]`).val('');
            $('.filters__content .--active').removeClass('--active');
            $('#search').val('');
            $('#buscadorAjax').submit();

        });
        $('#buscadorAjax').submit(function(evt) {

            evt.preventDefault();
            var data = $('#buscadorAjax').serializeArray();
            search(data);

        });
        function appendProductCart(elements, code, quantity) {

            if ($('.cart__float .--count').length) {

                $('.cart__float .--count').text(elements.total);

            } else {

                $('body').prepend('<div class="cart__float"><div class="--count">'+elements.total+'</div><i class="fas fa-shopping-cart"></i></div>');

            }
            $('.button.button--primary.button--cart[data-code="'+code+'"]').text(quantity);
            $('.card__cart__cancel[data-code="'+code+'"]').click();
            if ($('.button.button--primary.button--confirm[data-code="'+code+'"][data-order="0"]').length) {

                $('.button.button--primary.button--confirm[data-code="'+code+'"][data-order="0"]').text('Modificar el pedido');
                $('.button.button--primary.button--confirm[data-code="'+code+'"][data-order="0"]').attr('data-order', '1');
                $('.button.button--primary.button--confirm[data-code="'+code+'"]').closest('.card').addClass('--order');

            }

        }
        function removeProductCart(row, elements, code, quantity) {

            if (row) {

                row.remove();

            }
            $('.cart__float .--count').text(elements.total);
            $('.cart__products--footer h3').text(elements.price.string);
            if ($('.button.button--primary.button--confirm[data-code="'+code+'"][data-order="1"]').length) {

                $('.button.button--primary.button--confirm[data-code="'+code+'"][data-order="1"]').text('Agregar al pedido');
                $('.button.button--primary.button--confirm[data-code="'+code+'"][data-order="1"]').attr('data-order', '0');
                $('.button.button--primary.button--confirm[data-code="'+code+'"]').closest('.card').removeClass('--order');
                $('.button.button--primary.button--cart[data-code="'+code+'"]').html('<i class="fas fa-shopping-cart"></i>');
                $('.card__cart .card__product input[data-code="'+code+'"]').val(quantity);

            }
            if (elements.data.length == 0) {

                $('.cart__products--close').click();

            }

        }
        function newFilterLabel(elem){

            let {value, name, element, remove} = elem.data();
            var html = ' <li class="filters__labels__item" data-element="'+element+'" data-value="'+value+'">'+
                '<span class="filter-label">'+
                    name+'<i class="fas fa-times"></i>'+
                '</a>'+
            '</li>';
            $('#filterLabels').append(html);
            $(`#buscadorAjax [name="${element}"]`).val(value);

        }
        function updatePrices() {

            let productsPrice = document.querySelectorAll('.card__price__aux');
            if (productsPrice.length > 0) {
                var dataPromise = Array.prototype.map.call(productsPrice, product => {
                    let {code} = product.dataset;
                    return new Promise((resolve, reject) => {
                        axios.post('{{ route('ventor.ajax.prices')}}', {code}).
                            then(resolve)
                    })
                });
                Promise.all(dataPromise)
                    .then(responses => {
                        responses.forEach(response => {

                            let {data} = response;
                            if (data != '') {

                                if (!data.error) {

                                    $(`.card__price__aux[data-code="${data.code}"]`).text(data[data.markup].string);

                                }

                            }

                        });
                    });
            }

        }
        function results(resp) {

            if (!$('.cart__float .--count').length && !resp.cart.error && resp.cart.elements !== undefined) {

                $('body').prepend('<div class="cart__float"><div class="--count">'+resp.cart.elements.total+'</div><i class="fas fa-shopping-cart"></i></div>');

            }
            $('#product-main').html(resp.productsHTML);
            $('#filterLabels').html(resp.filtersLabels);
            $('#listadoTitulo').html(resp.title);
            $('#buscadorAjax .elemDelete').remove();
            $('#ventorProducts .overlay').removeClass('--active');
            $('.js-select-brand .filters__dropdown').html('');
            $('.paginator').html(resp.paginator);
            updatePrices();
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
        function pdf(slug, response, error, isOrder = false) {

            axios({
                url: isOrder ? slug : '{{ route('ventor.ajax.pdf')}}',
                method: 'POST',
                data: {slug},
                responseType: 'blob',
            })
            .then(response)
            .catch(error);

        }
        function decrement(btn, code) {

            var target = $(btn).closest('.card__product');
            var input = target.find('input');
            var {step, min} = input.data();
            var orderTmp = {};
            if (localStorage.orderTmp !== undefined) {

                orderTmp = JSON.parse(localStorage.orderTmp);

            }
            if ((parseInt(input.val()) - parseInt(step)) != 0 && min <= (parseInt(input.val()) - parseInt(step))) {

                input.val(parseInt(input.val()) - parseInt(step));
                orderTmp['__'+code] = input.val();

            } else {

                if ($('.button.button--primary.button--confirm[data-code="'+code+'"][data-order="1"]').length && $('.button.button--primary.button--confirm[data-code="'+code+'"]').text() == 'Modificar el pedido') {

                    $('.button.button--primary.button--confirm[data-code="'+code+'"][data-order="1"]').text('Quitar del pedido');

                }
                input.val(0);
                delete orderTmp['__'+code];

            }
            localStorage.orderTmp = JSON.stringify(orderTmp);

        }
        function increment(btn, code) {

            var target = $(btn).closest('.card__product');
            var input = target.find('input');
            var {step} = input.data();
            var orderTmp = {};
            if (localStorage.orderTmp !== undefined) {

                orderTmp = JSON.parse(localStorage.orderTmp);

            }
            if ($('.button.button--primary.button--confirm[data-code="'+code+'"]').text() == 'Quitar del pedido') {

                $('.button.button--primary.button--confirm[data-code="'+code+'"]').text(
                    $('.button.button--primary.button--confirm[data-code="'+code+'"]').data('order') == '0'
                        ? 'Agregar al pedido'
                        : 'Modificar el pedido'
                );

            }
            input.val(parseInt(step) + parseInt(input.val()));
            orderTmp['__'+code] = input.val();
            localStorage.orderTmp = JSON.stringify(orderTmp);

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
        async function paginator(slug){

            var sectionList = document.getElementById('sectionList');
            window.scrollTo({
                top: sectionList.offsetTop-200,
                left: 0,
                behavior: 'smooth'
            });

            $('#ventorProducts .overlay').addClass('--active');
            let response = await axios.post('{{ route('ventor.ajax.paginator')}}', {slug});
            let {data} = response;
            results(data);

        }

        $(document).ready(function(){

            let data = $('#buscadorAjax').serializeArray();
            data.push({
                name: 'orderBy',
                value: $('#orderByProducts').val()
            });
            data.push({
                name: 'page',
                value: '{{$data['currentPage']}}'
            });
            search(data);

        });
    </script>
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
{{$data['time']}}
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
                        <button class="button button--secondary-text" type="button" id="cleanFilters">
                            <i class="fas fa-trash"></i>Limpiar
                        </button>
                    </div>
                    <ul class="filters__labels" id="filterLabels">{!!$data['elements']['filtersLabels'] ?? ''!!}</ul>
                </div>
                <div class="filters__header">
                    @include("filters.search")
                    @include("filters.markup")
                    @include("filters.brands_select")
                    <div class="" style="margin-top:10px;">
                        <div class="filters__item__flex__list">
                            <h4 class="filters__title filters__title--white filters__title--small">Productos en liquidación</h4>
                            <label class="switch">
                                <input type="radio" @if(isset($data['type']) && $data['type'] == 'liquidacion') checked @endif name="type" value="liquidacion" class="elemFilter" data-name="Productos en liquidación" data-element="type" data-value="liquidacion"/>
                                <span class="switch-slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="" style="margin-top:10px;">
                        <div class="filters__item__flex__list">
                            <h4 class="filters__title filters__title--white filters__title--small">Productos nuevos</h4>
                            <label class="switch">
                                <input type="radio" @if(isset($data['type']) && $data['type'] == 'nuevos') checked @endif name="type" value="nuevos" class="elemFilter" data-name="Productos nuevos" data-element="type" data-value="nuevos"/>
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

            <div class="tab-selector">

                <div class="tab-selector__item --pdf">
                    <i class="fas fa-file-pdf"></i>
                    <span>Descargar</span>
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
                {{--@include("page.elements.__clients")

                @include('page.elements.__action_user')
                @if (auth()->guard('web')->check())
                    @include('page.elements.__products_table')
                @else--}}
                <div class="container__products" id="product-main">
                    {!! $data['elements']['productsHTML'] ?? '' !!}
                </div>
                <div class="paginator"></div>
            </div>
        </div>
    </div>
</section>