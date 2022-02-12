<script src="https://www.google.com/recaptcha/api.js?render={{ $ventor->captcha['public'] }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
    $('#pagoForm').on('submit', async function(event) {

        event.preventDefault();
        var error = '';
        if ($('#nrocliente').val().trim() == '') {

            error += '<br/>- Ingrese Nro de Cliente';

        }
        if ($('#razon').val().trim() == '') {

            error += '<br/>- Ingrese Razón Social';

        }
        if ($('#fecha').val().trim() == '') {

            error += '<br/>- Ingrese Fecha';

        }
        if ($('#importe').val().trim() == '') {

            error += '<br/>- Ingrese Importe';

        }
        if ($('#banco').val().trim() == '') {

            error += '<br/>- Ingrese Banco';

        }
        if ($('#fecha').val().trim() == '') {

            error += '<br/>- Ingrese Sucursal';

        }
        if ($('#facturas').val().trim() == '') {

            error += '<br/>- Ingrese Facturas';

        }
        if (error != '') {

            Toast.fire({
                icon: 'error',
                title: '<strong>Complete el formulario</strong>'+error
            });
            return false;

        }
        Toast.fire({
            icon: 'warning',
            title: 'Enviado informe de pago'
        });
        grecaptcha.ready(function() {
            grecaptcha.execute('{{$ventor->captcha["public"]}}', {action: 'pagos'}).then(async function(token) {
                var dataMail = {
                    nrocliente: $('#nrocliente').val(),
                    razon: $('#razon').val(),
                    fecha: $('#fecha').val(),
                    importe: $('#importe').val(),
                    banco: $('#banco').val(),
                    sucursal: $('#sucursal').val(),
                    facturas: $('#facturas').val(),
                    descuento: $('#descuento').val(),
                    observaciones: $('#observaciones').val(),
                    type: 'pagos',
                    token
                }
                console.log(dataMail)
                var response = await axios.post('{{ route('ventor.ajax.mail')}}', dataMail);
                var {data} = response;
                Toast.fire({
                    icon: data.error ? 'error' : 'success',
                    title: data.message
                });
                if (!data.error) {

                    $('#nrocliente').val('');
                    $('#razon').val('');
                    $('#fecha').val('');
                    $('#importe').val('');
                    $('#banco').val('');
                    $('#sucursal').val('');
                    $('#facturas').val('');
                    $('#descuento').val('');
                    $('#observaciones').val('');

                }
            })
        });
        return false;

    });
</script>