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
</script>