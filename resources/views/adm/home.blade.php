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
            <hr>
            <div class="">
                <h3>Usuario de prueba @if($data["prueba"])<i class="fas fa-check-circle text-success"></i>@else<i class="fas fa-times-circle text-danger"></i>@endif</h3>
                <form class="mt-3" action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Nombre<small class="fas fa-star-of-life ml-1"></small></label>
                                @php
                                $value = "";
                                if($data["prueba"])
                                    $value = $data["prueba"]->name;
                                if (!empty(old('name')))
                                    $value = old('name');
                                @endphp
                                <input value="{{ $value }}" type="text" required name="name" placeholder="Nombre" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="">Límite de descargas p/hora</label>
                                @php
                                $value = "";
                                if ($data["prueba"] && !empty($data["prueba"]->limit))
                                    $value = $data["prueba"]->limit;
                                if (!empty(old('limit')))
                                    $value = old('limit');
                                @endphp
                                <input value="{{ $value }}" type="number" name="limit" min="0" placeholder="Límite de descargas" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="">Usuario<small class="fas fa-star-of-life ml-1"></small></label>
                                @php
                                $value = "";
                                if($data["prueba"])
                                    $value = $data["prueba"]->username;
                                if (!empty(old('username')))
                                    $value = old('username');
                                @endphp
                                <input value="{{ $value }}" type="text" required name="username" placeholder="Usuario" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Contraseña<small class="fas fa-star-of-life ml-1"></small></label>
                                <input type="password" name="password" placeholder="Contraseña" class="form-control form-control-lg">
                                <small class="form-text text-muted">Solo completar en caso de blanquear contraseña.</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="">Email</label>
                                @php
                                $value = "";
                                if ($data["prueba"] && !empty($data["prueba"]->email))
                                    $value = $data["prueba"]->email;
                                if (!empty(old('email')))
                                    $value = old('email');
                                @endphp
                                <input value="{{ $value }}" type="email" name="email" placeholder="Email" class="form-control form-control-lg">
                                <small class="form-text text-muted">Solo completar en caso de querer recibir copia del pedido.</small>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-dark">Cambiar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>