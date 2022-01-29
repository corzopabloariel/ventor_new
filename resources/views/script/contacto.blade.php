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
    $('#contactoForm').on('submit', async function(event) {

        event.preventDefault();
        var error = '';
        if ($('#nombre').val().trim() == '') {

            error += '<br/>- Ingrese un nombre';

        }
        if ($('#email').val().trim() == '') {

            error += '<br/>- Ingrese un email';

        }
        if ($('#mensaje').val().trim() == '') {

            error += '<br/>- Ingrese un mensaje';

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
            grecaptcha.execute('{{$ventor->captcha["public"]}}', {action: 'contact'}).then(async function(token) {
                var dataMail = {
                    nombre: $('#nombre').val(),
                    email: $('#email').val(),
                    mensaje: $('#mensaje').val(),
                    telefono: $('#telefono').val(),
                    mandar: $('#mandar').val(),
                    type: 'contact',
                    token
                }
                var response = await axios.post('{{ route('ventor.ajax.mail')}}', dataMail);
                var {data} = response;
                Toast.fire({
                    icon: data.error ? 'error' : 'success',
                    title: data.message
                });
                if (!data.error) {

                    $('#nombre').val('');
                    $('#email').val('');
                    $('#mensaje').val('');
                    $('#telefono').val('');
                    $('#mandar').val('');

                }
            })
        });
        return false;

    });
</script>