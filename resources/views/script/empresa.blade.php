<script>
    $('.numeros__items__text a').on('click', function(e) {

        e.preventDefault();
        if ($(this).parent().prev().hasClass('--open')) {

            $(this).parent().prev().removeClass('--open');
            $(this).text('ver más');

        } else {

            $(this).parent().prev().addClass('--open');
            $(this).text('ver menos');

        }

    });
    $('.historia__item__top').on('click', function(e) {

        if ($(this).hasClass('--active')) {

            $(this).removeClass('--active');
            $(this).find('.fas.fa-chevron-up').removeClass('--active');
            $(this).next().removeClass('--active');

        } else {

            $(this).addClass('--active');
            $(this).find('.fas.fa-chevron-up').addClass('--active');
            $(this).next().addClass('--active');

        }
    })
</script>