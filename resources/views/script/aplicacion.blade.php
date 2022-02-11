@include('script.carrito')
<script>
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
    $('#appliedFilters').click(function (evt) {

        var data = $('#buscadorAjax').serializeArray();
        search(data);

    });
    $(document).on('click', '.elemFilter', function (evt) {

        let {value, name, element, remove = 1, clean = null} = $(this).data();
        if (clean !== null) {

            if (
                $(`#buscadorAjax [name="${element}"]`).length &&
                $(`#buscadorAjax [name="${element}"]`).val() != value
            ) {

                if (element == 'brand') {

                    $('#ventorProducts .loading__text strong').text('Modelos...');

                }
                if (element == 'model') {

                    $('#ventorProducts .loading__text strong').text('Años...')

                }
                if (element == 'year') {

                    $('#ventorProducts .loading__text strong').text('Productos...')

                }
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
            let {clean} = $(`.elemFilter[data-element="${element}"][data-value="${value}"]`).data();
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

    }).on('click', '.button--cart', function(evt) {

        var target = $(this).closest('.card__content');
        target.find('.card__cart').addClass('--active');

        }).on('click', '.card__cart__cancel', function (evt) {

        evt.preventDefault();
        var target = $(this).closest('.card__content');
        target.find('.card__cart').removeClass('--active');

    });
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
                    `<input type="radio" name="brand" class="elemFilter" data-clean="model|year" data-name="${brand.name}" data-element="brand" data-value="${brand.id}" value="${brand.id}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });
            $('#ventorProducts .loading__text strong').text('Modelos...');

        }
        if (resp.models !== undefined) {

            $('.js-select-model').parent().show();
            $('.js-select-model .filters__dropdown').html('');
            Object.keys(resp.models).forEach(index => {
                var model = resp.models[index];
                $('.js-select-model .filters__dropdown').append(`<label class="checkbox-container">` +
                    model.name+
                    `<input type="radio" name="model" class="elemFilter" data-clean="year" data-name="${model.name}" data-element="model" data-value="${model.id}" value="${model.id}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });
            $('#ventorProducts .loading__text strong').text('Años...')

        }
        if (resp.years !== undefined) {

            $('.js-select-year').parent().show();
            $('.js-select-year .filters__dropdown').html('');
            Object.keys(resp.years).forEach(index => {
                var year = resp.years[index];
                $('.js-select-year .filters__dropdown').append(`<label class="checkbox-container">` +
                    year.name+
                    `<input type="radio" name="year" class="elemFilter" data-name="${year.name}" data-element="year" data-value="${year.id}" value="${year.id}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });
            $('#ventorProducts .loading__text strong').text('Productos...')

        }
        if (resp.productsHTML) {

            if (isMobile) {

                $('#closeFilters').click();

            }
            $('#product-main').html(resp.productsHTML);
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
    $(document).ready(function(){

        let data = $('#buscadorAjax').serializeArray();
        search(data);

    });
</script>