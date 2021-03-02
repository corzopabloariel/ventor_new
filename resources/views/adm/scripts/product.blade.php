<script>
const fileFunction = function(t) {
    $("#modalProduct").modal("show");
};
const uploadFile = function(t) {
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    let formData = new FormData(t);
    axios({
        method: t.method,
        url: t.action,
        data: formData,
        responseType: 'json',
        config: { headers: {'Content-Type': 'multipart/form-data' }}
    })
    .then(res => {
        $("#inputGroupFile").val();
        if (res.data.error === 0) {
            $("#modalProduct").modal("hide");
            Toast.fire({
                icon: 'success',
                title: res.data.msg
            });
            if (res.data.update === 1)
                uploadProducts();
        } else {
            Toast.fire({
                icon: 'error',
                title: res.data.msg
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire(
            'Atención',
            'Error al subir el archivo',
            'error'
        );
    });
};
const actualizarFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Productos\"",
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
            uploadProducts();
        }
    });
};
const uploadProducts = function() {
    $("#notification").removeClass("d-none").addClass("d-flex");
    $("#notification .notification--text").text("En proceso");
    window.pyrus.call(`${url_simple+url_basic}${window.pyrus.getObjeto().ROUTE}/load`, data => {
        'use strict'
        $("#notification").removeClass("d-flex").addClass("d-none");
        $("#notification .notification--text").text("");
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
const categoriesFunction = function(t) {
    location.href = url_simple + url_basic + "products/categories";
};
</script>