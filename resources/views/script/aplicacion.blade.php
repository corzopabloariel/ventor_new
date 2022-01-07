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

    function results(resp) {

        /*if (!$('.cart__float .--count').length && !resp.cart.error && resp.cart.elements !== undefined && resp.cart.elements.total != 0) {

            $('body').prepend('<div class="cart__float"><div class="--count">'+resp.cart.elements.total+'</div><i class="fas fa-shopping-cart"></i></div>');

        }*/
        console.log(resp)
        //$('#product-main').html(resp.productsHTML);
        $('#buscadorAjax .elemDelete').remove();
        $('#ventorProducts .overlay').removeClass('--active');
        $('.js-select-brand .filters__dropdown').html('');
        /*Object.keys(resp.brands).forEach(index => {
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
        historial.push(urlData);*/

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