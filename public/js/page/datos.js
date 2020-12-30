window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
const enviar = function(t) {
    let url = t.action;
    let method = t.method;
    let formData = new FormData(t);
    grecaptcha.ready(function() {
        $(t).find(".form-control").prop( "readonly" , true );
        Toast.fire({
            icon: 'warning',
            title: 'Espere'
        });
        grecaptcha.execute(document.querySelector('meta[name="captcha"]').content, {action: 'datosCliente'}).then( function( token ) {
            formData.append( "token", token );
            axios({
                method: method,
                url: url,
                data: formData,
                responseType: 'json',
                config: { headers: {'Content-Type': 'multipart/form-data' }}
            })
            .then((res) => {
                $(t).find(".form-control").prop("readonly", false);
                if(res.data.error === 0) {
                    $(t).find(".form-control").val("");
                    Toast.fire({
                        icon: 'success',
                        title: res.data.mssg
                    });
                } else
                    Toast.fire({
                        icon: 'error',
                        title: res.data.mssg
                    });
            })
            .catch((err) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Ocurrió un error'
                });
            });
        });
    });
};