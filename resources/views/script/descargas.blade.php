<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    function getFile(data, response, error) {

        axios({
            url: '{{ route('descargas')}}',
            method: 'POST',
            data,
            responseType: 'blob',
        })
        .then(response)
        .catch(error);

    }
    $(document).on('click', '.notification a', function() {

        $('.notification').removeAttr('style');
        $('.notification').text('Espere');

    });
    $('#download__change').on('click', function(e) {

        e.preventDefault();
        $(this).closest('p').text('Vista simplificada');
        $('#download__simple').toggle();
        $('#download__normal').toggle();
        return;

    });
    $('.categorias__item__list__item__title').on('click', function(e) {

        e.preventDefault();
        $(this).parent().toggleClass('--open');
        return;

    });
    $('.download--element').on('change', async function(e) {

        const ELEMENT = $(this);
        const ID = ELEMENT.data('id');
        const NAME = ELEMENT.data('name');
        const NAME_EXT = ELEMENT.find('option:selected').data('name_ext');
        const PART = ELEMENT.find('option:selected').text();
        const INDEX = ELEMENT.find('option:selected').data('index');
        const TYPE = ELEMENT.find('option:selected').data('type');
        const TITLE = NAME+': '+PART;
        $('.notification').removeAttr('style');
        if (ELEMENT.val() != '' || (INDEX != '-1' && ELEMENT.val() == '')) {

            @auth
            $('.notification').text('Espere').addClass('--loader');
            let data;
            try {

                let response = await axios.post('{{ route('descargas')}}');
                data = response.data;

            } catch (error) {

                ELEMENT.val('');
                data = error.response.data;
                $('.notification').removeClass('--loader');

            }
            if (!data.error) {

                getFile(
                    {
                        id: ID,
                        index: INDEX
                    },
                    response => {

                        var a = $("<a style='display: none;'/>");
                        var {data} = response;
                        $('.notification').removeClass('--loader');
                        ELEMENT.val('');
                        if (data.size > 0) {

                            var file = new Blob([data], {type: TYPE});
                            var fileURL = URL.createObjectURL(file);
                            a.attr("href", fileURL);
                            a.attr("download", NAME_EXT);
                            $("body").append(a);
                            a[0].click();
                            window.URL.revokeObjectURL(fileURL);
                            a.remove();

                        } else {

                            if (ID != '0') {

                                $('.notification').css('display','flex').html(`<a title="${TITLE}" target='_blank' href="{{URL::to('/')}}/files/descargas/${NAME_EXT}" download>Click aquí para descarga directa</a>`);

                            } else {

                                $('.notification').css('display','flex').html(`<a title="${TITLE}" target='_blank' href="{{URL::to('/')}}/file/${NAME_EXT}" download>Click aquí para descarga directa</a>`);

                            }

                        }

                    },
                    async error => {

                        const jsonError = await (new Response(error.error));
                        console.log('por acá',error, jsonError)
                        @guest
                        $('.notification').removeClass('--loader');
                        Swal.fire({
                            title: '¡Atención!',
                            icon: 'error',
                            html: `Ingrese a su cuenta para poder acceder al archivo <strong>${TITLE}</strong>`,
                            showCloseButton: true,
                            showCancelButton: true,
                            showConfirmButton: false,
                            cancelButtonText: 'Cerrar',
                        });
                        @endguest
                        @auth
                        if (ID != '0') {

                            $('.notification').css('display','flex').html(`<a title="${TITLE}" target='_blank' href="{{URL::to('/')}}/files/descargas/${NAME_EXT}" download>Click aquí para descarga directa</a>`);

                        } else {

                            $('.notification').css('display','flex').html(`<a title="${TITLE}" target='_blank' href="{{URL::to('/')}}/file/${NAME_EXT}" download>Click aquí para descarga directa</a>`);

                        }
                        @endauth

                    }
                );

            }
            if (data.error) {

                Swal.fire({
                    title: '¡Atención!',
                    icon: 'error',
                    html: data.message,
                    showCloseButton: true,
                    showCancelButton: true,
                    showConfirmButton: false,
                    cancelButtonText: 'Cerrar',
                });

            }
            @endauth
            @guest
            Swal.fire({
                title: '¡Atención!',
                icon: 'warning',
                html: `Ingrese a su cuenta para poder acceder al archivo <strong>${TITLE}</strong>`,
                showCloseButton: true,
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: 'Cerrar',
            });
            @endguest

        }
    });

</script>