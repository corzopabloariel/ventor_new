require('./bootstrap');

import Swal from 'sweetalert2'
import Splide from '@splidejs/splide'


const formatter = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
});
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

window.Ventor = {
    send: function(evt) {
        let target = this;
        let url = target.action;
        let method = target.method;
        let formData = new FormData(t);
        grecaptcha.ready(function() {
            $(target).find(".form-control").prop( "readonly" , true );
            $(target).find(".btn").prop("disabled", true);
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            grecaptcha.execute(document.querySelector('meta[name="captcha"]').content, {action: 'contact'}).then( function( token ) {
                formData.append( "token", token );
                axios({
                    method: method,
                    url: url,
                    data: formData,
                    responseType: 'json',
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
                .then((res) => {
                    $(target).find(".form-control").prop("readonly", false);
                    $(target).find(".btn").prop("disabled", false);
                    if(res.data.error === 0) {
                        $(target).find(".form-control").val("");
                        $(target).find(".btn").prop("disabled", false);
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
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const card__home = document.querySelector('#card-slider');
    const card__contact = document.querySelector('#card-slider-contact');
    const form__contact = document.querySelector('#form--contact');

    if (form__contact) {
        form__contact.addEventListener('submit', window.Ventor.send);
    }
    if (card__home) {
        new Splide('#card-slider', {
            perPage    : 2,
            breakpoints: {
                '425': {
                    perPage: 1,
                }
            },
            pagination: false,
        }).mount();
    }
    if (card__contact) {
        new Splide('#card-slider-contact', {
            perPage    : 1,
            breakpoints: {
                600: {
                    perPage: 1,
                }
            },
        } ).mount();
    }
});