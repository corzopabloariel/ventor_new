<script>
const actualizarFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Vendedores\"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            window.pyrus.call(`${url_simple+url_basic}${window.pyrus.getObjeto().ROUTE}/load`, data => {
                'use strict'
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                    setTimeout(() => {
                        location.reload(data.url_search)
                    }, 2000);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            });
        }
    });
};

const cartFunction = function(t, id) {

    window.pyrus.call(`${url_simple+url_basic}${window.pyrus.getObjeto().ROUTE}/cart/${id}`, response => {
        let {data} = response;
        let modal = document.querySelector('#sellerCart');
        modal.querySelector('.modal-title').innerText = `${data.seller.name}`;
        document.querySelector('#colFormCart').value = data.seller.config.other.cart;
        modal.querySelector('form').action = `${url_simple+url_basic}${window.pyrus.getObjeto().ROUTE}/cart/${id}`;
        $(modal).modal('show');
    });

};

const cartForm = function(t) {
    let formData = new FormData(t);
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    window.pyrus.call(t.action, response => {
        let modal = document.querySelector('#sellerCart');
        let {data} = response;
        Toast.fire({
            icon: 'success',
            title: data.message
        });
        $(modal).modal('hide');
    }, "post", formData);
};
</script>