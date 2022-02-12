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
    $('#datoForm').on('submit', async function(event) {

        event.preventDefault();
        var error = '';
        if (
            $('#responsable').val().trim() == '' &&
            $('#razon').val().trim() == '' &&
            $('#documento').val().trim() == '' &&
            $('#telefono').val().trim() == '' &&
            $('#email').val().trim() == ''
        ) {

            error += '<br/>- Debe completar alguno de los campos del formulario';

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
            title: 'Enviado contacto'
        });
        grecaptcha.ready(function() {
            grecaptcha.execute('{{$ventor->captcha["public"]}}', {action: 'datos'}).then(async function(token) {
                var dataMail = {
                    responsable: $('#responsable').val(),
                    razon: $('#razon').val(),
                    documento: $('#documento').val(),
                    telefono: $('#telefono').val(),
                    email: $('#email').val(),
                    observaciones: $('#observaciones').val(),
                    type: 'datos',
                    user_id: USER_ID,
                    token
                }
                var response = await axios.post('{{ route('ventor.ajax.mail')}}', dataMail);
                var {data} = response;
                Toast.fire({
                    icon: data.error ? 'error' : 'success',
                    title: data.message
                });
                if (!data.error) {

                    $('#observaciones').val('');

                }
            })
        });
        return false;

    });
</script>