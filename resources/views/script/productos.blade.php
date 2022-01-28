@include('script.carrito')
<script>
    $('#appliedFiltersMobile').click(function() {

        $('#filterPage').val('1');
        $('#buscadorAjax').submit();
        $('body').removeClass('body--no-scroll');
        $('.filters').removeClass('--active');

    });
    $('.filters__item__dropdown').click(function (evt) {

        if ($(evt.target).is('a')) return;
        $(`.filters__item .--active`).toggleClass('--active');
        $(this).find("+ .filters__item__dropdown__content").toggleClass("--active");
        $(this).find("i").toggleClass("--active");

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
        if ($(this).closest('.filters__modal').hasClass('--open')) {

            $(this).closest('.filters__modal').removeClass('--open');

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
        let responseBrands = await axios.post('{{ route('ventor.ajax.products.brands')}}', params);
        let dataBrands = responseBrands.data;
        if (!dataBrands.error) {

            Object.keys(dataBrands.brands).forEach(index => {

                var brand = dataBrands.brands[index];
                $('.js-select-brand .filters__dropdown').append(`<label class="checkbox-container">` +
                    brand.name+
                    `<input ${data.request && data.request.brand && data.request.brand == brand.slug ? 'checked' : ''} type="radio" name="brand" class="elemFilter" data-name="${brand.name}" data-element="brand" data-value="${brand.slug}" value="${brand.slug}"/>`+
                    `<span class="checkmark-checkbox"></span>`+
                `</label>`);

            });

        }

    }
    function results(resp) {

        if (resp.error) {

            $('#ventorProducts .overlay').removeClass('--active');
            console.error(resp.message);
            return;

        }
        if (!$('.cart__float .--count').length && resp.cart && !resp.cart.error && resp.cart.elements !== undefined && resp.cart.elements.total != 0) {

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
            value: '{{$currentPage}}'
        });
        search(data);

    });
</script>