<script>
const cartFunction = function(t, id) {
    let formData = new FormData();
    let url = url_simple + url_basic + window.pyrus.getObjeto().TABLE + '/cart:' + id;
    $("#notification").removeClass("d-none").addClass("d-flex");
    $("#notification .notification--text").text("En proceso");
    window.pyrus.call(url, response => {
        let {data} = response;
        $("#notification").removeClass("d-flex").addClass("d-none");
        $("#notification .notification--text").text("");
        if (data.error === 0) {

            if (data.showBtn) {

                $('#btnClearCart').show();
                $('#btnClearCart').data('id', id);

            } else {

                $('#btnClearCart').hide();
                $('#btnClearCart').data('id', '');

            }
            window.localStorage.client = JSON.stringify(data.client);
            $('#modalClientCartLabel').text(data.client.razon_social);
            $('#modalClientCart tbody').html(data.data);
            $('#modalClientCart').modal('show');
            $('#modalClientCart p').text('');
            if (data.showBtn) {

                $('#modalClientCart p').text(`Última actualización: ${data.cart.updated_at}`)

            }

        } else {

            $('#btnClearCart').hide();
            Toast.fire({
                icon: 'error',
                title: data.txt
            });

        }
    }, "post", formData);
};
const accessFunction = function(t, id) {
    let formData = new FormData();
    let url = url_simple + url_basic + window.pyrus.getObjeto().TABLE + '/access:' + id
    window.pyrus.call(url, data => {
        if (data.data.error === 0) {
            window.open(url_simple + 'pedido','_blank');
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.message
            });
        }
    }, "post", formData);
};
const actualizarFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Clientes\"",
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
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            window.pyrus.call(`${url_simple+url_basic}${window.pyrus.getObjeto().ROUTE}/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
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

const passwordFunction = (t, id) => {
    $('[data-toggle="tooltip"]').tooltip('hide');
    let pos = $(t).closest("tr").index();
    let client = window.data.elements.data[pos];
    let data = [
        `<p><strong>Cuenta:</strong> ${client.nrocta}</p>`,
        client.razon_social !== undefined ? `<p><strong>Razón social:</strong> ${client.razon_social}</p>` : '',
        client.nrodoc !== undefined ? `<p><strong>Documento:</strong> ${client.nrodoc}</p>` : '',
        client.telefn !== undefined ? `<p><strong>Télefono:</strong> ${client.telefn}</p>` : '',
        client.direml !== undefined ? `<p><strong>Email:</strong> <a target="blank" class="text-primary" href="mailto: ${client.direml}">${client.direml}</a><i class="fas fa-external-link-alt ml-1"></i></p>` : '',
        `<p><strong>Dirección:</strong> ${client.address.direccion} (${client.address.codpos}). ${client.address.provincia}, ${client.address.localidad}</p>`
    ];
    $("#modalClientPass form").attr("action", url_simple + url_basic + "clients/" + client._id);
    $("#modalClientPass .modal-body-data").html(data.join(""));
    $("#modalClientPass").modal("show");
};
const dataFunction = (t, id) => {
    $('[data-toggle="tooltip"]').tooltip('hide');
    let pos = $(t).closest("tr").index();
    let client = window.data.elements.data[pos];
    let data = [
        `<p><strong>Cuenta:</strong> ${client.nrocta}</p>`,
        client.razon_social !== undefined ? `<p><strong>Razón social:</strong> ${client.razon_social}</p>` : '',
        client.nrodoc !== undefined ? `<p><strong>Documento:</strong> ${client.nrodoc}</p>` : '',
        client.telefn !== undefined ? `<p><strong>Télefono:</strong> ${client.telefn}</p>` : '',
        client.direml !== undefined ? `<p><strong>Email:</strong> <a target="blank" class="text-primary" href="mailto: ${client.direml}">${client.direml}</a><i class="fas fa-external-link-alt ml-1"></i></p>` : '',
        `<p><strong>Dirección:</strong> ${client.address.direccion} (${client.address.codpos}). ${client.address.provincia}, ${client.address.localidad}</p>`
    ];
    if (client.vendedor !== undefined) {
        data.push(
            `<hr/>`,
            `<h4 class="text-center mb-2">Vendedor</h4>`,
            `<p><strong>Nombre:</strong> ${client.vendedor.nombre} (${client.vendedor.cod !== undefined ? client.vendedor.cod : client.vendedor.code})</p>`,
            client.vendedor.email !== undefined && client.vendedor.email !== null ? `<p><strong>Email:</strong> <a target="blank" class="text-primary" href="mailto: ${client.vendedor.email}">${client.vendedor.email}</a><i class="fas fa-external-link-alt ml-1"></i></p>` : '',
            client.vendedor.telefono !== undefined && client.vendedor.telefono !== null ? `<p><strong>Teléfono:</strong> ${client.vendedor.telefono}</p>` : '',
        );
    } else {
        data.push(
            `<hr/>`,
            `<h4 class="">Sin vendedor</h4>`
        );
    }
    if (client.transportista !== undefined) {
        data.push(
            `<hr/>`,
            `<h4 class="text-center mb-2">Transportista</h4>`,
            `<p><strong>Nombre:</strong> ${client.transportista.nombre} (${client.transportista.cod !== undefined ? client.transportista.cod : client.transportista.code})</p>`
        );
    } else {
        data.push(
            `<hr/>`,
            `<h4 class="">Sin transporte</h4>`
        );
    }
    $("#modalClient .modal-body").html(data.join(""));
    $("#modalClient").modal("show");
};
const passwordSubmit = t => {
    let formData = new FormData(t);
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    $("#input-pass, #input-notice").prop("readonly", true);
    window.pyrus.call(t.action, data => {
        'use strict'

        $("#input-pass, #input-notice").prop("readonly", false);
        if (data.data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: data.data.message
            });
            $("#modalClientPass").modal("hide");
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.message
            });
        }
    }, "post", formData);
};

$('#modalClientPass').on('hidden.bs.modal', function (e) {
    $("#modalClientPass form").attr("action", "");
    $("#modalClientPass .modal-body-data").html("");
    $("#input-pass").val("");
    $("#input-notice").prop("checked", false)
});

$('#btnClearCart').on('click', function() {
    $('#modalClientCart').modal('hide');
    Swal.fire({
        title: "Atención!",
        html: "¿Limpiar el carrito de "+$('#modalClientCartLabel').text()+"?<br/><small>Esta acción se verá reflejada cuando el cliente se loguee o actualice su navegador</small>",
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
            let formData = new FormData();
            let client = JSON.parse(window.localStorage.client);
            formData.set('username', client.nrodoc);
            formData.set('empty', 1);
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            Connect.post(`${url_simple}pedido/checkout`, formData, data => {
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            });
        }
    });
});
</script>