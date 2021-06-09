import Swal from '../../node_modules/sweetalert2/dist/sweetalert2.all'

require('./bootstrap');

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



document.addEventListener('DOMContentLoaded', function () {
    if ( document.querySelector('#card-slider')) {
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
} );