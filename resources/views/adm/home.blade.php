@push('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script src="{{ asset('js/shortcut.js') }}"></script>
<script src="{{ asset('js/pyrus.js') . '?t=' . time() }}"></script>
<script src="{{ asset('js/basic.js') . '?t=' . time() }}"></script>
<script>
const actualizarProductsFunction = function(t) {
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
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            Connect.one(`${url_simple+url_basic}products/load`, data => {
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
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarClientsFunction = function(t) {
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
            Connect.one(`${url_simple+url_basic}clients/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.txt
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.txt
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarEmployeesFunction = function(t) {
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
            Connect.one(`${url_simple+url_basic}employees/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.txt
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.txt
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarSellersFunction = function(t) {
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
            Connect.one(`${url_simple+url_basic}sellers/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.txt
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.txt
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarTransportsFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Transportes\"",
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
            Connect.one(`${url_simple+url_basic}transports/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.txt
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.txt
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
</script>
@endpush
<section class="my-3">
    <div class="container-fluid">
        <div class="p-5 bg-white">
            <h1 class="text-center text-welcome">Bienvenido {{Auth::user()->name}}</h1>
        </div>
        <div class="p-5 bg-white mt-3">
            <button type="button" onclick="actualizarProductsFunction();" class="btn btn-lg btn-primary">Actualizar productos</button>
            <button type="button" onclick="actualizarClientsFunction();" class="btn btn-lg btn-info">Actualizar clientes</button>
            <button type="button" onclick="actualizarEmployeesFunction();" class="btn btn-lg btn-success">Actualizar empleados</button>
            <button type="button" onclick="actualizarSellersFunction();" class="btn btn-lg btn-danger">Actualizar vendedores</button>
            <button type="button" onclick="actualizarTransportsFunction();" class="btn btn-lg btn-warning">Actualizar transportes</button>
        </div>
    </div>
</section>