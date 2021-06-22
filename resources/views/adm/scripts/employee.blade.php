<script>
const actualizarFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Empleados\"",
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
const listarFunction = function() {
    window.pyrus.call(`${url_simple+url_basic}${window.pyrus.getObjeto().ROUTE}/list`, data => {
        $("#modalEmployee tbody").html(data.data.join(""));
        $("#modalEmployee").modal("show");
    });
};
const updateRoleSubmit = function(t) {
    let formData = new FormData(t);
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    window.pyrus.call(t.action, data => {
        'use strict'
        $(".role-user").prop("readonly", false);
        if (data.data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: data.data.txt
            });
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.txt
            });
        }
    }, "post", formData);
};
const accessFunction = function(t, id) {
    let formData = new FormData();
    formData.append('id', id);
    window.pyrus.call(`${url_simple+url_basic}${window.pyrus.getObjeto().ROUTE}/access`, data => {
        let {permissions} = data.data.user;
        document.querySelector('#modalAccess .modal-body > h4').innerText = `${data.data.user.name} #${data.data.user.docket}`;
        document.querySelector('#accessUserId').value = data.data.user.id;
        let actions = Object.keys(data.data.user.actions).map(a => {
            return `<th>${data.data.user.actions[a]}</th>`;
        });
        document.querySelector('#modalAccess .modal-body thead > tr').innerHTML = '<th>Ruta</th>'+actions.join('');
        let rows = Object.keys(data.data.user.routes).map(k => {
            let actions = Object.keys(data.data.user.actions).map(a => {
                let checked = '';
                if (permissions[k] !== undefined && permissions[k][a] !== undefined && permissions[k][a])
                    checked = 'checked';
                return `<td class="text-center"><input ${checked} type="checkbox" name="${k}[${a}]" value="1" /><input type="hidden" name="hidden[${k}][${a}]" value="1" /></td>`;
            });
            return `<td>${data.data.user.routes[k]}</td>`+actions.join('');
        });
        document.querySelector('#modalAccess .modal-body tbody').innerHTML = '<tr>'+rows.join('</tr><tr>')+'</tr>';
        $('#modalAccess').modal('show');
    }, 'post', formData);
};
const updatePermissionsSubmit = function(t) {
    let formData = new FormData(t);
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    window.pyrus.call(t.action, elements => {
        let {data} = elements; 
        if (data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: data.message
            });
            return;
        }
        Toast.fire({
            icon: 'error',
            title: data.message
        });
    }, "post", formData);
};
</script>