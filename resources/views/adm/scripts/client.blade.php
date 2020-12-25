<script>
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
        `<p><strong>Dirección:</strong> ${client.address.direccion} (${client.address.codpos}). ${client.address.provincia}, ${client.address.localidad}</p>`,
        `<hr/>`,
        `<h4 class="text-center mb-2">Vendedor</h4>`,
        `<p><strong>Nombre:</strong> ${client.vendedor.nombre} (${client.vendedor.cod})</p>`,
        client.vendedor.email !== undefined ? `<p><strong>Email:</strong> <a target="blank" class="text-primary" href="mailto: ${client.vendedor.email}">${client.vendedor.email}</a><i class="fas fa-external-link-alt ml-1"></i></p>` : '',
        client.vendedor.telefono !== undefined ? `<p><strong>Teléfono:</strong> ${client.vendedor.telefono}</p>` : '',
        `<hr/>`,
        `<h4 class="text-center mb-2">Transportista</h4>`,
        `<p><strong>Nombre:</strong> ${client.transportista.nombre} (${client.transportista.cod})</p>`,
    ];
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
                title: data.data.txt
            });
            $("#modalClientPass").modal("hide");
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.txt
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
</script>