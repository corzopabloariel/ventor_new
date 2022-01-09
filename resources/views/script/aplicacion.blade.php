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

    $('#appliedFilters').click(function (evt) {

        var data = $('#buscadorAjax').serializeArray();
        search(data);

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

    })
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
    function results(resp) {

        /*if (!$('.cart__float .--count').length && !resp.cart.error && resp.cart.elements !== undefined && resp.cart.elements.total != 0) {

            $('body').prepend('<div class="cart__float"><div class="--count">'+resp.cart.elements.total+'</div><i class="fas fa-shopping-cart"></i></div>');

        }*/
        console.log(resp)
        $('#buscadorAjax .elemDelete').remove();
        $('#ventorProducts .overlay').removeClass('--active');
        if (resp.elements.brands !== undefined) {

            $('.js-select-brand .filters__dropdown').html('');
            Object.keys(resp.elements.brands).forEach(index => {
                var brand = resp.elements.brands[index];
                $('.js-select-brand .filters__dropdown').append(`<label class="checkbox-container">` +
                    brand.name+
                    `<input type="radio" name="brand" class="elemFilter" data-clean="model|year" data-name="${brand.name}" data-element="brand" data-value="${brand.slug}" value="${brand.slug}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });

        }
        if (resp.elements.models !== undefined) {

            $('.js-select-model').parent().show();
            $('.js-select-model .filters__dropdown').html('');
            Object.keys(resp.elements.models).forEach(index => {
                var model = resp.elements.models[index];
                $('.js-select-model .filters__dropdown').append(`<label class="checkbox-container">` +
                    model.name+
                    `<input type="radio" name="model" class="elemFilter" data-clean="year" data-name="${model.name}" data-element="model" data-value="${model.slug}" value="${model.slug}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });

        }
        if (resp.elements.years !== undefined) {

            $('.js-select-year').parent().show();
            $('.js-select-year .filters__dropdown').html('');
            Object.keys(resp.elements.years).forEach(index => {
                var year = resp.elements.years[index];
                $('.js-select-year .filters__dropdown').append(`<label class="checkbox-container">` +
                    year+
                    `<input type="radio" name="year" class="elemFilter" data-name="${year}" data-element="year" data-value="${year}" value="${year}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);
            });

        }
        if (resp.elements.request) {

            $('.js-select-brand .filters__dropdown input[value="'+resp.elements.request.brand+'"]').trigger('click');
            $('.js-select-model .filters__dropdown input[value="'+resp.elements.request.model+'"]').trigger('click');
            $('.js-select-year .filters__dropdown input[value="'+resp.elements.request.year+'"]').trigger('click');

        }
        if (resp.elements.slug !== undefined) {

            var urlData = {
                pathname: '/'+resp.elements.slug,
                search: ''
            };
            historial.push(urlData);

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
        let response = await axios.post('{{ route('ventor.ajax.applications')}}', params);
        let {data} = response;
        results(data);

    }
    $(document).ready(function(){

        let data = $('#buscadorAjax').serializeArray();
        search(data);

    });
</script>