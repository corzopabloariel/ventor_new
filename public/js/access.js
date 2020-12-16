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
function enviar(evt) {
    evt.preventDefault();
    let target = this;
    let url = target.action;
    let method = target.method;
    let validate = target.checkValidity() && target.email.value != "" && target.tipo.value != "";
    console.log(validate)
    let formData = new FormData(this);
    if (!validate) {
        Toast.fire({
            icon: 'error',
            title: 'Complete los datos'
        });
        return null;
    }
    Array.prototype.forEach.call(target.querySelectorAll(".form-control"), i => i.setAttribute("readonly", true));
    grecaptcha.ready(function() {
        Toast.fire({
            icon: 'warning',
            title: 'Espere, enviando'
        });
        grecaptcha.execute(document.querySelector('meta[name="public-key"]').content, {action: 'access'}).then(function(token) {
            formData.append( "token", token );
            axios({
                method: method,
                url: url,
                data: formData,
                responseType: 'json',
                config: { headers: {'Content-Type': 'multipart/form-data' }}
            })
            .then((res) => {
                if (!parseInt(res.data.error)) {
                    if (res.data.success)
                        document.querySelector("#card-access").innerHTML = res.data.txt;
                    else
                        Toast.fire({
                            icon: 'error',
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
            })
            .then(() => {
                Array.prototype.forEach.call(target.querySelectorAll(".form-control"), i => i.removeAttribute("readonly"));
            });
        });
    });
};

document.addEventListener("DOMContentLoaded", function(event) {
    const form = document.querySelector("#form");

    form.addEventListener("submit", enviar);
});