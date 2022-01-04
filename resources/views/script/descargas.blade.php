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
    $('.download--element').on('change', async function(e) {

        const ELEMENT = $(this);
        const ID = ELEMENT.data('id');
        const NAME = ELEMENT.data('name');
        const NAME_EXT = ELEMENT.find('option:selected').data('name_ext');
        const PART = ELEMENT.find('option:selected').text();
        const INDEX = ELEMENT.find('option:selected').data('index');
        const TYPE = ELEMENT.find('option:selected').data('type');
        const TITLE = NAME+': '+PART;
        if (ELEMENT.val() != '' || (INDEX != '-1' && ELEMENT.val() == '')) {

            @auth
            $('.notification').addClass('--loader');
            try {

                let response = await axios.post('{{ route('descargas')}}');
                let {data} = response;
                console.log(data)

            } catch (error) {

                const jsonError = await (new Response(error.error));
                console.log(jsonError, error.error, error)

            }
            /*getFile(
                {
                    id: ID,
                    index: INDEX
                },
                response => {

                    $('.notification').removeClass('--loader');
                    var a = $("<a style='display: none;'/>");
                    var {data} = response;
                    if (data.size > 0) {

                        var file = new Blob([data], {type: TYPE});
                        var fileURL = URL.createObjectURL(file);
                        a.attr("href", fileURL);
                        a.attr("download", NAME_EXT);
                        $("body").append(a);
                        a[0].click();
                        window.URL.revokeObjectURL(fileURL);
                        a.remove();
                        ELEMENT.val('');

                    }

                },
                async error => {

                    const jsonError = await (new Response(error.error));
                    $('.notification').removeClass('--loader');
                    console.log('por acá',error, jsonError)
                    Swal.fire({
                        title: '¡Atención!',
                        icon: 'error',
                        html: `Ingrese a su cuenta para poder acceder al archivo <strong>${TITLE}</strong>`,
                        showCloseButton: true,
                        showCancelButton: true,
                        showConfirmButton: false,
                        cancelButtonText: 'Cerrar',
                    });

                }
            );*/
            @endauth
            @guest
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

        }
    });

</script>