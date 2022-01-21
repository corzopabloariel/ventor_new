<script src="https://unpkg.com/history/umd/history.production.min.js"></script>
<script src="{{ asset('js/owl-carousel/owl.carousel.min.js') }}"></script>
<script>
    var historial = window.HistoryLibrary.createBrowserHistory();
    $('.listing-lowered .owl-carousel').owlCarousel({
        loop: false,
        margin: 0,
        stagePadding: 0,
        nav: true,
        dots: true,
        navText: ['<span class="fas fa-chevron-left"></span>','<span class="fas fa-chevron-right"></span>'],
        responsive: {
            0:{
                items:1,
                stagePadding: 30
            },
            600:{
                items:2,
                stagePadding: 30
            },
            1000:{
                items:4,
                stagePadding: 0
            }
        }
    });
    $('.js-select-brand .select, .js-select-model .select, .js-select-year .select').click(function () {

        $(this).parent().find('.filters__modal').toggleClass('--open');

    });
    $('#appliedFiltersMobile').click(function() {

        var data = $('#buscadorAjax').serializeArray();
        search(data, true);

    });
    $('.showFilters').on('click',function() {

        $('body').addClass('body--no-scroll');
        $('.filters').addClass('--active');

    });
    $('#appliedFilters').click(function (evt) {

        var data = $('#buscadorAjax').serializeArray();
        search(data);

    });
    $('#closeFilters').on('click', function() {

        $('body').removeClass('body--no-scroll');
        $('.filters').removeClass('--active');

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
    $(document).on('click', '.elemFilter', function (evt) {

        let {value, name, element, remove = 1, clean = null} = $(this).data();console.log({value, name, element, remove, clean})
        if (clean !== null) {

            if (
                $(`#buscadorAjax [name="${element}"]`).length &&
                $(`#buscadorAjax [name="${element}"]`).val() != value
            ) {

                clean.split('|').forEach(c => {

                    $(`.js-select-${c}`).parent().hide();
                    if ($(`.filters__labels__item[data-element="${c}"]`).length == 1) {

                        $(`.filters__labels__item[data-element="${c}"]`).remove();
                        if ($(`#buscadorAjax [name="${c}"]`).length) {

                            $(`#buscadorAjax [name="${c}"]`).remove();

                        }

                    }

                });

            }

        }
        if ($(`.filters__labels__item[data-element="${element}"]`).length == 1) {

            $(`.filters__labels__item[data-element="${element}"] span`).html(name+'<i class="fas fa-times"></i>');
            $(`#buscadorAjax [name="${element}"]`).val(value);

        } else {

            newFilterLabel($(this));

        }
        if ($(this).closest('.filters__modal').hasClass('--open')) {

            $(this).closest('.filters__modal').removeClass('--open');

        }

    }).on('click', '#filterLabels .filters__labels__item i', function(){

        if ($(this).closest('.filters__labels__item').length > 0) {

            let {element, value} = $(this).closest('.filters__labels__item').data();
            let {clean} = $(`.elemFilter[data-value="${value}"]`).data();
            if (clean) {

                clean.split('|').forEach(c => {

                    $(`.js-select-${c}`).parent().hide();
                    if ($(`.filters__labels__item[data-element="${c}"]`).length == 1) {

                        $(`.filters__labels__item[data-element="${c}"]`).remove();
                        if ($(`#buscadorAjax [name="${c}"]`).length) {

                            $(`#buscadorAjax [name="${c}"]`).remove();

                        }

                    }

                });

            }
            if ($(`#buscadorAjax [name="${element}"]`).is('input[type="radio"]')) {

                $(`#buscadorAjax [name="${element}"]`).prop('checked', false);

            }
            $(this).closest('.filters__labels__item').remove();

        }

    }).on('click', '.button--stock', async function(evt) {

        $(this).addClass('--loader');
        var {code} = $(this).data();
        var response = await axios.post('{{ route('ventor.ajax.stock')}}', {code});
        var {data} = response;
        $(this).removeClass('--loader');
        if (!data.error) {

            $(this).addClass(data.color);
            if (Number.isInteger(data.stock)) {

                $(this).text(data.stock);

            }

        } else {

            Toast.fire({
                icon: 'error',
                title: data.message
            });

        }

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

    }).on('click', '.cart__float', async function(evt) {

        var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {show: true});
        var {data} = response;
        if (!data.error && data.productsHTML != '') {

            $('body').addClass('show--cart');
            $('.cart__products--elements').html(data.productsHTML);
            $('.cart__products--body .loading').addClass('--hidden');
            $('.cart__products--footer h3').text(data.elements.price.string);
            setTimeout(() => {

                $('.cart__products--container').addClass('--active');

            });

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
        var questionResponse = await question('¿Seguro de eliminar el producto del pedido?', '', 'warning', 'No', 'Si, borrar el producto');
        if (questionResponse.isConfirmed) {

            var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {code, quantity: 0, append: false});
            var {data} = response;
            if (!data.error) {

                removeProductCart(target, data.elements, code, 0);

            }

        }

    }).on('click', '.cart__products--close', function(evt) {

        evt.preventDefault();
        $('body').removeClass('show--cart');
        $('.cart__products--container').removeClass('--active');
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

            var questionResponse = await question('¿Seguro de eliminar el producto del pedido?', '', 'warning', 'No', 'Si, borrar el producto');
            if (questionResponse.isConfirmed) {

                var response = await axios.post('{{ route('ventor.ajax.cart.products')}}', {code, quantity, append: false});
                var {data} = response;

                if (!data.error) {

                    removeProductCart(target.closest('.cart__product'), data.elements, code, quantity);

                }

            } else {

                input.val(step).trigger('change');

            }

        }

    });
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
            $('.cart__float').remove();

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
    function results(resp, isMobile = false) {

        $('#product-main').html('');
        $('#buscadorAjax .elemDelete').remove();
        $('#ventorProducts .overlay').removeClass('--active');
        if (resp.brands !== undefined) {

            $('.js-select-brand .filters__dropdown').html('');
            Object.keys(resp.brands).forEach(index => {
                var brand = resp.brands[index];
                $('.js-select-brand .filters__dropdown').append(`<label class="checkbox-container">` +
                    brand.name+
                    `<input type="radio" name="brand" class="elemFilter" data-clean="model|year" data-name="${brand.name}" data-element="brand" data-value="${brand.slug}" value="${brand.slug}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });

        }
        if (resp.models !== undefined) {

            $('.js-select-model').parent().show();
            $('.js-select-model .filters__dropdown').html('');
            Object.keys(resp.models).forEach(index => {
                var model = resp.models[index];
                $('.js-select-model .filters__dropdown').append(`<label class="checkbox-container">` +
                    model.name+
                    `<input type="radio" name="model" class="elemFilter" data-clean="year" data-name="${model.name}" data-element="model" data-value="${model.slug}" value="${model.slug}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });

        }
        if (resp.years !== undefined) {

            $('.js-select-year').parent().show();
            $('.js-select-year .filters__dropdown').html('');
            Object.keys(resp.years).forEach(index => {
                var year = resp.years[index];
                $('.js-select-year .filters__dropdown').append(`<label class="checkbox-container">` +
                    year+
                    `<input type="radio" name="year" class="elemFilter" data-name="${year}" data-element="year" data-value="${year}" value="${year}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });

        }
        if (resp.productsHTML) {

            if (isMobile) {

                $('#closeFilters').click();

            }
            $('#product-main').html(resp.productsHTML);
            updatePrices();
            if (!$('.cart__float .--count').length && resp.cart && !resp.cart.error && resp.cart.elements !== undefined && resp.cart.elements.total != 0) {

                $('body').prepend('<div class="cart__float"><div class="--count">'+resp.cart.elements.total+'</div><i class="fas fa-shopping-cart"></i></div>');

            }

        }
        if (resp.request) {

            $('.js-select-brand .filters__dropdown input[value="'+resp.request.brand+'"]').trigger('click');
            $('.js-select-model .filters__dropdown input[value="'+resp.request.model+'"]').trigger('click');
            $('.js-select-year .filters__dropdown input[value="'+resp.request.year+'"]').trigger('click');

        }
        if (resp.slug !== undefined) {

            var urlData = {
                pathname: '/'+resp.slug,
                search: ''
            };
            historial.push(urlData);

        }

    }
    async function search(params, isMobile = false){

        var sectionList = document.getElementById('sectionList');
        window.scrollTo({
            top: sectionList.offsetTop-200,
            left: 0,
            behavior: 'smooth'
        });

        $('#ventorProducts .overlay').addClass('--active');
        let response = await axios.post('{{ route('ventor.ajax.applications')}}', params);
        let {data} = response;
        results(data, isMobile);

    }
    $(document).ready(function(){

        let data = $('#buscadorAjax').serializeArray();
        search(data);

    });
</script>