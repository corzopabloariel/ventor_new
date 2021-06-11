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
    },

    download: function(t, id) {
        let index = t.selectedIndex - 1;
        let file = t.item(index).text;
        let txt = t.dataset.name + ` [${file}]`;
        let link = $(t).next().children()[index];
        let value = $(t).val();
        if (value == "") {
            swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
                buttons: {
                    cerrar: true,
                },
            });
            return;
        }
        downloadTrack(t, id, link);
    },
    notFile: function(t) {
        let txt = t.dataset.name;
        swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
            buttons: {
                cerrar: true,
            },
        });
    },
    downloadTrack: function(t, id, link = null) {
        let txt = t.dataset.name
        let flag = false;
        if (link === null) {
            flag = true;
            link = document.createElement("a");
            link.href = t.dataset.href;
            link.download = t.dataset.name;
        }
        axios.get(document.querySelector('meta[name="url"]').content + "/track_download/" + id)
        .then(function (res) {
            if (res.data.error === 0) {
                link.click();
                if (flag)
                    link.remove();
            } else {
                swal("Atención!", res.data.msg, "error",{
                    buttons: {
                        cerrar: true,
                    },
                });
            }
        })
        .catch(err => {
            swal("Atención!", `Ingrese a su cuenta para poder acceder al archivo de ${txt}`, "error",{
                buttons: {
                    cerrar: true,
                },
            });
        });
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const card__home = document.querySelector('#card-slider');
    const card__contact = document.querySelector('#card-slider-contact');
    const card__enterprise = document.querySelector('#card-slider-enterprise');
    const slider__enterprise = document.querySelector('#slider-splide-enterprise');
    const form__contact = document.querySelector('#form--contact');
    const form__transmission = document.querySelector('#form--transmission');
    const form__pay = document.querySelector('#form--pay');
    const form__consult = document.querySelector('#form--consult');

    const card__download_publ = document.querySelector('#card-slider-PUBL');
    const card__download_cata = document.querySelector('#card-slider-CATA');
    const card__download_prec = document.querySelector('#card-slider-PREC');
    const card__download_otra = document.querySelector('#card-slider-OTRA');

    if (form__contact) {
        form__contact.addEventListener('submit', window.Ventor.send);
    }
    if (form__transmission) {
        form__transmission.addEventListener('submit', window.Ventor.send);
    }
    if (form__pay) {
        form__pay.addEventListener('submit', window.Ventor.send);
    }
    if (form__consult) {
        form__consult.addEventListener('submit', window.Ventor.send);
    }

    if (card__home) {
        new Splide(card__home, {
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
        new Splide(card__contact, {
            perPage    : 1,
            breakpoints: {
                600: {
                    perPage: 1,
                }
            },
        } ).mount();
    }
    if (card__enterprise) {
        new Splide(card__enterprise, {
            perPage    : 1,
            breakpoints: {
                600: {
                    perPage: 1,
                }
            },
        } ).mount();
    }
    if (slider__enterprise) {
        new Splide(slider__enterprise , {
            type        : 'loop',
            perPage     : 1,
            autoplay    : true,
            pauseOnHover: false,
            arrows      : false
        } ).mount();
    }
    //////////
    if (card__download_publ) {
        new Splide(card__download_publ, {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
    if (card__download_cata) {
        new Splide(card__download_cata, {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
    if (card__download_prec) {
        new Splide(card__download_prec, {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
    if (card__download_otra) {
        new Splide(card__download_otra, {
            perPage    : 2,
            pagination: true,
        } ).mount();
    }
});