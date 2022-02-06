<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-start',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    const passwordSubmit = async (t) => {

        var response = await axios.post(t.action, {
            password: $('#input-pass').val()
        });
        var {data} = response;
        if (data.error) {

            Toast.fire({
                icon: 'error',
                title: data.message
            });

        } else {

            $('#input-pass').val('');
            Toast.fire({
                icon: 'success',
                title: data.message
            });

        }

    };
    const passwordFunction = (t, id) => {

        $('[data-toggle="tooltip"]').tooltip('hide');
        let tr = $(t).closest("tr");
        var [nroCta, nroDoc, razonSocial, , , , telefono, ] = tr.find('td');
        let data = [
            `<p><strong>Cuenta:</strong> ${nroCta.textContent}</p>`,
            `<p><strong>Documento:</strong> ${nroDoc.textContent}</p>`,
            `<p><strong>Télefono:</strong> ${telefono.textContent}</p>`,
            `<p><strong>Razón social:</strong> ${razonSocial.textContent}</p>`
        ];
        $("#modalClientPass form").attr("action", "{{URL::to('adm/clients')}}/"+id);
        $("#modalClientPass .modal-body-data").html(data.join(""));
        $("#modalClientPass").modal("show");

    };
    const accessFunction = async function(t, userId) {

        let response = await axios.post('{{ route('ventor.ajax.clientAction')}}', {type: 'access', userId});
        let {data} = response;
        if (!data.error) {

            window.open('{{URL::to("/")}}', "_blank");

        }

    };
    const cartFunction = async function(t, id) {

        if (window.cartId !== undefined) {

            delete window.cartId;

        }
        let tr = $(t).closest("tr");
        var [nroCta, nroDoc, razonSocial, , , , telefono, ] = tr.find('td');
        var response = await axios.post('{{route("ventor.client.cart")}}', {
            userId: id
        });
        var {data} = response;
        if (data.error) {

            Toast.fire({
                icon: 'error',
                title: data.message
            });

        } else {

            window.cartId = data.cartId;
            var products = data.element.map(function(p) {

                return p.product.name+' ('+p.product.code+'). <strong>Cantidad:</strong> '+p.quantity+'<br/>'+p.product.part.name+' | '+p.product.subpart.name;

            });
            if (products.length == 0) {

                products.push('SIN INFORMACIÓN');

            }
            $('#modalClientCartLabel').text(razonSocial.textContent);
            $('#modalClientCart .modal-body').html(products.join('<hr/>'));
            $('#modalClientCart').modal('show');

        }

    };
    $('#btnClearCart').on('click', function() {

        $('#modalClientCart').modal('hide');
        Swal.fire({
            title: "Atención!",
            html: "¿Limpiar el carrito de "+$('#modalClientCartLabel').text()+"?<br/><small>Esta acción se verá reflejada cuando el cliente se loguee o actualice su navegador</small>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',

            confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
            confirmButtonAriaLabel: 'Confirmar',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
            cancelButtonAriaLabel: 'Cancelar'
        }).then(async (result) => {

            if (result.value) {

                var url = '{{ route("ventor.client.cartDelete", ":cartId") }}';
                url = url.replace(':cartId', window.cartId);
                var response = await axios.delete(url);
                console.log(response)

            }

        });

    });
</script>