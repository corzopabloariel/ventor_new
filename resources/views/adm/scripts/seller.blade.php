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
            window.pyrus.call(`${url_simple+url_basic}${window.pyrus.objeto.ROUTE}/load`, data => {
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.txt
                    });
                    setTimeout(() => {
                        location.reload(data.url_search)
                    }, 2000);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.txt
                    });
                }
            });
        }
    });
};
</script>