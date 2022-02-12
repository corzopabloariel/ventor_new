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
    $('#transmisionForm').on('submit', async function(event) {

        event.preventDefault();
        var error = '';
        if ($('#nombre').val().trim() == '') {

            error += '<br/>- Ingrese Nombre';

        }
        if ($('#email').val().trim() == '') {

            error += '<br/>- Ingrese un Email';

        }
        if ($('#potencia').val().trim() == '') {

            error += '<br/>- Ingrese Potencia HP';

        }
        if ($('#factor').val().trim() == '') {

            error += '<br/>- Ingrese Factor de servicio';

        }
        if ($('#poleaMotor').val().trim() == '') {

            error += '<br/>- Ingrese RPM polea motor';

        }
        if ($('#poleaConducida').val().trim() == '') {

            error += '<br/>- Ingrese RPM polea conducida';

        }
        if ($('#centroMin').val().trim() == '') {

            error += '<br/>- Ingrese Entre centro Min. (mm)';

        }
        if ($('#centroMax').val().trim() == '') {

            error += '<br/>- Ingrese Entre centro Máx. (mm)';

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
            title: 'Enviado análisis de transmisión'
        });
        var dataMail = {}
        $("#transmisionForm").serializeArray().forEach(i => {

            dataMail[i.name] = i.value;

        })
        grecaptcha.ready(function() {
            grecaptcha.execute('{{$ventor->captcha["public"]}}', {action: 'transmision'}).then(async function(token) {
                dataMail.type = 'transmision';
                dataMail.token = token;
                console.log(dataMail)
                var response = await axios.post('{{ route('ventor.ajax.mail')}}', dataMail);
                var {data} = response;
                Toast.fire({
                    icon: data.error ? 'error' : 'success',
                    title: data.message
                });
                if (!data.error) {

                    $('#nombre').val('');
                    $('#email').val('');
                    $('#telefono').val('');
                    $('#domicilio').val('');
                    $('#factor').val('');
                    $('#potencia').val('');
                    $('#poleaMotor').val('');
                    $('#poleaConducida').val('');
                    $('#centroMin').val('');
                    $('#centroMax').val('');
                    $('#mensaje').val('');

                }
            })
        });
        return false;

    });
</script>